<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('CCTV_Inventory', function (Blueprint $table): void {
            $table->id();
            $table->string('source_file')->nullable();
            $table->string('source_sheet', 100)->nullable();
            $table->unsignedInteger('source_row')->nullable();
            $table->unsignedInteger('source_id')->nullable();

            $table->string('branch', 150)->index();
            $table->string('region', 100)->nullable()->index();
            $table->string('province', 100)->nullable()->index();
            $table->string('business_unit', 120)->nullable()->index();
            $table->string('assigned_tech', 150)->nullable();

            $table->unsignedSmallInteger('total_cameras')->default(0);
            $table->unsignedSmallInteger('online_cameras')->default(0);
            $table->unsignedSmallInteger('offline_cameras')->default(0);
            $table->unsignedSmallInteger('recording_issue_cameras')->default(0);

            $table->string('nvr_status', 100)->nullable()->index();
            $table->string('storage_status', 100)->nullable();
            $table->decimal('storage_used_gb', 12, 2)->nullable();
            $table->string('recording_days', 100)->nullable();
            $table->string('vendor', 150)->nullable();
            $table->string('nvr_brand', 120)->nullable();
            $table->string('nvr_model', 150)->nullable();
            $table->string('nvr_rlp', 150)->nullable();
            $table->string('nvr_hdd_capacity', 100)->nullable();
            $table->decimal('nvr_hdd_capacity_gb', 12, 2)->nullable();

            $table->string('distribution_status', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->text('distribution_summary')->nullable();
            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['source_sheet', 'source_row']);
            $table->index(['region', 'province']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CCTV_Inventory');
    }
};
