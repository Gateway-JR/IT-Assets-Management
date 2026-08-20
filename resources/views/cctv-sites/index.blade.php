@extends('layouts.admin')

@section('title', 'IT Inventory Registry')

@section('content')
    @php
        $fleetHealth = (int) $summary->total_cameras > 0
            ? (int) round(((int) $summary->online_cameras / (int) $summary->total_cameras) * 100)
            : 0;
    @endphp

    <div class="page-heading">
        <div>
            <p class="eyebrow">Asset Management</p>
            <h2 class="page-title">IT Inventory Registry</h2>
            <p class="page-copy">
                Maintain branch camera availability, NVR hardware, storage, ownership, and distribution records.
            </p>
        </div>

        <a class="button button-primary" href="{{ route('admin.cctv-sites.create') }}">
            <span aria-hidden="true">＋</span> Add inventory site
        </a>
    </div>

    <section class="stats-grid" aria-label="Inventory summary">
        <article class="stat-card stat-accent-blue">
            <p class="stat-label">Monitored Sites</p>
            <strong class="stat-value">{{ number_format((int) $summary->total_sites) }}</strong>
            <div class="stat-note">Registered branch locations</div>
        </article>

        <article class="stat-card stat-accent-success">
            <p class="stat-label">Online Cameras</p>
            <strong class="stat-value">{{ number_format((int) $summary->online_cameras) }}</strong>
            <div class="stat-note">{{ $fleetHealth }}% of installed cameras</div>
        </article>

        <article class="stat-card stat-accent-danger">
            <p class="stat-label">Offline Cameras</p>
            <strong class="stat-value">{{ number_format((int) $summary->offline_cameras) }}</strong>
            <div class="stat-note">Requires connectivity review</div>
        </article>

        <article class="stat-card stat-accent-warning">
            <p class="stat-label">Recording Issues</p>
            <strong class="stat-value">{{ number_format((int) $summary->recording_issues) }}</strong>
            <div class="stat-note">Requires recording validation</div>
        </article>
    </section>

    <section class="card" aria-labelledby="registry-title">
        <div class="card-header">
            <h3 class="card-title" id="registry-title">Branch Records</h3>
            <p class="card-subtitle">Use the filters to locate a branch, technician, vendor, or NVR.</p>
        </div>

        <div class="card-body">
            <form class="filters" method="GET" action="{{ route('admin.cctv-sites.index') }}">
                <div class="field no-margin">
                    <label for="search">Search records</label>
                    <input
                        id="search"
                        name="search"
                        type="search"
                        value="{{ request('search') }}"
                        placeholder="Branch, province, tech, vendor..."
                    >
                </div>

                <div class="field no-margin">
                    <label for="region">Region</label>
                    <select id="region" name="region">
                        <option value="">All regions</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region }}" @selected(request('region') === $region)>{{ $region }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field no-margin">
                    <label for="nvr_status">NVR status</label>
                    <select id="nvr_status" name="nvr_status">
                        <option value="">All statuses</option>
                        @foreach (['Operational', 'Offline', 'Maintenance', 'Unknown'] as $status)
                            <option value="{{ $status }}" @selected(request('nvr_status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button class="button button-primary" type="submit">Apply filters</button>
                    @if (request()->hasAny(['search', 'region', 'nvr_status']))
                        <a class="button button-secondary" href="{{ route('admin.cctv-sites.index') }}">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        @if ($sites->count())
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Branch / ID</th>
                            <th>Location</th>
                            <th>Business Unit</th>
                            <th>Assigned Tech</th>
                            <th>Camera Status</th>
                            <th>NVR Status</th>
                            <th>Storage</th>
                            <th>Hardware</th>
                            <th>Distribution</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sites as $site)
                            @php
                                $nvrBadge = match ($site->nvr_status) {
                                    'Operational' => 'badge-success',
                                    'Offline' => 'badge-danger',
                                    'Maintenance' => 'badge-warning',
                                    default => 'badge-neutral',
                                };

                                $healthBar = $site->camera_health_percentage >= 90
                                    ? 'success'
                                    : ($site->camera_health_percentage >= 70 ? 'warning' : 'danger');
                            @endphp
                            <tr>
                                <td>
                                    <span class="primary-cell">{{ $site->branch }}</span>
                                    <span class="secondary-cell">ID {{ str_pad((string) $site->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <span class="primary-cell">{{ $site->province }}</span>
                                    <span class="secondary-cell">{{ $site->region }}</span>
                                </td>
                                <td>{{ $site->business_unit ?: '—' }}</td>
                                <td>{{ $site->assigned_tech ?: 'Unassigned' }}</td>
                                <td>
                                    <div class="metric-stack">
                                        <span class="primary-cell">{{ $site->online_cameras }} / {{ $site->total_cameras }} online</span>
                                        <div class="progress" aria-label="{{ $site->camera_health_percentage }} percent online">
                                            <div class="progress-bar {{ $healthBar }}" style="width: {{ $site->camera_health_percentage }}%"></div>
                                        </div>
                                        <div class="metric-line">
                                            <span>Offline <strong>{{ $site->offline_cameras }}</strong></span>
                                            <span>Issues <strong>{{ $site->recording_issue_cameras }}</strong></span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge {{ $nvrBadge }}">{{ $site->nvr_status }}</span></td>
                                <td>
                                    @if ($site->hdd_capacity_gb !== null)
                                        <div class="metric-stack">
                                            <span class="primary-cell">{{ number_format((float) $site->storage_used_gb, 2) }} GB used</span>
                                            <div class="progress" aria-label="{{ $site->storage_percentage }} percent storage used">
                                                <div class="progress-bar {{ $site->storage_percentage >= 90 ? 'danger' : '' }}" style="width: {{ $site->storage_percentage }}%"></div>
                                            </div>
                                            <span class="secondary-cell">of {{ number_format((float) $site->hdd_capacity_gb, 2) }} GB</span>
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <span class="primary-cell">{{ $site->nvr_brand ?: '—' }}</span>
                                    <span class="secondary-cell">{{ $site->nvr_model ?: 'No model' }} · {{ $site->vendor ?: 'No vendor' }}</span>
                                </td>
                                <td>
                                    {{ $site->distribution ?: '—' }}
                                    @if ($site->distribution_summary)
                                        <span class="secondary-cell">{{ \Illuminate\Support\Str::limit($site->distribution_summary, 58) }}</span>
                                    @endif
                                </td>
                                <td>{{ $site->remarks ? \Illuminate\Support\Str::limit($site->remarks, 55) : '—' }}</td>
                                <td>
                                    <div class="action-list">
                                        <a class="button button-secondary button-sm" href="{{ route('admin.cctv-sites.show', $site) }}">View</a>
                                        <a class="button button-secondary button-sm" href="{{ route('admin.cctv-sites.edit', $site) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.cctv-sites.destroy', $site) }}" onsubmit="return confirm('Delete {{ addslashes($site->branch) }}? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="button button-danger button-sm" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($sites->hasPages())
                <nav class="pagination" aria-label="Inventory site pages">
                    <div class="pagination-copy">
                        Showing {{ $sites->firstItem() }}–{{ $sites->lastItem() }} of {{ $sites->total() }} records
                    </div>
                    <div class="pagination-actions">
                        <a class="button button-secondary button-sm {{ $sites->onFirstPage() ? 'disabled' : '' }}" href="{{ $sites->previousPageUrl() ?: '#' }}">Previous</a>
                        <span class="button button-secondary button-sm disabled">Page {{ $sites->currentPage() }} of {{ $sites->lastPage() }}</span>
                        <a class="button button-secondary button-sm {{ $sites->hasMorePages() ? '' : 'disabled' }}" href="{{ $sites->nextPageUrl() ?: '#' }}">Next</a>
                    </div>
                </nav>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon" aria-hidden="true">◉</div>
                <h3 class="empty-title">No inventory sites found</h3>
                <p class="empty-copy">
                    {{ request()->hasAny(['search', 'region', 'nvr_status'])
                        ? 'No records match the current filters. Clear the filters and try again.'
                        : 'Create the first branch record to begin managing equipment and system status.' }}
                </p>
                @if (request()->hasAny(['search', 'region', 'nvr_status']))
                    <a class="button button-secondary" href="{{ route('admin.cctv-sites.index') }}">Clear filters</a>
                @else
                    <a class="button button-primary" href="{{ route('admin.cctv-sites.create') }}">Add first site</a>
                @endif
            </div>
        @endif
    </section>
@endsection
