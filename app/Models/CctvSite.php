<?php

namespace App\Models;

use Database\Factories\CctvSiteFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CctvSite extends Model
{
    /** @use HasFactory<CctvSiteFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'CCTV_Inventory';

    protected $fillable = [
        'source_file',
        'source_sheet',
        'source_row',
        'source_id',
        'branch',
        'region',
        'province',
        'business_unit',
        'assigned_tech',
        'total_cameras',
        'online_cameras',
        'offline_cameras',
        'recording_issue_cameras',
        'nvr_status',
        'storage_status',
        'storage_used_gb',
        'recording_days',
        'vendor',
        'nvr_brand',
        'nvr_model',
        'nvr_rlp',
        'nvr_hdd_capacity',
        'nvr_hdd_capacity_gb',
        'distribution_status',
        'remarks',
        'distribution_summary',
        'imported_at',
        'deleted_at',
    ];

    protected function casts(): array
    {
        return [
            'total_cameras' => 'integer',
            'online_cameras' => 'integer',
            'offline_cameras' => 'integer',
            'recording_issue_cameras' => 'integer',
            'storage_used_gb' => 'float',
            'nvr_hdd_capacity_gb' => 'float',
            'imported_at' => 'datetime',
        ];
    }

    public function getStoragePercentageAttribute(): float
    {
        if (! $this->storage_used_gb || ! $this->nvr_hdd_capacity_gb) {
            return 0;
        }

        return min(100, round(($this->storage_used_gb / $this->nvr_hdd_capacity_gb) * 100, 1));
    }

    public function getRequiresAttentionAttribute(): bool
    {
        return $this->offline_cameras > 0
            || $this->recording_issue_cameras > 0
            || ($this->nvr_status !== null && ! $this->nvr_is_healthy)
            || ($this->storage_used_gb !== null && $this->storage_percentage >= 85);
    }

    public function getNvrIsHealthyAttribute(): bool
    {
        if ($this->nvr_status === null) {
            return false;
        }

        $status = strtolower(trim($this->nvr_status));

        return $status === 'operational' || str_contains($status, 'good');
    }

    public function getNvrStatusLabelAttribute(): string
    {
        return $this->nvr_status ?: 'Not reported';
    }

    public function getStorageCapacityLabelAttribute(): string
    {
        if ($this->nvr_hdd_capacity) {
            return $this->nvr_hdd_capacity;
        }

        if ($this->nvr_hdd_capacity_gb) {
            return number_format($this->nvr_hdd_capacity_gb).' GB';
        }

        return 'Not reported';
    }
}
