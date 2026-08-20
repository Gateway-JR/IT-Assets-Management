<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), function (Builder $query) use ($request): void {
                $term = '%'.trim((string) $request->input('q')).'%';

                $query->where(function (Builder $query) use ($term): void {
                    $query->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term);
                });
            })
            ->when($request->input('role') === 'admin', fn (Builder $query) => $query->where('is_admin', true))
            ->when($request->input('role') === 'user', fn (Builder $query) => $query->where('is_admin', false))
            ->orderByDesc('is_admin')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'summary' => [
                'total' => User::query()->count(),
                'admins' => User::query()->where('is_admin', true)->count(),
                'standard' => User::query()->where('is_admin', false)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('users.create', ['user' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = User::create($this->validated($request));

        return redirect()->route('users.show', $user)
            ->with('success', 'User account created successfully.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $attributes = $this->validated($request, $user);

        if ($request->user()->is($user) && ! $attributes['is_admin']) {
            throw ValidationException::withMessages([
                'is_admin' => 'You cannot remove administrator access from your own account.',
            ]);
        }

        if ($user->is_admin && ! $attributes['is_admin'] && User::query()->where('is_admin', true)->count() === 1) {
            throw ValidationException::withMessages([
                'is_admin' => 'At least one administrator account is required.',
            ]);
        }

        if (blank($attributes['password'] ?? null)) {
            unset($attributes['password']);
        }

        $user->update($attributes);

        return redirect()->route('users.show', $user)
            ->with('success', 'User account updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete the account you are currently using.');
        }

        if ($user->is_admin && User::query()->where('is_admin', true)->count() === 1) {
            return redirect()->route('users.index')
                ->with('error', 'The final administrator account cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User account deleted successfully.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request, ?User $user = null): array
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user),
            ],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'is_admin' => ['required', 'boolean'],
        ]);
    }
}
