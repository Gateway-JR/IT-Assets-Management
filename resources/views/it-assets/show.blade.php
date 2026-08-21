@extends('layouts.dashboard')

@section('title', $itAsset->display_name)
@section('topbar-title', 'IT Asset Inventory')

@section('styles')
    @include('it-assets._styles')
@endsection

@section('content')
    <header class="page-heading">
        <div>
            <p class="page-eyebrow">Equipment record #{{ $itAsset->id }}</p>
            <h1 class="page-title">{{ $itAsset->display_name }}</h1>
            <p class="page-description">Complete inventory record, assignment, network identifiers, procurement information, and source details.</p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ route('it-assets.index') }}">Back</a>
            <a class="button button-primary" href="{{ route('it-assets.edit', $itAsset) }}">Edit asset</a>
        </div>
    </header>

    @php
        $condition = strtolower(trim((string) $itAsset->condition));
        $conditionBadge = $itAsset->requires_attention
            ? 'badge-danger'
            : ((str_contains($condition, 'good') || ($condition !== 'not working' && str_contains($condition, 'working')))
                ? 'badge-success'
                : 'badge-neutral');
    @endphp

    <section class="panel asset-hero">
        <div>
            <h2>{{ $itAsset->asset_tag ?: 'Asset record #'.$itAsset->id }}</h2>
            <p>{{ collect([$itAsset->brand, $itAsset->model, $itAsset->category])->filter()->join(' · ') }}</p>
        </div>
        <div class="asset-hero-badges">
            <span class="badge {{ $conditionBadge }}">{{ $itAsset->condition ?: 'Condition not reported' }}</span>
            <span class="badge badge-neutral">{{ $itAsset->status ?: 'Status not reported' }}</span>
        </div>
    </section>

    <div class="asset-details">
        <section class="panel asset-detail-card">
            <h3>Identity and equipment</h3>
            <dl class="asset-detail-list">
                <dt>Asset tag</dt><dd>{{ $itAsset->asset_tag ?: '—' }}</dd>
                <dt>Asset name</dt><dd>{{ $itAsset->asset_name ?: '—' }}</dd>
                <dt>Category</dt><dd>{{ $itAsset->category }}</dd>
                <dt>Brand</dt><dd>{{ $itAsset->brand ?: '—' }}</dd>
                <dt>Model</dt><dd>{{ $itAsset->model ?: '—' }}</dd>
                <dt>Serial number</dt><dd class="asset-code">{{ $itAsset->serial_number ?: '—' }}</dd>
            </dl>
        </section>

        <section class="panel asset-detail-card">
            <h3>Assignment and location</h3>
            <dl class="asset-detail-list">
                <dt>Assigned user</dt><dd>{{ $itAsset->assigned_user ?: 'Unassigned' }}</dd>
                <dt>Department</dt><dd>{{ $itAsset->department ?: '—' }}</dd>
                <dt>Branch</dt><dd>{{ $itAsset->branch ?: '—' }}</dd>
                <dt>Location</dt><dd>{{ $itAsset->location ?: '—' }}</dd>
                <dt>Status</dt><dd>{{ $itAsset->status ?: '—' }}</dd>
                <dt>Condition</dt><dd>{{ $itAsset->condition ?: '—' }}</dd>
            </dl>
        </section>

        <section class="panel asset-detail-card">
            <h3>Network information</h3>
            <dl class="asset-detail-list">
                <dt>IP address</dt><dd class="asset-code">{{ $itAsset->ip_address ?: '—' }}</dd>
                <dt>MAC address</dt><dd class="asset-code">{{ $itAsset->mac_address ?: '—' }}</dd>
            </dl>
        </section>

        <section class="panel asset-detail-card">
            <h3>Procurement and warranty</h3>
            <dl class="asset-detail-list">
                <dt>Purchase date</dt><dd>{{ $itAsset->purchase_date ?: '—' }}</dd>
                <dt>Warranty start</dt><dd>{{ $itAsset->warranty_start ?: '—' }}</dd>
                <dt>Warranty end</dt><dd>{{ $itAsset->warranty_end ?: '—' }}</dd>
                <dt>Supplier</dt><dd>{{ $itAsset->supplier ?: '—' }}</dd>
            </dl>
        </section>

        <section class="panel asset-detail-card wide">
            <h3>Remarks</h3>
            <p class="asset-remarks">{{ $itAsset->remarks ?: 'No remarks recorded.' }}</p>
        </section>

        @if ($itAsset->source_file || $itAsset->source_sheet || $itAsset->source_row)
            <section class="panel asset-detail-card wide">
                <h3>Import source</h3>
                <dl class="asset-detail-list">
                    <dt>Workbook</dt><dd>{{ $itAsset->source_file ?: '—' }}</dd>
                    <dt>Worksheet</dt><dd>{{ $itAsset->source_sheet ?: '—' }}</dd>
                    <dt>Source row</dt><dd>{{ $itAsset->source_row ?: '—' }}</dd>
                    <dt>Imported</dt><dd>{{ $itAsset->imported_at?->format('M j, Y g:i A') ?: '—' }}</dd>
                </dl>
            </section>
        @endif

        <section class="panel asset-detail-card wide">
            <h3>Remove from active inventory</h3>
            <p class="asset-remarks">This keeps the record recoverable in the database but hides it from the active IT asset inventory.</p>
            <form method="POST" action="{{ route('it-assets.destroy', $itAsset) }}" onsubmit="return confirm('Remove this IT asset from the active inventory?');" style="margin-top: 16px;">
                @csrf
                @method('DELETE')
                <button class="button button-danger" type="submit">Remove asset</button>
            </form>
        </section>
    </div>
@endsection
