<?php

namespace App\Console\Commands;

use App\Services\ItAssetWorkbookImporter;
use Illuminate\Console\Command;
use Throwable;

class ImportItAssets extends Command
{
    protected $signature = 'it-assets:import {file} {--replace}';

    protected $description = 'Import an IT asset workbook into the it_assets table';

    public function handle(ItAssetWorkbookImporter $importer): int
    {
        try {
            $count = $importer->import(
                (string) $this->argument('file'),
                (bool) $this->option('replace')
            );
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info("Imported {$count} IT asset ".str('record')->plural($count).'.');

        return self::SUCCESS;
    }
}
