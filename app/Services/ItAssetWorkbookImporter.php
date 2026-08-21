<?php

namespace App\Services;

use App\Models\ItAsset;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use PharData;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use RuntimeException;
use Throwable;

class ItAssetWorkbookImporter
{
    /** @var array<string, string> */
    private const COLUMN_MAP = [
        'assetTag' => 'asset_tag',
        'assetName' => 'asset_name',
        'category' => 'category',
        'status' => 'status',
        'condition' => 'condition',
        'branch' => 'branch',
        'assignedUser' => 'assigned_user',
        'department' => 'department',
        'location' => 'location',
        'serialNumber' => 'serial_number',
        'brand' => 'brand',
        'model' => 'model',
        'ipAddress' => 'ip_address',
        'macAddress' => 'mac_address',
        'purchaseDate' => 'purchase_date',
        'warrantyStart' => 'warranty_start',
        'warrantyEnd' => 'warranty_end',
        'supplier' => 'supplier',
        'remarks' => 'remarks',
    ];

    /** @var array<string, int> */
    private const FIELD_LIMITS = [
        'asset_tag' => 150,
        'asset_name' => 255,
        'category' => 100,
        'status' => 100,
        'condition' => 150,
        'branch' => 150,
        'assigned_user' => 150,
        'department' => 150,
        'location' => 190,
        'serial_number' => 190,
        'brand' => 120,
        'model' => 190,
        'ip_address' => 45,
        'mac_address' => 50,
        'purchase_date' => 50,
        'warranty_start' => 50,
        'warranty_end' => 50,
        'supplier' => 190,
    ];

