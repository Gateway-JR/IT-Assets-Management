<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\CctvInventoryWorkbookImporter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => 'password', 'is_admin' => true]
        );

        if (! $user->is_admin && User::query()->where('is_admin', true)->doesntExist()) {
            $user->update(['is_admin' => true]);
        }

        $workbook = base_path('Gateway_BranchesGETMS_CCTV_Monitoring_Export.xlsx');

        if (is_file($workbook)) {
            app(CctvInventoryWorkbookImporter::class)->import($workbook, replace: true);
        }
    }
}
