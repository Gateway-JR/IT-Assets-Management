@extends('layouts.dashboard')

@section('title', 'Add User')
@section('topbar-title', 'User Management')

@section('styles')
    @include('users._styles')
@endsection

@section('content')
    <div class="page-heading">
        <div>
            <p class="page-eyebrow">User management</p>
            <h1 class="page-title">Add user</h1>
            <p class="page-description">Create a login account and choose what level of access it should have.</p>
        </div>
    </div>

    <div class="user-form-shell">
        <form class="panel user-form-panel" method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('users._form')
            <div class="form-actions">
                <button class="button button-primary" type="submit">Create user</button>
                <a class="button" href="{{ route('users.index') }}">Cancel</a>
            </div>
        </form>
        <aside class="panel user-side-panel">
            <h2>Before creating an account</h2>
            <ul>
                <li>Use an email address that uniquely identifies the user.</li>
                <li>Passwords are stored securely as one-way hashes.</li>
                <li>Grant administrator access only to staff who manage accounts.</li>
            </ul>
        </aside>
    </div>
@endsection