    public function import(
        string $path,
        bool $replace = false,
        ?string $sourceFile = null
    ): int {
        $resolvedPath = realpath($path);

        if ($resolvedPath === false || ! is_file($resolvedPath)) {
            throw new InvalidArgumentException("Workbook not found: {$path}");
        }

        $sourceFile = mb_substr(
            basename(str_replace('\\', '/', $sourceFile ?? $resolvedPath)),
            0,
            255
        );
        $extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));

        if (! in_array($extension, ['xls', 'xlsx'], true)) {
            throw new InvalidArgumentException('The IT asset source must be an .xls or .xlsx workbook.');
        }

        $temporaryCopy = null;
        $readPath = $resolvedPath;

        if (strtolower(pathinfo($readPath, PATHINFO_EXTENSION)) !== $extension) {
            $temporaryBase = tempnam(sys_get_temp_dir(), 'it-assets-import-');

            if ($temporaryBase === false) {
                throw new RuntimeException('Unable to prepare the uploaded IT asset workbook.');
            }

            unlink($temporaryBase);
            $temporaryCopy = $temporaryBase.'.'.$extension;

            if (! copy($resolvedPath, $temporaryCopy)) {
                throw new RuntimeException('Unable to read the uploaded IT asset workbook.');
            }

            $readPath = $temporaryCopy;
        }

        try {
            $worksheets = $extension === 'xlsx'
                ? $this->xlsxWorksheets($readPath)
                : $this->xlsWorksheets($readPath);
            $rows = [];
            $assetSheetFound = false;

            foreach ($worksheets as $worksheet) {
                $header = $this->headerColumns($worksheet['name'], $worksheet['rows']);

                if ($header === null) {
                    continue;
                }

                $assetSheetFound = true;
                $rows = array_merge(
                    $rows,
                    $this->assetRows(
                        $worksheet['name'],
                        $worksheet['rows'],
                        $sourceFile,
                        $header['row'],
                        $header['columns']
                    )
                );
            }

            if (! $assetSheetFound) {
                throw new InvalidArgumentException(
                    'The workbook does not contain the required IT asset header row. '
                    .'Expected these exact, case-sensitive headers: '.implode(', ', array_keys(self::COLUMN_MAP)).'.'
                );
            }

            if ($rows === []) {
                throw new InvalidArgumentException('The workbook does not contain any IT asset rows to import.');
            }

            return DB::transaction(function () use ($rows, $replace): int {
                if ($replace) {
                    ItAsset::withTrashed()->forceDelete();
                }

                foreach ($rows as $row) {
                    $asset = ItAsset::withTrashed()->firstOrNew([
                        'source_file' => $row['source_file'],
                        'source_sheet' => $row['source_sheet'],
                        'source_row' => $row['source_row'],
                    ]);

                    $asset->fill($row);
                    $asset->deleted_at = null;
                    $asset->save();
                }

                return count($rows);
            });
        } finally {
            if ($temporaryCopy !== null && is_file($temporaryCopy)) {
                unlink($temporaryCopy);
            }
        }
    }

    /**
     * @return list<array{name: string, rows: array<int, array<int, string>>}>
     */
    private function xlsxWorksheets(string $path): array
    {
        try {
            $archive = new PharData($path);
        } catch (Throwable $exception) {
            throw new RuntimeException('Unable to open the Excel workbook.', previous: $exception);
        }

        $sharedStrings = $this->sharedStrings($archive);
        $relationships = $this->workbookRelationships($archive);
        $worksheets = [];

        foreach ($this->workbookSheets($archive) as $sheet) {
            $target = $relationships[$sheet['relationship_id']] ?? null;

            if ($target === null) {
                throw new RuntimeException("Worksheet relationship missing for {$sheet['name']}.");
            }

            $worksheetPath = $this->worksheetPath($target);
            $worksheets[] = [
                'name' => $sheet['name'],
                'rows' => $this->worksheetRows($archive, $worksheetPath, $sharedStrings),
            ];
        }

        return $worksheets;
    }

    /**
     * @return list<array{name: string, rows: array<int, array<int, string>>}>
     */
    private function xlsWorksheets(string $path): array
    {
        try {
            $reader = new Xls;
            $reader->setReadDataOnly(true);
            $workbook = $reader->load($path);
        } catch (Throwable $exception) {
            throw new RuntimeException('Unable to open the Excel workbook.', previous: $exception);
        }

        $worksheets = [];

        try {
            foreach ($workbook->getWorksheetIterator() as $worksheet) {
                $rows = [];
                $highestRow = $worksheet->getHighestDataRow();
                $highestColumn = Coordinate::columnIndexFromString($worksheet->getHighestDataColumn());

                for ($row = 1; $row <= $highestRow; $row++) {
                    for ($column = 1; $column <= $highestColumn; $column++) {
                        $value = $worksheet->getCell([$column, $row])->getValue();

                        if ($value === null || $value === '') {
                            continue;
                        }

                        $rows[$row][$column] = $this->normalizeCellValue($value);
                    }
                }

                $worksheets[] = ['name' => $worksheet->getTitle(), 'rows' => $rows];
            }
        } finally {
            $workbook->disconnectWorksheets();
        }

        return $worksheets;
    }

    /** @return list<string> */
    private function sharedStrings(PharData $archive): array
    {
        if (! isset($archive['xl/sharedStrings.xml'])) {
            return [];
        }

        $xpath = $this->xpath($archive['xl/sharedStrings.xml']->getContent());
        $strings = [];

        foreach ($xpath->query('//*[local-name()="si"]') ?: [] as $node) {
            $strings[] = $node->textContent;
        }

        return $strings;
    }

    /** @return array<string, string> */
    private function workbookRelationships(PharData $archive): array
    {
        $xpath = $this->xpath($this->archiveEntry($archive, 'xl/_rels/workbook.xml.rels'));
        $relationships = [];

        foreach ($xpath->query('//*[local-name()="Relationship"]') ?: [] as $node) {
            if ($node instanceof DOMElement) {
                $relationships[$node->getAttribute('Id')] = $node->getAttribute('Target');
            }
        }

        return $relationships;
    }

    /** @return list<array{name: string, relationship_id: string}> */
    private function workbookSheets(PharData $archive): array
    {
        $xpath = $this->xpath($this->archiveEntry($archive, 'xl/workbook.xml'));
        $sheets = [];

        foreach ($xpath->query('//*[local-name()="sheet"]') ?: [] as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $sheets[] = [
                'name' => $node->getAttribute('name'),
                'relationship_id' => $node->getAttributeNS(
                    'http://schemas.openxmlformats.org/officeDocument/2006/relationships',
                    'id'
                ),
            ];
        }

        return $sheets;
    }

    /**
     * @param  list<string>  $sharedStrings
     * @return array<int, array<int, string>>
     */
    private function worksheetRows(PharData $archive, string $path, array $sharedStrings): array
    {
        $xpath = $this->xpath($this->archiveEntry($archive, $path));
        $rows = [];

        foreach ($xpath->query('//*[local-name()="sheetData"]/*[local-name()="row"]') ?: [] as $rowNode) {
            if (! $rowNode instanceof DOMElement) {
                continue;
            }

            $rowNumber = (int) $rowNode->getAttribute('r');

            foreach ($xpath->query('./*[local-name()="c"]', $rowNode) ?: [] as $cellNode) {
                if (! $cellNode instanceof DOMElement) {
                    continue;
                }

                $column = $this->columnNumber($cellNode->getAttribute('r'));
                $type = $cellNode->getAttribute('t');
                $valueNode = $xpath->query('./*[local-name()="v"]', $cellNode)?->item(0);
                $value = $valueNode?->textContent ?? '';

                if ($type === 's' && $value !== '') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = $cellNode->textContent;
                }

                $rows[$rowNumber][$column] = $this->normalizeCellValue($value);
            }
        }

        return $rows;
    }

    /**
     * @param  array<int, array<int, string>>  $sheetRows
     * @return array{row: int, columns: array<int, string>}|null
     */
    private function headerColumns(string $sheetName, array $sheetRows): ?array
    {
        foreach ($sheetRows as $rowNumber => $cells) {
            $headings = [];

            foreach ($cells as $column => $value) {
                $heading = $this->blankToNull($value);

                if ($heading !== null) {
                    $headings[$column] = $heading;
                }
            }

            if ($headings === []) {
                continue;
            }

            $recognized = array_intersect($headings, array_keys(self::COLUMN_MAP));

            if ($recognized === []) {
                return null;
            }

            $missing = array_values(array_diff(array_keys(self::COLUMN_MAP), $headings));

            if ($missing !== []) {
                throw new InvalidArgumentException(
                    "{$sheetName} row {$rowNumber}: missing required IT asset "
                    .str('header')->plural(count($missing)).': '.implode(', ', $missing)
                    .'. Headers are case-sensitive.'
                );
            }

            if (count($recognized) !== count(array_unique($recognized))) {
                throw new InvalidArgumentException(
                    "{$sheetName} row {$rowNumber}: IT asset headers may not be duplicated."
                );
            }

            $columns = [];

            foreach ($headings as $column => $heading) {
                if (isset(self::COLUMN_MAP[$heading])) {
                    $columns[$column] = self::COLUMN_MAP[$heading];
                }
            }

            return ['row' => $rowNumber, 'columns' => $columns];
        }

        return null;
    }

    /**
     * @param  array<int, array<int, string>>  $sheetRows
     * @param  array<int, string>  $columns
     * @return list<array<string, mixed>>
     */
    private function assetRows(
        string $sheetName,
        array $sheetRows,
        string $sourceFile,
        int $headerRowNumber,
        array $columns
    ): array {
        $assets = [];

        foreach ($sheetRows as $rowNumber => $cells) {
            if ($rowNumber === $headerRowNumber) {
                continue;
            }

            $row = [];

            foreach ($columns as $column => $attribute) {
                $row[$attribute] = $this->blankToNull($cells[$column] ?? null);
            }

            if ($this->rowIsEmpty($row)) {
                continue;
            }

            if ($row['category'] === null) {
                throw new InvalidArgumentException(
                    "{$sheetName} row {$rowNumber}: category is required."
                );
            }

            foreach (['purchase_date', 'warranty_start', 'warranty_end'] as $attribute) {
                $row[$attribute] = $this->normalizeDateValue($row[$attribute] ?? null);
            }

            $this->validateFieldLengths($row, $sheetName, $rowNumber);

            $row['source_file'] = $sourceFile;
            $row['source_sheet'] = $sheetName;
            $row['source_row'] = $rowNumber;
            $row['imported_at'] = now();
            $assets[] = $row;
        }

        return $assets;
    }

    /** @param array<string, mixed> $row */
    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null) {
                return false;
            }
        }

        return true;
    }

    /** @param array<string, mixed> $row */
    private function validateFieldLengths(array $row, string $sheetName, int $rowNumber): void
    {
        foreach (self::FIELD_LIMITS as $attribute => $maximum) {
            $value = $row[$attribute] ?? null;

            if ($value === null || mb_strlen((string) $value) <= $maximum) {
                continue;
            }

            $header = array_search($attribute, self::COLUMN_MAP, true) ?: $attribute;

            throw new InvalidArgumentException(
                "{$sheetName} row {$rowNumber}: {$header} may not exceed {$maximum} characters."
            );
        }
    }

    private function xpath(string $xml): DOMXPath
    {
        $document = new DOMDocument;

        if (! $document->loadXML($xml, LIBXML_NONET | LIBXML_COMPACT)) {
            throw new RuntimeException('The workbook contains invalid XML.');
        }

        return new DOMXPath($document);
    }

    private function archiveEntry(PharData $archive, string $path): string
    {
        if (! isset($archive[$path])) {
            throw new RuntimeException("Workbook entry missing: {$path}");
        }

        return $archive[$path]->getContent();
    }

    private function worksheetPath(string $target): string
    {
        $target = ltrim(str_replace('\\', '/', $target), '/');

        return str_starts_with($target, 'xl/') ? $target : 'xl/'.$target;
    }

    private function columnNumber(string $reference): int
    {
        preg_match('/^[A-Z]+/', strtoupper($reference), $matches);
        $number = 0;

        foreach (str_split($matches[0] ?? '') as $letter) {
            $number = ($number * 26) + (ord($letter) - 64);
        }

        return $number;
    }

    private function blankToNull(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function normalizeCellValue(mixed $value): string
    {
        return trim((string) $value);
    }

    private function normalizeDateValue(?string $value): ?string
    {
        if ($value === null || ! is_numeric($value)) {
            return $value;
        }

        $serial = (float) $value;

        if ($serial < 20000 || $serial > 80000) {
            return $value;
        }

        return Date::excelToDateTimeObject($serial)->format('Y-m-d');
    }
}
