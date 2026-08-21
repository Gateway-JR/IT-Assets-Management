@extends('layouts.dashboard')

@section('title', 'Add IT Asset')
@section('topbar-title', 'IT Asset Inventory')

@section('styles')
    @include('it-assets._styles')
@endsection

@section('content')
    <header class="page-heading">
        <div>
            <p class="page-eyebrow">New equipment record</p>
            <h1 class="page-title">Add an IT asset</h1>
            <p class="page-description">Record the equipment identity, assignment, location, network information, and current condition.</p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ route('it-assets.index') }}">Back to inventory</a>
        </div>
    </header>

    <form class="panel asset-form-panel" method="POST" action="{{ route('it-assets.store') }}">
        @csrf
        @include('it-assets._form', ['submitLabel' => 'Add IT asset'])
    </form>
@endsection
