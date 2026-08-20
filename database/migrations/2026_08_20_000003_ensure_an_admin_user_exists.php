<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'is_admin')) {
            return;
        }

        if (DB::table('users')->where('is_admin', true)->doesntExist()) {
            $firstUserId = DB::table('users')->orderBy('id')->value('id');

            if ($firstUserId !== null) {
                DB::table('users')->where('id', $firstUserId)->update(['is_admin' => true]);
            }
        }
    }

    public function down(): void
    {
        // Do not revoke administrator access during a rollback.
    }
};
