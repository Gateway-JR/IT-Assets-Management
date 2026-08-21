<?php

namespace App\Models;

use Database\Factories\ItAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItAsset extends Model
{
    /** @use HasFactory<ItAssetFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'source_file',
        'source_sheet',
        'source_row',
        'asset_tag',
        'asset_name',
        'category',
        'status',
        'condition',
        'branch',
        'assigned_user',
        'department',
        'location',
        'serial_number',
        'brand',
        'model',
        'ip_address',
        'mac_address',
        'purchase_date',
        'warranty_start',
        'warranty_end',
        'supplier',
        'remarks',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'source_row' => 'integer',
            'imported_at' => 'datetime',
        ];
    }

    public function getDisplayNameAttribute(): string
    {
        foreach ([$this->asset_name, $this->asset_tag, $this->model, $this->category] as $name) {
            if (filled($name)) {
                return trim((string) $name);
            }
        }

        return $this->exists ? 'IT Asset #'.$this->getKey() : 'IT Asset';
    }

    public function getRequiresAttentionAttribute(): bool
    {
        $condition = strtolower(trim((string) $this->condition));
        $status = strtolower(trim((string) $this->status));

        foreach (['damage', 'not working', 'minor issue', 'repair'] as $indicator) {
            if (str_contains($condition, $indicator)) {
                return true;
            }
        }

        return str_contains($status, 'repair');
    }
}
