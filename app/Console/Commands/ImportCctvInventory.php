<?php

namespace App\Console\Commands;

use App\Services\CctvInventoryWorkbookImporter;
use Illuminate\Console\Command;
use Throwable;

class ImportCctvInventory extends Command
{
    protected $signature = 'cctv:import-inventory
        {file=Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx : Path to the CCTV inventory workbook}
        {--replace : Replace all existing CCTV inventory rows before importing}';

    protected $description = 'Import the Gateway branch CCTV workbook into the CCTV_Inventory table';

    public function handle(CctvInventoryWorkbookImporter $importer): int
    {
        try {
            $count = $importer->import((string) $this->argument('file'), (bool) $this->option('replace'));
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Imported {$count} CCTV inventory records.");

        return self::SUCCESS;
    }
}
