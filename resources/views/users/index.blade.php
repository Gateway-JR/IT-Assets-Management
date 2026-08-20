@extends('layouts.dashboard')

@section('title', 'Users')
@section('topbar-title', 'User Management')

@section('styles')
    @include('users._styles')
@endsection

@section('content')
    <div class="page-heading">
        <div>
            <p class="page-eyebrow">Administration</p>
            <h1 class="page-title">Users</h1>
            <p class="page-description">Manage the accounts and login credentials that can access the CCTV inventory website.</p>
        </div>
        <div class="button-row">
            <a class="button button-primary" href="{{ route('users.create') }}">Add user</a>
        </div>
    </div>

    <section class="user-metrics" aria-label="User account summary">
        <article class="user-metric">
            <p class="user-metric-label">Total accounts</p>
            <strong class="user-metric-value">{{ number_format($summary['total']) }}</strong>
        </article>
        <article class="user-metric admins">
            <p class="user-metric-label">Administrators</p>
            <strong class="user-metric-value">{{ number_format($summary['admins']) }}</strong>
        </article>
        <article class="user-metric standard">
            <p class="user-metric-label">Standard users</p>
            <strong class="user-metric-value">{{ number_format($summary['standard']) }}</strong>
        </article>
    </section>

    <section class="panel users-panel" aria-labelledby="users-title">
        <div class="users-panel-header">
            <div>
                <p class="panel-kicker">Account registry</p>
                <h2 class="panel-title" id="users-title">Website users</h2>
                <p class="panel-copy">Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} matching accounts</p>
            </div>
        </div>

        <form class="user-filters" method="GET" action="{{ route('users.index') }}" role="search">
            <div>
                <label class="field-label" for="q">Search users</label>
                <input class="control" id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Search name or email...">
            </div>
            <div>
                <label class="field-label" for="role">Account role</label>
                <select class="control" id="role" name="role">
                    <option value="">All roles</option>
                    <option value="admin" @selected(request('role') === 'admin')>Administrators</option>
                    <option value="user" @selected(request('role') === 'user')>Standard users</option>
                </select>
            </div>
            <div class="filter-actions">
                <button class="button button-primary" type="submit">Apply</button>
                <a class="button" href="{{ route('users.index') }}">Reset</a>
            </div>
        </form>

        @if ($users->isEmpty())
            <div class="empty-users">
                <h3>No users found</h3>
                <p>{{ request()->hasAny(['q', 'role']) ? 'Try changing or clearing the filters.' : 'Add the first user account to get started.' }}</p>
                <a class="button button-primary" href="{{ route('users.create') }}">Add user</a>
            </div>
        @else
            <div class="users-table-wrap">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Last updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="user-identity">
                                        <span class="user-avatar" aria-hidden="true">{{ str($user->name)->explode(' ')->filter()->take(2)->map(fn ($part) => str($part)->substr(0, 1)->upper())->join('') }}</span>
                                        <span>
                                            <span class="cell-primary">{{ $user->name }}</span>
                                            <span class="cell-secondary">{{ $user->email }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td><span class="badge {{ $user->is_admin ? 'badge-success' : 'badge-neutral' }}">{{ $user->is_admin ? 'Administrator' : 'Standard user' }}</span></td>
                                <td>{{ $user->created_at?->format('M j, Y') }}</td>
                                <td>{{ $user->updated_at?->diffForHumans() }}</td>
                                <td>
                                    <div class="row-actions">
                                        <a class="button button-small" href="{{ route('users.show', $user) }}">View</a>
                                        <a class="button button-small" href="{{ route('users.edit', $user) }}">Edit</a>
                                        @if (! auth()->user()->is($user))
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user account? They will no longer be able to log in.');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="button button-danger button-small" type="submit">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <nav class="users-pagination" aria-label="User pagination">
                    <span>Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
                    <div class="button-row">
                        @if ($users->onFirstPage())
                            <span class="button button-small" aria-disabled="true">Previous</span>
                        @else
                            <a class="button button-small" href="{{ $users->previousPageUrl() }}">Previous</a>
                        @endif
                        @if ($users->hasMorePages())
                            <a class="button button-small" href="{{ $users->nextPageUrl() }}">Next</a>
                        @else
                            <span class="button button-small" aria-disabled="true">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        @endif
    </section>
@endsection
