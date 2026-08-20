<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cctv_sites', function (Blueprint $table): void {
            $table->id();
            $table->string('branch', 150)->index();
            $table->string('region', 100)->index();
            $table->string('province', 100)->index();
            $table->string('business_unit', 120)->nullable();
            $table->string('assigned_tech', 150)->nullable();

            $table->unsignedSmallInteger('total_cameras')->default(0);
            $table->unsignedSmallInteger('online_cameras')->default(0);
            $table->unsignedSmallInteger('offline_cameras')->default(0);
            $table->unsignedSmallInteger('recording_issue_cameras')->default(0);

            $table->string('nvr_status', 30)->default('Unknown')->index();
            $table->decimal('storage_used_gb', 12, 2)->nullable();
            $table->string('vendor', 120)->nullable();
            $table->string('nvr_brand', 120)->nullable();
            $table->string('nvr_model', 120)->nullable();
            $table->decimal('nvr_hdd_capacity_gb', 12, 2)->nullable();

            $table->string('distribution_status', 30)->default('pending');
            $table->text('remarks')->nullable();
            $table->text('distribution_summary')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['region', 'province']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cctv_sites');
    }
};
