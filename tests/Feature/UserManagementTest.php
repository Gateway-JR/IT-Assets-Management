<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_administrators_can_access_user_management(): void
    {
        $standardUser = User::factory()->create();

        $this->get(route('users.index'))->assertRedirect(route('login'));

        $this->actingAs($standardUser)
            ->get(route('users.index'))
            ->assertForbidden();

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('href="'.route('users.index').'"', false);

        $administrator = User::factory()->admin()->create();

        $this->actingAs($administrator)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('href="'.route('users.index').'"', false)
            ->assertSeeText('Website users');
    }

    public function test_administrator_can_create_read_update_and_delete_a_login_account(): void
    {
        $administrator = User::factory()->admin()->create();
        $this->actingAs($administrator);

        $this->post(route('users.store'), [
            'name' => 'CCTV Operator',
            'email' => 'OPERATOR@EXAMPLE.COM',
            'password' => 'initial-password',
            'password_confirmation' => 'initial-password',
            'is_admin' => '0',
        ])->assertSessionHasNoErrors();

        $user = User::query()->where('email', 'operator@example.com')->sole();

        $this->assertTrue(Hash::check('initial-password', $user->password));

        $this->get(route('users.show', $user))
            ->assertOk()
            ->assertSeeText('CCTV Operator')
            ->assertSeeText('operator@example.com')
            ->assertSeeText('Standard user');

        $oldPassword = $user->password;

        $this->put(route('users.update', $user), [
            'name' => 'Senior CCTV Operator',
            'email' => 'senior.operator@example.com',
            'password' => '',
            'password_confirmation' => '',
            'is_admin' => '1',
        ])
            ->assertRedirect(route('users.show', $user))
            ->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('Senior CCTV Operator', $user->name);
        $this->assertSame('senior.operator@example.com', $user->email);
        $this->assertTrue($user->is_admin);
        $this->assertSame($oldPassword, $user->password);

        $this->delete(route('users.destroy', $user))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_validation_protects_unique_email_and_password_confirmation(): void
    {
        $administrator = User::factory()->admin()->create();
        $existing = User::factory()->create(['email' => 'existing@example.com']);

        $this->actingAs($administrator)
            ->from(route('users.create'))
            ->post(route('users.store'), [
                'name' => 'Duplicate Account',
                'email' => $existing->email,
                'password' => 'long-enough-password',
                'password_confirmation' => 'different-password',
                'is_admin' => '0',
            ])
            ->assertRedirect(route('users.create'))
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_administrator_cannot_delete_or_demote_their_current_account(): void
    {
        $administrator = User::factory()->admin()->create();

        $this->actingAs($administrator)
            ->delete(route('users.destroy', $administrator))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $administrator->id]);

        $this->actingAs($administrator)
            ->from(route('users.edit', $administrator))
            ->put(route('users.update', $administrator), [
                'name' => $administrator->name,
                'email' => $administrator->email,
                'password' => '',
                'password_confirmation' => '',
                'is_admin' => '0',
            ])
            ->assertRedirect(route('users.edit', $administrator))
            ->assertSessionHasErrors(['is_admin']);

        $this->assertTrue($administrator->fresh()->is_admin);
    }
}
