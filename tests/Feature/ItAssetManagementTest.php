<?php

namespace Tests\Feature;

use App\Models\ItAsset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItAssetManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_the_it_asset_inventory(): void
    {
        $this->get(route('it-assets.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_the_asset_dashboard_and_sidebar_tab(): void
    {
        $this->actingAs(User::factory()->create());

        ItAsset::factory()->create([
            'asset_name' => 'Dell Latitude 5440',
            'category' => 'Laptop',
            'status' => 'Assigned',
            'condition' => 'Good',
            'branch' => 'Makati',
            'ip_address' => '192.168.1.101',
        ]);
        ItAsset::factory()->create([
            'asset_name' => 'Acer Repair Monitor',
            'category' => 'Monitor',
            'status' => 'Stock',
            'condition' => 'Damage',
            'branch' => 'Davao',
            'ip_address' => null,
            'mac_address' => null,
        ]);

        $this->get(route('it-assets.index'))
            ->assertOk()
            ->assertViewIs('it-assets.index')
            ->assertSeeText('IT asset inventory')
            ->assertSeeText('IT assets')
            ->assertSeeText('Dell Latitude 5440')
            ->assertSeeText('Acer Repair Monitor')
            ->assertViewHas('summary', fn (array $summary): bool => $summary === [
                'total' => 2,
                'assigned' => 1,
                'stock' => 1,
                'attention' => 1,
                'networked' => 1,
                'branches' => 2,
            ]);
    }

    public function test_search_and_filters_are_applied_to_assets_and_summary(): void
    {
        $this->actingAs(User::factory()->create());

        $matching = ItAsset::factory()->create([
            'asset_name' => 'Finance Dell Laptop',
            'category' => 'Laptop',
            'status' => 'Assigned',
            'condition' => 'Damage Casing',
            'branch' => 'Makati',
            'serial_number' => 'SEARCH-SERIAL-100',
        ]);
        ItAsset::factory()->create([
            'asset_name' => 'Healthy Cebu Monitor',
            'category' => 'Monitor',
            'status' => 'Stock',
            'condition' => 'Good',
            'branch' => 'Cebu',
            'serial_number' => 'OTHER-200',
        ]);

        $this->get(route('it-assets.index', [
            'q' => 'SEARCH-SERIAL',
            'category' => 'Laptop',
            'branch' => 'Makati',
            'state' => 'attention',
        ]))
            ->assertOk()
            ->assertSeeText($matching->asset_name)
            ->assertDontSeeText('Healthy Cebu Monitor')
            ->assertViewHas('assets', fn ($assets): bool => $assets->total() === 1
                && $assets->first()->is($matching))
            ->assertViewHas('summary', fn (array $summary): bool => $summary['total'] === 1
                && $summary['attention'] === 1);
    }

    public function test_asset_can_be_created_viewed_and_updated_with_all_workbook_fields(): void
    {
        $this->actingAs(User::factory()->create());

        $payload = $this->validPayload();

        $response = $this->post(route('it-assets.store'), $payload);
        $asset = ItAsset::query()->where('asset_tag', 'GM-LAP-900')->sole();

        $response
            ->assertRedirect(route('it-assets.show', $asset))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('it_assets', $payload);

        $this->get(route('it-assets.show', $asset))
            ->assertOk()
            ->assertViewIs('it-assets.show')
            ->assertSeeText('Gateway Test Laptop')
            ->assertSeeText('SN-TEST-900')
            ->assertSeeText('AA:BB:CC:DD:EE:90');

        $updated = array_replace($payload, [
            'asset_name' => 'Gateway Test Laptop - Updated',
            'status' => 'For Repair',
            'condition' => 'Damage screen',
            'assigned_user' => null,
        ]);

        $this->put(route('it-assets.update', $asset), $updated)
            ->assertRedirect(route('it-assets.show', $asset))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('it_assets', [
            'id' => $asset->id,
            'asset_name' => 'Gateway Test Laptop - Updated',
            'status' => 'For Repair',
            'condition' => 'Damage screen',
            'assigned_user' => null,
        ]);
    }

    public function test_category_is_required_and_asset_is_soft_deleted(): void
    {
        $this->actingAs(User::factory()->create());

        $this->from(route('it-assets.create'))
            ->post(route('it-assets.store'), ['asset_name' => 'Missing Category'])
            ->assertRedirect(route('it-assets.create'))
            ->assertSessionHasErrors('category');

        $asset = ItAsset::factory()->create();

        $this->delete(route('it-assets.destroy', $asset))
            ->assertRedirect(route('it-assets.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted('it_assets', ['id' => $asset->id]);
        $this->assertNull(ItAsset::query()->find($asset->id));
        $this->assertNotNull(ItAsset::withTrashed()->find($asset->id));
    }

    /** @return array<string, string|null> */
    private function validPayload(): array
    {
        return [
            'asset_tag' => 'GM-LAP-900',
            'asset_name' => 'Gateway Test Laptop',
            'category' => 'Laptop',
            'status' => 'Assigned',
            'condition' => 'Good',
            'branch' => 'Makati',
            'assigned_user' => 'Juan Dela Cruz',
            'department' => 'IT',
            'location' => 'IT Department Office',
            'serial_number' => 'SN-TEST-900',
            'brand' => 'Dell',
            'model' => 'Latitude 5440',
            'ip_address' => '192.168.1.90',
            'mac_address' => 'AA:BB:CC:DD:EE:90',
            'purchase_date' => '2026-01-15',
            'warranty_start' => '2026-01-15',
            'warranty_end' => '2029-01-14',
            'supplier' => 'Gateway Hardware Partner',
            'remarks' => 'Created by the IT asset management feature test.',
        ];
    }
}
