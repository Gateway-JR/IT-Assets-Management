@extends('layouts.dashboard')

@section('title', $user->name)
@section('topbar-title', 'User Management')

@section('styles')
    @include('users._styles')
@endsection

@section('content')
    <div class="page-heading">
        <div>
            <p class="page-eyebrow">User account</p>
            <h1 class="page-title">{{ $user->name }}</h1>
            <p class="page-description">Review this user’s login identity and access level.</p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ route('users.index') }}">Back to users</a>
            <a class="button button-primary" href="{{ route('users.edit', $user) }}">Edit user</a>
        </div>
    </div>

    <section class="panel" aria-label="User account details">
        <div class="users-panel-header">
            <div class="user-identity">
                <span class="user-avatar" aria-hidden="true">{{ str($user->name)->explode(' ')->filter()->take(2)->map(fn ($part) => str($part)->substr(0, 1)->upper())->join('') }}</span>
                <span>
                    <span class="cell-primary">{{ $user->name }}</span>
                    <span class="cell-secondary">Account ID {{ $user->id }}</span>
                </span>
            </div>
            <span class="badge {{ $user->is_admin ? 'badge-success' : 'badge-neutral' }}">{{ $user->is_admin ? 'Administrator' : 'Standard user' }}</span>
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Email / login</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Access level</span>
                <span class="detail-value">{{ $user->is_admin ? 'Administrator' : 'Standard user' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Account created</span>
                <span class="detail-value">{{ $user->created_at?->format('F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Last updated</span>
                <span class="detail-value">{{ $user->updated_at?->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>
    </section>

    @if (! auth()->user()->is($user))
        <section class="danger-zone" aria-labelledby="delete-user-title">
            <h2 id="delete-user-title">Delete user account</h2>
            <p>This permanently removes the account and prevents this user from signing in.</p>
            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Delete this user account? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Delete user</button>
            </form>
        </section>
    @endif
@endsection
