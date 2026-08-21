@extends('layouts.dashboard')

@section('title', 'IT Assets')
@section('topbar-title', 'IT Asset Inventory')

@section('styles')
    @include('it-assets._styles')
@endsection

@section('content')
    <header class="page-heading">
        <div>
            <p class="page-eyebrow">Equipment register</p>
            <h1 class="page-title">IT asset inventory</h1>
            <p class="page-description">
                Track laptops, desktops, peripherals, network details, assignments, locations, and equipment condition across Gateway branches.
            </p>
        </div>
        <div class="button-row">
            <a class="button button-primary" href="{{ route('it-assets.create') }}">Add IT asset</a>
        </div>
    </header>

    <section class="asset-summary-grid" aria-label="Filtered IT asset summary">
        <article class="panel asset-metric">
            <span class="asset-metric-label">Total assets</span>
            <strong class="asset-metric-value">{{ number_format($summary['total']) }}</strong>
            <span class="asset-metric-note">Matching the current filters</span>
        </article>
        <article class="panel asset-metric">
            <span class="asset-metric-label">Assigned</span>
            <strong class="asset-metric-value">{{ number_format($summary['assigned']) }}</strong>
            <span class="asset-metric-note">Issued to a user or role</span>
        </article>
        <article class="panel asset-metric stock">
            <span class="asset-metric-label">In stock</span>
            <strong class="asset-metric-value">{{ number_format($summary['stock']) }}</strong>
            <span class="asset-metric-note">Available or held in inventory</span>
        </article>
        <article class="panel asset-metric attention">
            <span class="asset-metric-label">Needs attention</span>
            <strong class="asset-metric-value">{{ number_format($summary['attention']) }}</strong>
            <span class="asset-metric-note">Damaged, not working, or for repair</span>
        </article>
        <article class="panel asset-metric">
            <span class="asset-metric-label">Networked</span>
            <strong class="asset-metric-value">{{ number_format($summary['networked']) }}</strong>
            <span class="asset-metric-note">IP or MAC address recorded</span>
        </article>
        <article class="panel asset-metric">
            <span class="asset-metric-label">Branches</span>
            <strong class="asset-metric-value">{{ number_format($summary['branches']) }}</strong>
            <span class="asset-metric-note">Named locations represented</span>
        </article>
    </section>

    <section class="panel asset-toolbar" aria-label="Asset filters and workbook import">
        <form class="asset-filters" method="GET" action="{{ route('it-assets.index') }}">
            <div>
                <label class="field-label" for="q">Search inventory</label>
                <input class="control" id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Asset, serial, user, branch, brand...">
            </div>
            <div>
                <label class="field-label" for="category">Category</label>
                <select class="control" id="category" name="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label" for="status">Status</label>
                <select class="control" id="status" name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label" for="branch">Branch</label>
                <select class="control" id="branch" name="branch">
                    <option value="">All branches</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch }}" @selected(request('branch') === $branch)>{{ $branch }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label" for="state">Quick view</label>
                <select class="control" id="state" name="state">
                    <option value="">All records</option>
                    <option value="attention" @selected(request('state') === 'attention')>Needs attention</option>
                    <option value="networked" @selected(request('state') === 'networked')>Networked assets</option>
                    <option value="unassigned" @selected(request('state') === 'unassigned')>No assigned user</option>
                </select>
            </div>
            <div class="asset-filter-actions">
                <button class="button button-primary" type="submit">Apply</button>
                <a class="button" href="{{ route('it-assets.index') }}">Clear</a>
            </div>
        </form>

        <details class="asset-import" @if ($errors->has('import_file')) open @endif>
            <summary>Import or refresh from an Excel workbook</summary>
            <form class="asset-import-form" method="POST" action="{{ route('it-assets.import') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="field-label" for="import_file">IT asset workbook (.xlsx or .xls)</label>
                    <input class="control" id="import_file" name="import_file" type="file" accept=".xlsx,.xls" required>
                    @error('import_file')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <button class="button button-primary" type="submit">Import workbook</button>
            </form>
        </details>
    </section>

    <section class="panel asset-table-panel">
        <div class="asset-table-heading">
            <div>
                <h2>Asset register</h2>
                <p>{{ number_format($assets->total()) }} matching {{ str('record')->plural($assets->total()) }}</p>
            </div>
        </div>

        @if ($assets->isNotEmpty())
            <div class="asset-table-wrap">
                <table class="asset-table">
                    <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Category</th>
                            <th>Assignment</th>
                            <th>Branch / location</th>
                            <th>Identifiers</th>
                            <th>Status / condition</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($assets as $asset)
                            @php
                                $condition = strtolower(trim((string) $asset->condition));
                                $conditionBadge = $asset->requires_attention
                                    ? 'badge-danger'
                                    : ((str_contains($condition, 'good') || ($condition !== 'not working' && str_contains($condition, 'working')))
                                        ? 'badge-success'
                                        : 'badge-neutral');
                            @endphp
                            <tr>
                                <td>
                                    <a class="asset-primary" href="{{ route('it-assets.show', $asset) }}">{{ $asset->display_name }}</a>
                                    <span class="asset-secondary">
                                        {{ $asset->asset_tag ? 'Tag '.$asset->asset_tag : 'Record #'.$asset->id }}
                                    </span>
                                </td>
                                <td>
                                    <span class="asset-primary">{{ $asset->category }}</span>
                                    <span class="asset-secondary">{{ collect([$asset->brand, $asset->model])->filter()->join(' · ') ?: 'Brand/model not recorded' }}</span>
                                </td>
                                <td>
                                    <span class="asset-primary">{{ $asset->assigned_user ?: 'Unassigned' }}</span>
                                    <span class="asset-secondary">{{ $asset->department ?: 'Department not recorded' }}</span>
                                </td>
                                <td>
                                    <span class="asset-primary">{{ $asset->branch ?: 'Branch not recorded' }}</span>
                                    <span class="asset-secondary">{{ $asset->location ?: 'Location not recorded' }}</span>
                                </td>
                                <td>
                                    <span class="asset-primary asset-code">{{ $asset->serial_number ?: 'No serial number' }}</span>
                                    <span class="asset-secondary asset-code">{{ $asset->ip_address ?: ($asset->mac_address ?: 'No network address') }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $conditionBadge }}">{{ $asset->condition ?: 'Not reported' }}</span>
                                    <span class="asset-secondary">{{ $asset->status ?: 'Status not reported' }}</span>
                                </td>
                                <td>
                                    <div class="asset-actions">
                                        <a class="button button-small" href="{{ route('it-assets.show', $asset) }}">View</a>
                                        <a class="button button-small" href="{{ route('it-assets.edit', $asset) }}">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($assets->hasPages())
                <nav class="asset-pagination" aria-label="IT asset pages">
                    <span>Showing {{ $assets->firstItem() }}–{{ $assets->lastItem() }} of {{ number_format($assets->total()) }}</span>
                    <div class="asset-pagination-actions">
                        <a class="button button-small {{ $assets->onFirstPage() ? 'disabled' : '' }}" href="{{ $assets->previousPageUrl() ?: '#' }}">Previous</a>
                        <a class="button button-small {{ $assets->hasMorePages() ? '' : 'disabled' }}" href="{{ $assets->nextPageUrl() ?: '#' }}">Next</a>
                    </div>
                </nav>
            @endif
        @else
            <div class="asset-empty">
                <h3>No IT assets found</h3>
                <p>
                    {{ request()->hasAny(['q', 'category', 'status', 'branch', 'state'])
                        ? 'No records match the current filters. Clear them and try a broader search.'
                        : 'Import Assets-List-Database.xlsx or add the first IT asset manually.' }}
                </p>
                @if (request()->hasAny(['q', 'category', 'status', 'branch', 'state']))
                    <a class="button" href="{{ route('it-assets.index') }}">Clear filters</a>
                @else
                    <a class="button button-primary" href="{{ route('it-assets.create') }}">Add first asset</a>
                @endif
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const filterForm = document.querySelector('.asset-filters');
            if (!filterForm) return;

            filterForm.querySelectorAll('select').forEach(function (select) {
                select.addEventListener('change', function () {
                    filterForm.requestSubmit();
                });
            });
        })();
    </script>
@endpush
