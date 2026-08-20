@extends('layouts.dashboard')

@section('title', 'Edit User')
@section('topbar-title', 'User Management')

@section('styles')
    @include('users._styles')
@endsection

@section('content')
    <div class="page-heading">
        <div>
            <p class="page-eyebrow">User management</p>
            <h1 class="page-title">Edit user</h1>
            <p class="page-description">Update {{ $user->name }}’s account details, password, or access level.</p>
        </div>
    </div>

    <div class="user-form-shell">
        <form class="panel user-form-panel" method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('users._form')
            <div class="form-actions">
                <button class="button button-primary" type="submit">Save changes</button>
                <a class="button" href="{{ route('users.show', $user) }}">Cancel</a>
            </div>
        </form>
        <aside class="panel user-side-panel">
            <h2>Account safety</h2>
            <p>Changing the email changes the username used at login. Changing the password takes effect the next time credentials are requested.</p>
            @if (auth()->user()->is($user))
                <p><strong>This is your current account.</strong> Its administrator access cannot be removed here.</p>
            @endif
        </aside>
    </div>
@endsection
