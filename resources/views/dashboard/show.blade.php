@extends('layouts.dashboard')

@section('title', $site->branch)
@section('topbar-title', 'Site Record')

@section('styles')
    .record-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.45fr) minmax(300px, 0.55fr);
        gap: 18px;
        align-items: start;
    }

    .record-card {
        overflow: hidden;
    }

    .record-header {
        position: relative;
        padding: 26px;
        color: #ffffff;
        background:
            radial-gradient(circle at 100% 0, rgba(103, 215, 231, 0.16), transparent 35%),
            var(--navy-900);
    }

    .record-header::after {
        content: "";
        position: absolute;
        right: 26px;
        bottom: 0;
        width: 80px;
        height: 3px;
        background: var(--cyan-300);
    }

    .record-id {
        margin: 0 0 10px;
        color: #8fc7ef;
        font-size: 0.64rem;
        font-weight: 850;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .record-name {
        margin: 0;
        font-size: clamp(1.4rem, 2.4vw, 2rem);
        font-weight: 760;
        letter-spacing: -0.03em;
    }

    .record-location {
        margin: 9px 0 0;
        color: #9fb2c6;
        font-size: 0.75rem;
    }

    .detail-section {
        padding: 25px 26px;
        border-bottom: 1px solid var(--line-soft);
    }

    .detail-section:last-child {
        border-bottom: 0;
    }

    .detail-heading {
        margin: 0 0 18px;
        color: var(--blue-500);
        font-size: 0.63rem;
        font-weight: 850;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .definition-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px 28px;
        margin: 0;
    }

    .definition-grid div {
        min-width: 0;
    }

    .definition-grid dt {
        margin: 0 0 7px;
        color: var(--muted-light);
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .definition-grid dd {
        margin: 0;
        color: var(--navy-900);
        font-size: 0.78rem;
        font-weight: 680;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .definition-wide {
        grid-column: 1 / -1;
    }

    .camera-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px;
    }

    .camera-stat {
        padding: 15px;
        border: 1px solid var(--line-soft);
        background: #f9fbfc;
    }

    .camera-stat span {
        display: block;
        color: var(--muted);
        font-size: 0.61rem;
        font-weight: 780;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .camera-stat strong {
        display: block;
        margin-top: 10px;
        color: var(--navy-900);
        font-size: 1.35rem;
        font-weight: 800;
    }

    .camera-stat.online { border-top: 3px solid var(--success); }
    .camera-stat.offline { border-top: 3px solid var(--danger); }
    .camera-stat.issue { border-top: 3px solid var(--warning); }

    .storage-box {
        margin-top: 22px;
        padding: 16px;
        border: 1px solid var(--line-soft);
    }

    .storage-topline {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 11px;
        color: var(--muted);
        font-size: 0.68rem;
    }

    .storage-topline strong {
        color: var(--navy-900);
    }

    .side-card {
        padding: 22px;
    }

    .side-card + .side-card {
        margin-top: 14px;
    }

    .side-title {
        margin: 0;
        color: var(--navy-900);
        font-size: 0.9rem;
        font-weight: 780;
    }

    .health-list {
        margin: 19px 0 0;
        padding: 0;
        list-style: none;
    }

    .health-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid var(--line-soft);
        color: var(--muted);
        font-size: 0.69rem;
    }

    .health-list li:last-child {
        border-bottom: 0;
    }

    .danger-zone {
        border-color: #f0d2cf;
    }

    .danger-zone p {
        margin: 9px 0 16px;
        color: var(--muted);
        font-size: 0.69rem;
        line-height: 1.55;
    }

    @media (max-width: 940px) {
        .record-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .definition-grid { grid-template-columns: 1fr; }
        .definition-wide { grid-column: auto; }
        .camera-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .detail-section,
        .record-header { padding: 21px 18px; }
    }
@endsection

@section('content')
    <div class="page-heading">
        <div>
            <p class="page-eyebrow">Branch record</p>
            <h1 class="page-title">{{ $site->branch }}</h1>
            <p class="page-description">Complete equipment and operational record for this site.</p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ route('dashboard') }}#inventory">Back to inventory</a>
            <a class="button button-primary" href="{{ route('cctv-sites.edit', $site) }}">Edit record</a>
        </div>
    </div>

    <div class="record-grid">
        <article class="panel record-card">
            <header class="record-header">
                <p class="record-id">
                    {{ $site->source_sheet ? $site->source_sheet.' workbook row '.$site->source_row : 'Site ID #'.str_pad((string) $site->id, 4, '0', STR_PAD_LEFT) }}
                </p>
                <h2 class="record-name">{{ $site->branch }}</h2>
                <p class="record-location">{{ $site->province }} · {{ $site->region }}</p>
            </header>

            <section class="detail-section">
                <h3 class="detail-heading">Branch details</h3>
                <dl class="definition-grid">
                    <div>
                        <dt>Business unit</dt>
                        <dd>{{ $site->business_unit }}</dd>
                    </div>
                    <div>
                        <dt>Assigned technician</dt>
                        <dd>{{ $site->assigned_tech ?: 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt>Region</dt>
                        <dd>{{ $site->region }}</dd>
                    </div>
                    <div>
                        <dt>Province</dt>
                        <dd>{{ $site->province }}</dd>
                    </div>
                </dl>
            </section>

            <section class="detail-section">
                <h3 class="detail-heading">Camera status</h3>
                <div class="camera-grid">
                    <div class="camera-stat">
                        <span>Total</span>
                        <strong>{{ number_format($site->total_cameras) }}</strong>
                    </div>
                    <div class="camera-stat online">
                        <span>Online</span>
                        <strong>{{ number_format($site->online_cameras) }}</strong>
                    </div>
                    <div class="camera-stat offline">
                        <span>Offline</span>
                        <strong>{{ number_format($site->offline_cameras) }}</strong>
                    </div>
                    <div class="camera-stat issue">
                        <span>Recording issue</span>
                        <strong>{{ number_format($site->recording_issue_cameras) }}</strong>
                    </div>
                </div>
            </section>

            <section class="detail-section">
                <h3 class="detail-heading">NVR and storage</h3>
                <dl class="definition-grid">
                    <div>
                        <dt>NVR status</dt>
                        <dd>
                            <span class="badge {{ $site->nvr_is_healthy ? 'badge-success' : 'badge-warning' }}">
                                {{ $site->nvr_status_label }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt>Vendor</dt>
                        <dd>{{ $site->vendor }}</dd>
                    </div>
                    <div>
                        <dt>NVR brand</dt>
                        <dd>{{ $site->nvr_brand }}</dd>
                    </div>
                    <div>
                        <dt>NVR model</dt>
                        <dd>{{ $site->nvr_model ?: 'Not reported' }}</dd>
                    </div>
                    <div>
                        <dt>NVR RLP</dt>
                        <dd>{{ $site->nvr_rlp ?: 'Not reported' }}</dd>
                    </div>
                </dl>

                <div class="storage-box">
                    <div class="storage-topline">
                        <span><strong>{{ $site->storage_status ?: 'Status not reported' }}</strong></span>
                        <span>{{ $site->storage_capacity_label }} capacity</span>
                    </div>
                    <span class="cell-secondary">Retention: {{ $site->recording_days ?: 'Not reported' }}</span>
                </div>
            </section>

            <section class="detail-section">
                <h3 class="detail-heading">Distribution</h3>
                <dl class="definition-grid">
                    <div>
                        <dt>Distribution status</dt>
                        <dd>
                            <span class="badge">
                                {{ $site->distribution_status ?: 'Not reported' }}
                            </span>
                        </dd>
                    </div>
                    <div class="definition-wide">
                        <dt>Distribution summary</dt>
                        <dd>{{ $site->distribution_summary ?: 'No distribution summary provided.' }}</dd>
                    </div>
                    <div class="definition-wide">
                        <dt>Remarks</dt>
                        <dd>{{ $site->remarks ?: 'No remarks recorded.' }}</dd>
                    </div>
                </dl>
            </section>
        </article>

        <aside>
            <section class="panel side-card">
                <p class="panel-kicker">Operational status</p>
                <h2 class="side-title">Site health snapshot</h2>
                <ul class="health-list">
                    <li>
                        Camera availability
                        <strong>{{ $site->total_cameras > 0 ? number_format(($site->online_cameras / $site->total_cameras) * 100, 1) : '0.0' }}%</strong>
                    </li>
                    <li>
                        NVR health
                        <span class="badge {{ $site->nvr_is_healthy ? 'badge-success' : 'badge-warning' }}">{{ $site->nvr_status_label }}</span>
                    </li>
                    <li>
                        Storage capacity
                        <strong>{{ $site->storage_capacity_label }}</strong>
                    </li>
                    <li>
                        Distribution
                        <strong>{{ $site->distribution_status ?: 'Not reported' }}</strong>
                    </li>
                </ul>
            </section>

            <section class="panel side-card">
                <p class="panel-kicker">Record activity</p>
                <h2 class="side-title">Last updated</h2>
                <p class="context-copy" style="margin-top:9px;color:var(--muted);font-size:.7rem;line-height:1.55;">
                    {{ $site->updated_at->format('F j, Y \a\t g:i A') }}
                </p>
            </section>

            <section class="panel side-card danger-zone">
                <p class="panel-kicker" style="color:var(--danger);">Record management</p>
                <h2 class="side-title">Remove active record</h2>
                <p>This uses a recoverable soft deletion and does not immediately erase the database row.</p>
                <form method="POST" action="{{ route('cctv-sites.destroy', $site) }}" onsubmit="return confirm('Remove {{ addslashes($site->branch) }} from the active inventory?');">
                    @csrf
                    @method('DELETE')
                    <button class="button button-danger" type="submit">Remove record</button>
                </form>
            </section>
        </aside>
    </div>
@endsection
