<?php

namespace Database\Factories;

use App\Models\ItAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItAsset>
 */
class ItAssetFactory extends Factory
{
    public function definition(): array
    {
        $purchaseDate = fake()->dateTimeBetween('-5 years', '-1 month');
        $warrantyEnd = (clone $purchaseDate)->modify('+3 years');

        return [
            'source_file' => null,
            'source_sheet' => null,
            'source_row' => null,
            'asset_tag' => strtoupper(fake()->unique()->bothify('IT-####-???')),
            'asset_name' => fake()->randomElement([
                'Dell Latitude 5440',
                'HP ProDesk 400 G7',
                'Brother DCP-T520W',
                'Acer EB192Q Monitor',
            ]),
            'category' => fake()->randomElement(['Laptop', 'Desktop', 'Monitor', 'Printer']),
            'status' => fake()->randomElement(['Assigned', 'Stock', 'In Use']),
            'condition' => fake()->randomElement(['Good', 'Working', 'Good Condition']),
            'branch' => fake()->randomElement(['Makati', 'Davao', 'Mandaue', 'CDO']),
            'assigned_user' => fake()->optional(0.75)->name(),
            'department' => fake()->randomElement(['IT', 'Accounting', 'Finance', 'Service']),
            'location' => fake()->randomElement(['IT Room', 'Admin Office', 'Service Office']),
            'serial_number' => strtoupper(fake()->unique()->bothify('SN-########-???')),
            'brand' => fake()->randomElement(['Dell', 'HP', 'Lenovo', 'Acer', 'Brother']),
            'model' => strtoupper(fake()->bothify('MODEL-####-??')),
            'ip_address' => fake()->optional(0.65)->ipv4(),
            'mac_address' => fake()->optional(0.65)->macAddress(),
            'purchase_date' => $purchaseDate->format('Y-m-d'),
            'warranty_start' => $purchaseDate->format('Y-m-d'),
            'warranty_end' => $warrantyEnd->format('Y-m-d'),
            'supplier' => fake()->randomElement([
                'Dell Partner Cebu',
                'Hardycom Bacolod',
                'MF Computer Bacolod',
            ]),
            'remarks' => fake()->optional(0.3)->sentence(),
            'imported_at' => null,
        ];
    }
}
