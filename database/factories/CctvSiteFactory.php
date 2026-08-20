<?php

namespace Database\Factories;

use App\Models\CctvSite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CctvSite>
 */
class CctvSiteFactory extends Factory
{
    public function definition(): array
    {
        $total = fake()->numberBetween(8, 48);
        $offline = fake()->numberBetween(0, min(3, $total));
        $issues = fake()->numberBetween(0, min(2, $total - $offline));
        $capacity = fake()->randomElement([2048, 4096, 8192, 12288]);

        return [
            'branch' => fake()->unique()->company().' Branch',
            'region' => fake()->randomElement(['NCR', 'Region III', 'Region IV-A', 'Region VII']),
            'province' => fake()->randomElement(['Metro Manila', 'Bulacan', 'Cavite', 'Cebu']),
            'business_unit' => fake()->randomElement(['Retail', 'Distribution', 'Logistics', 'Corporate']),
            'assigned_tech' => fake()->name(),
            'total_cameras' => $total,
            'online_cameras' => $total - $offline - $issues,
            'offline_cameras' => $offline,
            'recording_issue_cameras' => $issues,
            'nvr_status' => $offline > 0 ? 'degraded' : 'operational',
            'storage_used_gb' => fake()->numberBetween((int) ($capacity * 0.25), (int) ($capacity * 0.9)),
            'vendor' => fake()->randomElement(['SecureTech Solutions', 'Vision Systems PH', 'Prime Surveillance']),
            'nvr_brand' => fake()->randomElement(['Hikvision', 'Dahua', 'Uniview']),
            'nvr_model' => fake()->bothify('NVR-####-##CH'),
            'nvr_hdd_capacity_gb' => $capacity,
            'distribution_status' => fake()->randomElement(['complete', 'partial', 'pending']),
            'remarks' => fake()->optional()->sentence(),
            'distribution_summary' => fake()->sentence(),
        ];
    }
}
