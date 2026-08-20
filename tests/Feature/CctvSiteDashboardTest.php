<?php

namespace Tests\Feature;

use App\Models\CctvSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls as XlsWriter;
use Tests\TestCase;

class CctvSiteDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_dashboard_renders_inventory_and_network_summary(): void
    {
        $makati = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Makati Flagship',
            'total_cameras' => 12,
            'online_cameras' => 10,
            'offline_cameras' => 2,
            'recording_issue_cameras' => 1,
            'storage_used_gb' => 100,
            'nvr_hdd_capacity_gb' => 200,
        ]));

        $cebu = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Cebu Distribution Hub',
            'region' => 'Region VII',
            'province' => 'Cebu',
            'business_unit' => 'Distribution',
            'assigned_tech' => 'Carlo Reyes',
            'total_cameras' => 8,
            'online_cameras' => 8,
            'offline_cameras' => 0,
            'recording_issue_cameras' => 0,
            'storage_used_gb' => 150,
            'nvr_hdd_capacity_gb' => 300,
            'nvr_status' => 'operational',
        ]));

        $response = $this->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertViewIs('dashboard.index')
            ->assertSee('action="'.route('dashboard').'#inventory"', false)
            ->assertSeeText('Import XLS')
            ->assertSeeText('Export XLS')
            ->assertSee('id="importModal"', false)
            ->assertSee('action="'.route('cctv-sites.import').'"', false)
            ->assertSee('href="'.route('cctv-sites.import-template').'"', false)
            ->assertSeeText($makati->branch)
            ->assertSeeText($cebu->branch)
            ->assertViewHas('sites', function ($sites) use ($makati, $cebu): bool {
                $ids = collect($sites->items())->pluck('id');

                return $sites->total() === 2
                    && $ids->contains($makati->id)
                    && $ids->contains($cebu->id);
            })
            ->assertViewHas('summary', fn (array $summary): bool => $summary === [
                'branches' => 2,
                'records' => 2,
                'total' => 20,
                'online' => 18,
                'offline' => 2,
                'issues' => 1,
                'availability' => 90.0,
                'storage_capacity_tb' => 0.5,
                'storage_records' => 2,
            ]);

        foreach (['ID', 'Branch / unit', 'Location', 'Assigned tech', 'Cameras', 'NVR status', 'Storage / retention', 'Distribution'] as $heading) {
            $response->assertSeeText($heading);
        }
    }

    public function test_dashboard_overview_aggregates_business_units_per_branch(): void
    {
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Shared Branch',
            'business_unit' => 'Retail',
            'total_cameras' => 12,
            'online_cameras' => 10,
            'offline_cameras' => 2,
            'recording_issue_cameras' => 1,
        ]));
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Shared Branch',
            'business_unit' => 'Service',
            'total_cameras' => 8,
            'online_cameras' => 8,
            'offline_cameras' => 0,
            'recording_issue_cameras' => 0,
            'nvr_status' => 'operational',
        ]));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSeeText('Camera status comparison')
            ->assertSeeText('Cameras by region')
            ->assertSeeText('Branches requiring attention')
            ->assertViewHas('branchOverview', function ($branches): bool {
                $branch = $branches->firstWhere('branch', 'Gateway Shared Branch');

                return $branches->count() === 1
                    && $branch['records'] === 2
                    && $branch['business_units'] === 2
                    && $branch['total'] === 20
                    && $branch['online'] === 18
                    && $branch['offline'] === 2
                    && $branch['issues'] === 1
                    && $branch['availability'] === 90.0;
            });
    }

    public function test_dashboard_combines_search_location_business_unit_and_health_filters(): void
    {
        $matching = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Makati Flagship',
            'region' => 'NCR',
            'business_unit' => 'Retail',
            'offline_cameras' => 1,
            'online_cameras' => 14,
            'recording_issue_cameras' => 1,
        ]));

        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Cebu Region Mismatch',
            'region' => 'Region VII',
            'business_unit' => 'Retail',
        ]));
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Logistics Unit Mismatch',
            'region' => 'NCR',
            'business_unit' => 'Logistics',
        ]));
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Healthy Site',
            'region' => 'NCR',
            'business_unit' => 'Retail',
            'online_cameras' => 16,
            'offline_cameras' => 0,
            'recording_issue_cameras' => 0,
            'nvr_status' => 'operational',
        ]));
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Acme Search Mismatch',
            'region' => 'NCR',
            'business_unit' => 'Retail',
        ]));

        $response = $this->get(route('dashboard', [
            'q' => 'Gateway',
            'region' => 'NCR',
            'business_unit' => 'Retail',
            'health' => 'offline',
        ]));

        $response
            ->assertOk()
            ->assertSeeText($matching->branch)
            ->assertDontSeeText('Gateway Cebu Region Mismatch')
            ->assertDontSeeText('Gateway Logistics Unit Mismatch')
            ->assertDontSeeText('Gateway Healthy Site')
            ->assertDontSeeText('Acme Search Mismatch')
            ->assertViewHas('sites', fn ($sites): bool => $sites->total() === 1
                && $sites->first()->is($matching));
    }

    public function test_dashboard_searches_equipment_fields_and_applies_every_health_filter(): void
    {
        $healthy = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Healthy Workbook Site',
            'online_cameras' => 16,
            'offline_cameras' => 0,
            'recording_issue_cameras' => 0,
            'nvr_status' => 'Good',
        ]));
        $nvrAttention = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Recorder Attention Site',
            'nvr_status' => 'Currently requesting DVR only',
            'nvr_rlp' => 'RLP-SEARCH-7788',
        ]));
        $recording = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Recording Issue Site',
            'online_cameras' => 14,
            'offline_cameras' => 2,
            'recording_issue_cameras' => 2,
            'nvr_status' => 'Good',
        ]));

        $this->get(route('dashboard', ['q' => 'RLP-SEARCH-7788']))
            ->assertOk()
            ->assertSeeText($nvrAttention->branch)
            ->assertDontSeeText($healthy->branch)
            ->assertDontSeeText($recording->branch);

        $this->get(route('dashboard', ['health' => 'healthy']))
            ->assertOk()
            ->assertSeeText($healthy->branch)
            ->assertDontSeeText($nvrAttention->branch)
            ->assertDontSeeText($recording->branch);

        $this->get(route('dashboard', ['health' => 'recording']))
            ->assertOk()
            ->assertSeeText($recording->branch)
            ->assertDontSeeText($healthy->branch);

        $this->get(route('dashboard', ['health' => 'nvr']))
            ->assertOk()
            ->assertSeeText($nvrAttention->branch)
            ->assertDontSeeText($healthy->branch);
    }

    public function test_create_show_and_edit_pages_render_the_expected_record(): void
    {
        $site = CctvSite::factory()->create($this->validPayload());

        $this->get(route('cctv-sites.create'))
            ->assertOk()
            ->assertViewIs('cctv-sites.create')
            ->assertViewHas('cctvSite', fn (CctvSite $formSite): bool => ! $formSite->exists);

        $this->get(route('cctv-sites.show', $site))
            ->assertOk()
            ->assertViewIs('cctv-sites.show')
            ->assertSeeText($site->branch)
            ->assertSeeText($site->assigned_tech)
            ->assertSeeText($site->nvr_model)
            ->assertViewHas('cctvSite', fn (CctvSite $viewSite): bool => $viewSite->is($site));

        $this->get(route('cctv-sites.edit', $site))
            ->assertOk()
            ->assertViewIs('cctv-sites.edit')
            ->assertSee($site->branch)
            ->assertViewHas('cctvSite', fn (CctvSite $formSite): bool => $formSite->is($site));
    }

    public function test_site_can_be_created_with_all_inventory_fields(): void
    {
        $payload = $this->validPayload([
            'branch' => 'Gateway BGC Corporate Center',
            'remarks' => 'Replace camera 12 during the next maintenance window.',
            'distribution_summary' => 'All cameras assigned to the ground and second floors.',
        ]);

        $response = $this->post(route('cctv-sites.store'), $payload);

        $site = CctvSite::query()->where('branch', $payload['branch'])->sole();

        $response
            ->assertRedirect(route('cctv-sites.show', $site))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('CCTV_Inventory', $payload);
    }

    public function test_camera_status_counts_must_equal_the_total_camera_count(): void
    {
        $payload = $this->validPayload([
            'branch' => 'Invalid Camera Allocation',
            'total_cameras' => 16,
            'online_cameras' => 12,
            'offline_cameras' => 1,
            'recording_issue_cameras' => 1,
        ]);

        $this->from(route('cctv-sites.create'))
            ->post(route('cctv-sites.store'), $payload)
            ->assertRedirect(route('cctv-sites.create'))
            ->assertSessionHasErrors([
                'total_cameras' => 'Total cameras must equal the online and offline camera counts combined.',
            ]);

        $this->assertDatabaseMissing('CCTV_Inventory', ['branch' => $payload['branch']]);
    }

    public function test_storage_used_cannot_exceed_nvr_capacity(): void
    {
        $payload = $this->validPayload([
            'branch' => 'Invalid Storage Allocation',
            'storage_used_gb' => 8193,
            'nvr_hdd_capacity_gb' => 8192,
        ]);

        $this->from(route('cctv-sites.create'))
            ->post(route('cctv-sites.store'), $payload)
            ->assertRedirect(route('cctv-sites.create'))
            ->assertSessionHasErrors([
                'storage_used_gb' => 'Storage used cannot be greater than the NVR HDD capacity.',
            ]);

        $this->assertDatabaseMissing('CCTV_Inventory', ['branch' => $payload['branch']]);
    }

    public function test_site_can_be_updated(): void
    {
        $site = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Makati - Old Name',
        ]));
        $payload = $this->validPayload([
            'branch' => 'Gateway Makati Corporate Center',
            'assigned_tech' => 'Maria Santos',
            'total_cameras' => 24,
            'online_cameras' => 22,
            'offline_cameras' => 2,
            'recording_issue_cameras' => 2,
            'nvr_status' => 'maintenance',
            'storage_used_gb' => 6144,
            'nvr_hdd_capacity_gb' => 8192,
            'distribution_status' => 'partial',
        ]);

        $response = $this->put(route('cctv-sites.update', $site), $payload);

        $response
            ->assertRedirect(route('cctv-sites.show', $site))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('CCTV_Inventory', ['id' => $site->id] + $payload);
        $this->assertDatabaseMissing('CCTV_Inventory', [
            'id' => $site->id,
            'branch' => 'Gateway Makati - Old Name',
        ]);
    }

    public function test_site_is_soft_deleted_and_removed_from_the_active_dashboard(): void
    {
        $site = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Retired Branch',
        ]));

        $this->delete(route('cctv-sites.destroy', $site))
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('CCTV_Inventory', ['id' => $site->id]);
        $this->assertNull(CctvSite::query()->find($site->id));
        $this->assertNotNull(CctvSite::withTrashed()->find($site->id));

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('sites', fn ($sites): bool => $sites->total() === 0);
    }

    public function test_xls_export_uses_the_regional_layout_and_respects_dashboard_filters(): void
    {
        $included = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Makati CSV Branch',
            'region' => 'NCR',
            'remarks' => 'Rack checked, cabling secure.',
            'distribution_summary' => 'Showroom: 3 • Workshop: 2',
        ]));
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway NCR Secondary Branch',
            'region' => 'NCR',
            'distribution_summary' => null,
        ]));
        $thirdIncluded = CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway NCR Tertiary Branch',
            'region' => 'NCR',
            'distribution_summary' => null,
        ]));
        CctvSite::factory()->create($this->validPayload([
            'branch' => 'Gateway Cebu Excluded Branch',
            'region' => 'Region VII',
            'province' => 'Cebu',
        ]));

        $response = $this->get(route('cctv-sites.export', ['region' => 'NCR']));

        $response
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel')
            ->assertDownload('gateway-cctv-inventory-'.now()->format('Y-m-d').'.xls');

        $content = $response->streamedContent();
        self::assertStringStartsWith("\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1", $content);

        $temporaryFile = tempnam(sys_get_temp_dir(), 'cctv-xls-');
        self::assertNotFalse($temporaryFile);
        file_put_contents($temporaryFile, $content);

        try {
            $workbook = (new Xls)->load($temporaryFile);
            self::assertSame([
                'Corrected NCR',
                'Corrected Visayas',
                'Corrected Mindanao',
                'Legend',
            ], $workbook->getSheetNames());

            $ncr = $workbook->getSheetByName('Corrected NCR');
            self::assertNotNull($ncr);
            self::assertSame('ID', $ncr->getCell('A1')->getValue());
            self::assertSame($included->id, $ncr->getCell('B1')->getValue());
            self::assertSame('Branch', $ncr->getCell('A2')->getValue());
            self::assertSame($included->branch, $ncr->getCell('B2')->getValue());
            self::assertSame('NVR Model', $ncr->getCell('A15')->getValue());
            self::assertSame($included->nvr_model, $ncr->getCell('B15')->getValue());
            self::assertSame('• Showroom', $ncr->getCell('A18')->getValue());
            self::assertSame(3, $ncr->getCell('B18')->getValue());
            self::assertSame('• Workshop', $ncr->getCell('A19')->getValue());
            self::assertSame(2, $ncr->getCell('B19')->getValue());
            self::assertSame('Remarks', $ncr->getCell('A20')->getValue());
            self::assertSame($included->remarks, $ncr->getCell('B20')->getValue());
            self::assertSame('ID', $ncr->getCell('A22')->getValue());
            self::assertSame('Branch', $ncr->getCell('A23')->getValue());
            self::assertSame($thirdIncluded->branch, $ncr->getCell('B23')->getValue());

            $visayas = $workbook->getSheetByName('Corrected Visayas');
            self::assertNotNull($visayas);
            self::assertNull($visayas->getCell('A1')->getValue());

            $legend = $workbook->getSheetByName('Legend');
            self::assertNotNull($legend);
            self::assertSame('Column', $legend->getCell('A1')->getValue());
            self::assertSame('What to Fill In', $legend->getCell('B1')->getValue());
            $workbook->disconnectWorksheets();
        } finally {
            unlink($temporaryFile);
        }
    }

    public function test_import_template_can_be_downloaded(): void
    {
        $this->get(route('cctv-sites.import-template'))
            ->assertOk()
            ->assertHeader(
                'content-type',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            )
            ->assertDownload('Gateway_CCTV_Monitoring_Template.xlsx');
    }

    public function test_completed_xls_template_can_be_uploaded_from_the_dashboard(): void
    {
        $path = $this->makeXlsImportWorkbook([
            [
                801,
                'Gateway Uploaded XLS Branch',
                'NCR',
                'Metro Manila',
                'Retail',
                'Maria Santos',
                8,
                7,
                1,
                1,
                'Good',
                'Full/Overwrite',
                '30',
                'SecureTech',
                'Hikvision',
                'DS-7608NI',
                'RLP-801',
                '8TB',
                'Complete',
                'Imported from the dashboard modal.',
                'Showroom: 5 • Workshop: 3',
            ],
        ]);

        try {
            $response = $this->post(route('cctv-sites.import'), [
                'import_file' => new UploadedFile(
                    $path,
                    'Gateway_CCTV_Completed.xls',
                    'application/vnd.ms-excel',
                    null,
                    true
                ),
            ]);

            $response
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('success', 'Imported 1 CCTV inventory record.');

            $this->assertDatabaseHas('CCTV_Inventory', [
                'source_file' => 'Gateway_CCTV_Completed.xls',
                'source_sheet' => 'Sheet1',
                'source_row' => 2,
                'source_id' => 801,
                'branch' => 'Gateway Uploaded XLS Branch',
                'total_cameras' => 8,
                'online_cameras' => 7,
                'offline_cameras' => 1,
                'nvr_hdd_capacity_gb' => 8192,
            ]);
        } finally {
            unlink($path);
        }
    }

    public function test_completed_xlsx_template_can_be_uploaded_from_a_temporary_file(): void
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'cctv-xlsx-upload-');
        self::assertNotFalse($temporaryPath);
        copy(base_path('Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx'), $temporaryPath);

        try {
            $this->post(route('cctv-sites.import'), [
                'import_file' => new UploadedFile(
                    $temporaryPath,
                    'Gateway_CCTV_Completed.xlsx',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    null,
                    true
                ),
            ])
                ->assertRedirect(route('dashboard'))
                ->assertSessionHas('success', 'Imported 70 CCTV inventory records.');

            $this->assertDatabaseCount('CCTV_Inventory', 70);
        } finally {
            unlink($temporaryPath);
        }
    }

    public function test_invalid_import_is_rejected_without_saving_partial_rows(): void
    {
        $path = $this->makeXlsImportWorkbook([
            [1, 'Valid Row', 'NCR', 'Metro Manila', 'Retail', null, 4, 4, 0, 0],
            [2, 'Invalid Row', 'NCR', 'Metro Manila', 'Retail', null, 5, 3, 1, 0],
        ]);

        try {
            $this->post(route('cctv-sites.import'), [
                'import_file' => new UploadedFile(
                    $path,
                    'Gateway_CCTV_Invalid.xls',
                    'application/vnd.ms-excel',
                    null,
                    true
                ),
            ])
                ->assertRedirect(route('dashboard'))
                ->assertSessionHasErrors([
                    'import_file' => 'Sheet1 row 3: Total Camera must equal Online plus Offline.',
                ]);

            $this->assertDatabaseCount('CCTV_Inventory', 0);
        } finally {
            unlink($path);
        }
    }

    public function test_gateway_workbook_import_populates_the_live_inventory_table(): void
    {
        $this->artisan('cctv:import-inventory', [
            'file' => base_path('Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx'),
            '--replace' => true,
        ])
            ->expectsOutput('Imported 70 CCTV inventory records.')
            ->assertSuccessful();

        $this->assertDatabaseCount('CCTV_Inventory', 70);
        $this->assertDatabaseHas('CCTV_Inventory', [
            'source_sheet' => 'Visayas',
            'source_row' => 2,
            'branch' => 'Mandaue',
            'business_unit' => 'HYUNDAI',
            'total_cameras' => 15,
            'online_cameras' => 14,
            'offline_cameras' => 1,
            'storage_status' => 'Full/Overwrite',
            'recording_days' => '30',
            'nvr_hdd_capacity' => '12TB',
            'nvr_hdd_capacity_gb' => 12288,
        ]);

        $this->assertDatabaseHas('CCTV_Inventory', [
            'source_sheet' => 'Minadanao',
            'source_row' => 3,
            'branch' => 'MATINA',
            'region' => 'Region XI',
            'province' => 'Davao del sur',
            'business_unit' => 'Hyundai',
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_replace([
            'branch' => 'Gateway Makati Flagship',
            'region' => 'NCR',
            'province' => 'Metro Manila',
            'business_unit' => 'Retail',
            'assigned_tech' => 'Juan Dela Cruz',
            'total_cameras' => 16,
            'online_cameras' => 15,
            'offline_cameras' => 1,
            'recording_issue_cameras' => 1,
            'nvr_status' => 'degraded',
            'storage_used_gb' => 4096,
            'vendor' => 'SecureTech Solutions',
            'nvr_brand' => 'Hikvision',
            'nvr_model' => 'DS-7616NI-K2',
            'nvr_hdd_capacity_gb' => 8192,
            'distribution_status' => 'complete',
            'remarks' => 'Quarterly inspection completed.',
            'distribution_summary' => 'Sixteen cameras distributed across two floors.',
        ], $overrides);
    }

    /** @param list<list<mixed>> $rows */
    private function makeXlsImportWorkbook(array $rows): string
    {
        $headers = [
            'ID',
            'Branch',
            'Region',
            'Province',
            'Business Unit',
            'Assigned Tech',
            'Total Camera',
            'Online',
            'Offline',
            'Recording Issue',
            'NVR Status',
            'Storage Used',
            'Recording Days',
            'Vendor',
            'NVR Brand',
            'NVR Model',
            'NVR RLP',
            'HDD Capacity',
            'Distribution',
            'Remarks',
            'Distribution Summary',
        ];

        $workbook = new Spreadsheet;
        $worksheet = $workbook->getActiveSheet();
        $worksheet->setTitle('Sheet1');
        $worksheet->fromArray($headers, null, 'A1');

        foreach ($rows as $index => $row) {
            $worksheet->fromArray(array_pad($row, count($headers), null), null, 'A'.($index + 2));
        }

        $guide = $workbook->createSheet();
        $guide->setTitle('Sheet2');
        $guide->fromArray([
            ['Column', 'What to Fill In'],
            ['Branch', 'Branch name.'],
        ], null, 'A1');

        $path = tempnam(sys_get_temp_dir(), 'cctv-import-test-');
        self::assertNotFalse($path);

        (new XlsWriter($workbook))->save($path);
        $workbook->disconnectWorksheets();

        return $path;
    }
}
