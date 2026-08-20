<?php

namespace App\Services;

use App\Models\CctvSite;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class CctvInventoryXlsExporter
{
    /** @var array<string, array<string, float>> */
    private const REGIONAL_SHEETS = [
        'Corrected NCR' => ['A' => 18, 'B' => 31, 'C' => 3, 'D' => 18, 'E' => 31],
        'Corrected Visayas' => ['A' => 30.5546875, 'B' => 46, 'C' => 3, 'D' => 34.88671875, 'E' => 70.5546875],
        'Corrected Mindanao' => ['A' => 17.44140625, 'B' => 66.21875, 'C' => 3, 'D' => 18, 'E' => 72.5546875],
    ];

    /** @var array<string, string> */
    private const LEGEND = [
        'Region' => 'Region where the branch is located.',
        'Branch' => 'Branch name.',
        'Total Camera' => 'Total number of CCTV cameras.',
        'Online Camera' => 'Number of cameras currently online.',
        'Offline Camera' => 'Number of cameras currently offline.',
        'Storage Capacity' => 'NVR/storage capacity (e.g., 4TB, 8TB).',
        'Retention Days' => 'Number of days CCTV footage is retained.',
        'Camera Brand' => 'CCTV camera brand.',
        'NVR Brand' => 'NVR brand.',
        'NVR RLP' => 'NVR RLP/reference information, when available.',
        'Camera Condition' => 'Overall camera condition/status.',
        'NVR Condition' => 'Overall NVR condition/status.',
        'Internet Provider' => 'Internet service provider.',
        'Internet Speed' => 'Internet speed (e.g., 100 Mbps).',
        'Issues/Remarks' => 'Any CCTV-related issues or additional remarks.',
        'Distribution Summary' => 'Camera distribution by area, e.g. Ground Floor: 8, 2nd Floor: 6.',
    ];

    /** @param  Collection<int, CctvSite>  $sites */
    public function create(Collection $sites): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getProperties()
            ->setCreator('Gateway IT Inventory System')
            ->setTitle('Gateway CCTV Monitoring Export')
            ->setSubject('Filtered CCTV inventory')
            ->setDescription('Filtered export using the corrected regional sheet layout.');

        $spreadsheet->getDefaultStyle()->getFont()
            ->setName('Calibri')
            ->setSize(11)
            ->getColor()->setARGB('FF243447');
        $spreadsheet->getDefaultStyle()->getAlignment()
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);

        $grouped = collect(array_fill_keys(array_keys(self::REGIONAL_SHEETS), null))
            ->map(fn (): Collection => collect());

        foreach ($sites as $site) {
            $grouped[$this->regionalSheetName($site)]->push($site);
        }

        foreach (array_keys(self::REGIONAL_SHEETS) as $index => $sheetName) {
            $worksheet = $index === 0
                ? $spreadsheet->getActiveSheet()
                : $spreadsheet->createSheet();
            $worksheet->setTitle($sheetName);
            $this->populateRegionalSheet($worksheet, $grouped[$sheetName], self::REGIONAL_SHEETS[$sheetName]);
        }

        $legend = $spreadsheet->createSheet();
        $legend->setTitle('Legend');
        $this->populateLegend($legend);
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /** @param  Collection<int, CctvSite>  $sites */
    public function write(Collection $sites, string $destination): void
    {
        $spreadsheet = $this->create($sites);
        $writer = new Xls($spreadsheet);
        $writer->setPreCalculateFormulas(false);
        $writer->save($destination);
        $spreadsheet->disconnectWorksheets();
    }

    /**
     * @param  Collection<int, CctvSite>  $sites
     * @param  array<string, float>  $widths
     */
    private function populateRegionalSheet(Worksheet $sheet, Collection $sites, array $widths): void
    {
        foreach ($widths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $this->applyReferenceMargins($sheet->getPageMargins());
        $row = 1;
        $separatorRows = $sheet->getTitle() === 'Corrected Visayas' ? 2 : 1;

        foreach ($sites->values()->chunk(2) as $pair) {
            $pair = $pair->values();
            $leftHeight = $this->writeRecord($sheet, $pair->get(0), 'A', 'B', $row);
            $rightHeight = $pair->has(1)
                ? $this->writeRecord($sheet, $pair->get(1), 'D', 'E', $row)
                : 0;

            $row += max($leftHeight, $rightHeight) + $separatorRows;
        }

        $lastRow = max(1, $row - 1);
        $sheet->getStyle("A1:E{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
    }

    private function writeRecord(Worksheet $sheet, CctvSite $site, string $labelColumn, string $valueColumn, int $startRow): int
    {
        $cameraCountsAreUnreported = in_array($site->source_sheet, ['NCR', 'Minadanao'], true)
            && $site->total_cameras === 0
            && $site->online_cameras === 0
            && $site->offline_cameras === 0
            && $site->recording_issue_cameras === 0;

        $fields = [
            ['ID', $site->source_id ?: $site->id],
            ['Branch', $site->branch],
            ['Region', $site->region],
            ['Province', $site->province],
            ['Business Unit', $site->business_unit],
            ['Assigned Tech', $site->assigned_tech],
            ['Total Camera', $cameraCountsAreUnreported ? null : $site->total_cameras],
            ['Online', $cameraCountsAreUnreported ? null : $site->online_cameras],
            ['Offline', $cameraCountsAreUnreported ? null : $site->offline_cameras],
            ['Recording Issue', $cameraCountsAreUnreported ? null : $site->recording_issue_cameras],
            ['NVR Status', $site->nvr_status],
            ['Storage Used', $site->storage_status],
            ['Vendor', $site->vendor],
            ['NVR Brand', $site->nvr_brand],
            ['NVR Model', $site->nvr_model],
            ['HDD Capacity', $site->nvr_hdd_capacity ?: ($site->nvr_hdd_capacity_gb ? number_format($site->nvr_hdd_capacity_gb).' GB' : null)],
            ['Distribution', $site->distribution_status],
        ];

        $row = $startRow;

        foreach ($fields as [$label, $value]) {
            $sheet->setCellValue("{$labelColumn}{$row}", $label);

            if (is_int($value) || is_float($value)) {
                $sheet->setCellValue("{$valueColumn}{$row}", $value);
            } elseif ($value !== null) {
                $sheet->setCellValueExplicit("{$valueColumn}{$row}", (string) $value, DataType::TYPE_STRING);
            }

            $sheet->getRowDimension($row)->setRowHeight($row === $startRow ? 19.2 : 16.8);
            $row++;
        }

        $idRange = "{$labelColumn}{$startRow}:{$valueColumn}{$startRow}";
        $sheet->getStyle($idRange)->getFont()
            ->setBold(true)
            ->getColor()->setARGB('FF1F3B53');

        $distributionRow = $startRow + 16;
        $distributionRange = "{$labelColumn}{$distributionRow}:{$valueColumn}{$distributionRow}";
        $sheet->getStyle($distributionRange)->getFont()
            ->setBold(true)
            ->getColor()->setARGB('FF28594F');

        foreach ($this->distributionItems($site->distribution_summary) as [$area, $count]) {
            $sheet->setCellValueExplicit("{$labelColumn}{$row}", '• '.$area, DataType::TYPE_STRING);

            if ($count !== null) {
                $sheet->setCellValue("{$valueColumn}{$row}", $count);
            }

            $sheet->getRowDimension($row)->setRowHeight(16.8);
            $row++;
        }

        $sheet->setCellValue("{$labelColumn}{$row}", 'Remarks');

        if ($site->remarks !== null) {
            $sheet->setCellValueExplicit("{$valueColumn}{$row}", $site->remarks, DataType::TYPE_STRING);
        }

        $sheet->getRowDimension($row)->setRowHeight(16.8);

        return ($row - $startRow) + 1;
    }

    /** @return list<array{0: string, 1: ?int}> */
    private function distributionItems(?string $summary): array
    {
        if ($summary === null || trim($summary) === '') {
            return [];
        }

        $items = preg_split('/\s*•\s*/u', trim($summary), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_map(function (string $item): array {
            $item = trim($item);

            if (preg_match('/^(.*?):\s*(\d+)\s*$/u', $item, $matches)) {
                return [trim($matches[1]), (int) $matches[2]];
            }

            return [$item, null];
        }, $items);
    }

    private function regionalSheetName(CctvSite $site): string
    {
        $source = strtolower((string) $site->source_sheet);

        if (str_contains($source, 'visayas')) {
            return 'Corrected Visayas';
        }

        if (str_contains($source, 'mindanao') || str_contains($source, 'minadanao')) {
            return 'Corrected Mindanao';
        }

        if (str_contains($source, 'ncr')) {
            return 'Corrected NCR';
        }

        $region = strtolower(trim((string) $site->region));

        if (str_contains($region, 'visayas') || preg_match('/region\s+(vi|vii|viii)\b/i', $region)) {
            return 'Corrected Visayas';
        }

        if ($region === 'nir' || preg_match('/region\s+(ix|x|xi|xii|xiii)\b/i', $region)) {
            return 'Corrected Mindanao';
        }

        return 'Corrected NCR';
    }

    private function populateLegend(Worksheet $sheet): void
    {
        $sheet->getColumnDimension('A')->setWidth(23);
        $sheet->getColumnDimension('B')->setWidth(58);
        $sheet->setCellValue('A1', 'Column');
        $sheet->setCellValue('B1', 'What to Fill In');

        $row = 2;
        foreach (self::LEGEND as $column => $description) {
            $sheet->setCellValue("A{$row}", $column);
            $sheet->setCellValue("B{$row}", $description);
            $row++;
        }

        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF1F3B53']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDCE8F2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getStyle('A2:A17')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF243447']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEAF1F7']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getStyle('B2:B17')->applyFromArray([
            'font' => ['color' => ['argb' => 'FF243447']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8FAFC']],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(20.85);
        for ($legendRow = 2; $legendRow <= 17; $legendRow++) {
            $sheet->getRowDimension($legendRow)->setRowHeight(17.55);
        }

        $this->applyReferenceMargins($sheet->getPageMargins());
    }

    private function applyReferenceMargins(PageMargins $margins): void
    {
        $margins
            ->setLeft(0.7)
            ->setRight(0.7)
            ->setTop(0.75)
            ->setBottom(0.75)
            ->setHeader(0.3)
            ->setFooter(0.3);
    }
}
