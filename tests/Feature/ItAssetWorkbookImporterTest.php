<?php

namespace Tests\Feature;

use App\Models\ItAsset;
use App\Services\ItAssetWorkbookImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Tests\TestCase;

class ItAssetWorkbookImporterTest extends TestCase
{
    use RefreshDatabase;

    /** @var list<string> */
    private array $temporaryFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->temporaryFiles as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_actual_workbook_imports_181_assets_with_mixed_dates_and_is_idempotent(): void
    {
        $path = base_path('Assets-List-Database.xlsx');
        $importer = app(ItAssetWorkbookImporter::class);

        self::assertFileExists($path);
        self::assertSame(181, $importer->import($path));
        $this->assertDatabaseCount('it_assets', 181);

        $this->assertDatabaseHas('it_assets', [
            'source_file' => 'Assets-List-Database.xlsx',
            'source_sheet' => 'Davao',
            'source_row' => 2,
            'category' => 'Laptop',
            'status' => 'stock',
            'condition' => 'damage',
            'branch' => 'Davao',
            'location' => 'IT Room',
            'brand' => 'HP',
            'model' => 'HP Pavilion G series',
            'purchase_date' => '2021',
            'supplier' => 'Hardycom Bacolod',
        ]);
        $this->assertDatabaseHas('it_assets', [
            'source_sheet' => 'Davao',
            'source_row' => 9,
            'serial_number' => '30135037011',
        ]);
        $this->assertDatabaseHas('it_assets', [
            'source_sheet' => 'Davao',
            'source_row' => 29,
            'asset_name' => 'DELL INSPERON 3567',
            'serial_number' => '1FHV2F2',
            'purchase_date' => '2017-05-27',
            'warranty_start' => '2017-05-27',
            'warranty_end' => '2020-05-27',
        ]);

        $asset = ItAsset::query()
            ->where('source_file', 'Assets-List-Database.xlsx')
            ->where('source_sheet', 'Davao')
            ->where('source_row', 29)
            ->sole();
        $originalId = $asset->id;
        $asset->delete();

        self::assertSame(181, $importer->import($path));
        $restored = ItAsset::query()->findOrFail($originalId);
        self::assertNull($restored->deleted_at);
        self::assertSame('2017-05-27', $restored->purchase_date);

        self::assertSame(181, $importer->import($path));
        $this->assertDatabaseCount('it_assets', 181);
        self::assertSame($originalId, ItAsset::query()
            ->where('source_file', 'Assets-List-Database.xlsx')
            ->where('source_sheet', 'Davao')
            ->where('source_row', 29)
            ->value('id'));
    }

    public function test_import_command_imports_the_actual_workbook(): void
    {
        $this->artisan('it-assets:import', [
            'file' => base_path('Assets-List-Database.xlsx'),
        ])
            ->expectsOutput('Imported 181 IT asset records.')
            ->assertSuccessful();

        $this->assertDatabaseCount('it_assets', 181);
    }

    public function test_malformed_case_sensitive_header_is_rejected(): void
    {
        $headers = $this->headers();
        $headers[2] = 'Category';
        $path = $this->writeXls([$this->validRow()], $headers);

        try {
            app(ItAssetWorkbookImporter::class)->import($path);
            self::fail('The malformed workbook should have been rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('missing required IT asset header: category', $exception->getMessage());
            self::assertStringContainsString('case-sensitive', $exception->getMessage());
        }

        $this->assertDatabaseCount('it_assets', 0);
    }

    public function test_all_rows_are_validated_before_replace_or_insert_occurs(): void
    {
        $existing = ItAsset::factory()->create(['asset_tag' => 'KEEP-ME']);
        $invalidRow = $this->validRow();
        $invalidRow[1] = 'Missing Category Asset';
        $invalidRow[2] = '';
        $path = $this->writeXls([$this->validRow(), $invalidRow]);

        try {
            app(ItAssetWorkbookImporter::class)->import($path, replace: true);
            self::fail('The invalid workbook should have been rejected.');
        } catch (InvalidArgumentException $exception) {
            self::assertStringContainsString('Import row 3: category is required.', $exception->getMessage());
        }

        $this->assertDatabaseCount('it_assets', 1);
        $this->assertDatabaseHas('it_assets', ['id' => $existing->id, 'asset_tag' => 'KEEP-ME']);
    }

    public function test_model_accessors_supply_a_display_name_and_attention_state(): void
    {
        $asset = ItAsset::factory()->make([
            'asset_name' => null,
            'asset_tag' => 'GM-LAP-001',
            'status' => 'Stock',
            'condition' => 'Minor Issue - keyboard',
        ]);

        self::assertSame('GM-LAP-001', $asset->display_name);
        self::assertTrue($asset->requires_attention);

        $asset->condition = 'Good';
        $asset->status = 'For Repair';
        self::assertTrue($asset->requires_attention);

        $asset->status = 'Assigned';
        self::assertFalse($asset->requires_attention);
    }

    /** @return list<string> */
    private function headers(): array
    {
        return [
            'assetTag',
            'assetName',
            'category',
            'status',
            'condition',
            'branch',
            'assignedUser',
            'department',
            'location',
            'serialNumber',
            'brand',
            'model',
            'ipAddress',
            'macAddress',
            'purchaseDate',
            'warrantyStart',
            'warrantyEnd',
            'supplier',
            'remarks',
        ];
    }

    /** @return list<string> */
    private function validRow(): array
    {
        return [
            'GM-LAP-TEST',
            'Dell Latitude Test',
            'Laptop',
            'Assigned',
            'Good',
            'Makati',
            'Test User',
            'IT',
            'IT Office',
            'SN-TEST-001',
            'Dell',
            'Latitude 5440',
            '192.168.1.50',
            'AA:BB:CC:DD:EE:50',
            '42882',
            '42882',
            '43978',
            'Test Supplier',
            'Fixture asset',
        ];
    }

    /**
     * @param  list<list<string>>  $rows
     * @param  list<string>|null  $headers
     */
    private function writeXls(array $rows, ?array $headers = null): string
    {
        $temporaryBase = tempnam(sys_get_temp_dir(), 'it-assets-test-');
        self::assertNotFalse($temporaryBase);
        unlink($temporaryBase);
        $path = $temporaryBase.'.xls';
        $this->temporaryFiles[] = $path;

        $workbook = new Spreadsheet;
        $worksheet = $workbook->getActiveSheet();
        $worksheet->setTitle('Import');
        $worksheet->fromArray($headers ?? $this->headers(), null, 'A1');

        foreach ($rows as $offset => $row) {
            $worksheet->fromArray($row, null, 'A'.($offset + 2));
        }

        (new Xls($workbook))->save($path);
        $workbook->disconnectWorksheets();

        return $path;
    }
}
