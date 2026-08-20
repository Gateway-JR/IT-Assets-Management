<?php

namespace App\Services;

use App\Models\CctvSite;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use PharData;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use RuntimeException;
use Throwable;

class CctvInventoryWorkbookImporter
{
    /** @var array<string, string> */
    private const COLUMN_MAP = [
        'ID' => 'source_id',
        'Branch' => 'branch',
        'Region' => 'region',
        'Province' => 'province',
        'Business Unit' => 'business_unit',
        'Assigned Tech' => 'assigned_tech',
        'Total Camera' => 'total_cameras',
        'Online' => 'online_cameras',
        'Offline' => 'offline_cameras',
        'Recording Issue' => 'recording_issue_cameras',
        'NVR Status' => 'nvr_status',
        'Storage Used' => 'storage_status',
        'Recording Days' => 'recording_days',
        'Vendor' => 'vendor',
        'NVR Brand' => 'nvr_brand',
        'NVR Model' => 'nvr_model',
        'NVR RLP' => 'nvr_rlp',
        'HDD Capacity' => 'nvr_hdd_capacity',
        'Distribution' => 'distribution_status',
        'Remarks' => 'remarks',
        'Distribution Summary' => 'distribution_summary',
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

        $sourceFile = substr(
            basename(str_replace('\\', '/', $sourceFile ?? $resolvedPath)),
            0,
            255
        );
        $extension = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));

        if (! in_array($extension, ['xls', 'xlsx'], true)) {
            throw new InvalidArgumentException('The inventory source must be an .xls or .xlsx workbook.');
        }

        $temporaryCopy = null;
        $readPath = $resolvedPath;

        if (strtolower(pathinfo($readPath, PATHINFO_EXTENSION)) !== $extension) {
            $temporaryBase = tempnam(sys_get_temp_dir(), 'cctv-import-');

            if ($temporaryBase === false) {
                throw new RuntimeException('Unable to prepare the uploaded workbook.');
            }

            unlink($temporaryBase);
            $temporaryCopy = $temporaryBase.'.'.$extension;

            if (! copy($resolvedPath, $temporaryCopy)) {
                throw new RuntimeException('Unable to read the uploaded workbook.');
            }

            $readPath = $temporaryCopy;
        }

        try {
            $worksheets = $extension === 'xlsx'
                ? $this->xlsxWorksheets($readPath)
                : $this->xlsWorksheets($readPath);
            $rows = [];
            $inventorySheetFound = false;

            foreach ($worksheets as $worksheet) {
                if (! $this->hasTemplateHeaders($worksheet['rows'])) {
                    continue;
                }

                $inventorySheetFound = true;
                $rows = array_merge(
                    $rows,
                    $this->inventoryRows($worksheet['name'], $worksheet['rows'], $sourceFile)
                );
            }

            if (! $inventorySheetFound) {
                throw new InvalidArgumentException(
                    'This workbook does not match Gateway_CCTV_Monitoring_Template.xlsx.'
                );
            }

            if ($rows === []) {
                throw new InvalidArgumentException('The workbook does not contain any inventory rows to import.');
            }

            return DB::transaction(function () use ($rows, $replace): int {
                if ($replace) {
                    CctvSite::withTrashed()->forceDelete();
                }

                foreach ($rows as $row) {
                    CctvSite::withTrashed()->updateOrCreate(
                        ['source_sheet' => $row['source_sheet'], 'source_row' => $row['source_row']],
                        $row + ['deleted_at' => null]
                    );
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

            $worksheetPath = 'xl/'.ltrim(str_replace('\\', '/', $target), '/');
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
            $strings[] = trim($node->textContent);
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

    /** @param array<int, array<int, string>> $sheetRows */
    private function hasTemplateHeaders(array $sheetRows): bool
    {
        if ($sheetRows === []) {
            return false;
        }

        $headerRow = $sheetRows[min(array_keys($sheetRows))] ?? [];
        $headings = array_values(array_filter($headerRow));

        return array_diff(array_keys(self::COLUMN_MAP), $headings) === [];
    }

    /**
     * @param  array<int, array<int, string>>  $sheetRows
     * @return list<array<string, mixed>>
     */
    private function inventoryRows(string $sheetName, array $sheetRows, string $sourceFile): array
    {
        $headerRowNumber = min(array_keys($sheetRows));
        $headerRow = $sheetRows[$headerRowNumber] ?? [];
        $columns = [];

        foreach ($headerRow as $column => $heading) {
            if (isset(self::COLUMN_MAP[$heading])) {
                $columns[$column] = self::COLUMN_MAP[$heading];
            }
        }

        $carry = ['branch' => null, 'region' => null, 'province' => null];
        $inventory = [];

        foreach ($sheetRows as $rowNumber => $cells) {
            if ($rowNumber === $headerRowNumber) {
                continue;
            }

            $row = [];

            foreach ($columns as $column => $attribute) {
                $row[$attribute] = $this->blankToNull($cells[$column] ?? null);
            }

            foreach (array_keys($carry) as $attribute) {
                if ($row[$attribute] !== null) {
                    $carry[$attribute] = $row[$attribute];
                } else {
                    $row[$attribute] = $carry[$attribute];
                }
            }

            if (collect($row)->filter(fn (mixed $value): bool => $value !== null)->isEmpty()) {
                continue;
            }

            if ($row['branch'] === null) {
                throw new InvalidArgumentException(
                    "{$sheetName} row {$rowNumber}: Branch is required."
                );
            }

            $row['source_id'] = $this->optionalInteger(
                $row['source_id'] ?? null,
                $sheetName,
                $rowNumber,
                'ID'
            );

            foreach ([
                'total_cameras' => 'Total Camera',
                'online_cameras' => 'Online',
                'offline_cameras' => 'Offline',
                'recording_issue_cameras' => 'Recording Issue',
            ] as $attribute => $label) {
                $row[$attribute] = $this->cameraCount(
                    $row[$attribute] ?? null,
                    $sheetName,
                    $rowNumber,
                    $label
                );
            }

            if ($row['total_cameras'] !== $row['online_cameras'] + $row['offline_cameras']) {
                throw new InvalidArgumentException(
                    "{$sheetName} row {$rowNumber}: Total Camera must equal Online plus Offline."
                );
            }

            if ($row['recording_issue_cameras'] > $row['total_cameras']) {
                throw new InvalidArgumentException(
                    "{$sheetName} row {$rowNumber}: Recording Issue cannot exceed Total Camera."
                );
            }

            $row['source_file'] = $sourceFile;
            $row['source_sheet'] = $sheetName;
            $row['source_row'] = $rowNumber;
            $row['nvr_hdd_capacity_gb'] = $this->capacityInGigabytes($row['nvr_hdd_capacity'] ?? null);
            $row['imported_at'] = now();
            $inventory[] = $row;
        }

        return $inventory;
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

    private function columnNumber(string $reference): int
    {
        preg_match('/^[A-Z]+/', strtoupper($reference), $matches);
        $number = 0;

        foreach (str_split($matches[0] ?? '') as $letter) {
            $number = ($number * 26) + (ord($letter) - 64);
        }

        return $number;
    }

    private function blankToNull(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function optionalInteger(
        mixed $value,
        string $sheetName,
        int $rowNumber,
        string $label
    ): ?int {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_numeric($value) || (float) $value < 0 || floor((float) $value) !== (float) $value) {
            throw new InvalidArgumentException(
                "{$sheetName} row {$rowNumber}: {$label} must be a whole number."
            );
        }

        return (int) $value;
    }

    private function cameraCount(
        mixed $value,
        string $sheetName,
        int $rowNumber,
        string $label
    ): int {
        $count = $this->optionalInteger($value, $sheetName, $rowNumber, $label) ?? 0;

        if ($count > 65535) {
            throw new InvalidArgumentException(
                "{$sheetName} row {$rowNumber}: {$label} may not exceed 65,535."
            );
        }

        return $count;
    }

    private function normalizeCellValue(mixed $value): string
    {
        $value = (string) $value;

        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }

    private function capacityInGigabytes(?string $capacity): ?float
    {
        if ($capacity === null || ! preg_match('/([\d,.]+)\s*(TB|GB)?/i', $capacity, $matches)) {
            return null;
        }

        $value = (float) str_replace(',', '', $matches[1]);

        return strtoupper($matches[2] ?? 'GB') === 'TB' ? $value * 1024 : $value;
    }
}
