@extends('layouts.dashboard')

@section('title', 'Edit IT Asset')
@section('topbar-title', 'IT Asset Inventory')

@section('styles')
    @include('it-assets._styles')
@endsection

@section('content')
    <header class="page-heading">
        <div>
            <p class="page-eyebrow">Equipment record #{{ $itAsset->id }}</p>
            <h1 class="page-title">Edit {{ $itAsset->display_name }}</h1>
            <p class="page-description">Update the asset details while retaining its workbook import history.</p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ route('it-assets.show', $itAsset) }}">Cancel editing</a>
        </div>
    </header>

    <form class="panel asset-form-panel" method="POST" action="{{ route('it-assets.update', $itAsset) }}">
        @csrf
        @method('PUT')
        @include('it-assets._form', ['submitLabel' => 'Save changes'])
    </form>
@endsection
