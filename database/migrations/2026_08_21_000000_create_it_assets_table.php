<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('it_assets', function (Blueprint $table): void {
            $table->id();
            $table->string('source_file')->nullable();
            $table->string('source_sheet', 100)->nullable();
            $table->unsignedInteger('source_row')->nullable();

            $table->string('asset_tag', 150)->nullable()->index();
            $table->string('asset_name')->nullable();
            $table->string('category', 100)->index();
            $table->string('status', 100)->nullable()->index();
            $table->string('condition', 150)->nullable()->index();
            $table->string('branch', 150)->nullable()->index();
            $table->string('assigned_user', 150)->nullable();
            $table->string('department', 150)->nullable()->index();
            $table->string('location', 190)->nullable();
            $table->string('serial_number', 190)->nullable()->index();
            $table->string('brand', 120)->nullable()->index();
            $table->string('model', 190)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('mac_address', 50)->nullable();
            $table->string('purchase_date', 50)->nullable();
            $table->string('warranty_start', 50)->nullable();
            $table->string('warranty_end', 50)->nullable();
            $table->string('supplier', 190)->nullable();
            $table->text('remarks')->nullable();

            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['source_file', 'source_sheet', 'source_row'],
                'it_assets_source_location_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('it_assets');
    }
};
