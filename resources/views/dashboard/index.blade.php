@extends('layouts.dashboard')

@section('title', 'Network Overview')
@section('topbar-title', 'IT Inventory Overview')

@section('styles')
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .metrics {
        display: grid;
        grid-template-columns: repeat(5, minmax(150px, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .metric-card {
        position: relative;
        min-width: 0;
        overflow: hidden;
        padding: 20px;
        border: 1px solid var(--line-soft);
        background: #ffffff;
        box-shadow: 0 12px 32px rgba(7, 17, 31, 0.055);
    }

    .metric-card::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 42px;
        height: 3px;
        background: var(--blue-500);
    }

    .metric-card.metric-online::after { background: var(--success); }
    .metric-card.metric-offline::after { background: var(--danger); }
    .metric-card.metric-warning::after { background: var(--warning); }

    .metric-label {
        margin: 0;
        color: var(--muted);
        font-size: 0.64rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .metric-value {
        display: block;
        margin-top: 15px;
        color: var(--navy-900);
        font-size: clamp(1.7rem, 2.7vw, 2.25rem);
        font-weight: 780;
        line-height: 1;
        letter-spacing: -0.04em;
    }

    .metric-detail {
        display: block;
        margin-top: 9px;
        overflow: hidden;
        color: var(--muted-light);
        font-size: 0.67rem;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .health-strip {
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(300px, 0.7fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .health-panel,
    .storage-panel {
        padding: 22px;
    }

    .panel-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .panel-kicker {
        margin: 0 0 6px;
        color: var(--blue-500);
        font-size: 0.61rem;
        font-weight: 850;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .panel-title {
        margin: 0;
        color: var(--navy-900);
        font-size: 0.98rem;
        font-weight: 760;
    }

    .health-rate {
        color: var(--success);
        font-size: 0.85rem;
        font-weight: 820;
    }

    .health-bar {
        display: flex;
        height: 9px;
        overflow: hidden;
        background: #edf1f5;
    }

    .health-online { background: var(--success); }
    .health-offline { background: var(--danger); }
    .health-issue { background: var(--warning); }

    .health-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin-top: 16px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--muted);
        font-size: 0.7rem;
    }

    .legend-swatch {
        width: 8px;
        height: 8px;
        background: var(--success);
    }

    .legend-swatch.offline { background: var(--danger); }
    .legend-swatch.issue { background: var(--warning); }

    .storage-value {
        margin: 0 0 12px;
        color: var(--navy-900);
        font-size: 1.65rem;
        font-weight: 780;
        letter-spacing: -0.035em;
    }

    .storage-caption {
        display: block;
        margin-top: 10px;
        color: var(--muted);
        font-size: 0.68rem;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: minmax(280px, 0.72fr) minmax(480px, 1.55fr);
        gap: 14px;
        margin-bottom: 14px;
    }

    .overview-panel {
        min-width: 0;
        padding: 22px;
    }

    .overview-panel .panel-heading {
        margin-bottom: 18px;
    }

    .panel-meta {
        flex: 0 0 auto;
        padding: 6px 9px;
        border: 1px solid var(--line);
        color: var(--muted);
        background: #f8fafc;
        font-size: 0.61rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .availability-layout {
        display: grid;
        grid-template-columns: 154px minmax(0, 1fr);
        align-items: center;
        gap: 25px;
        min-height: 210px;
    }

    .availability-ring {
        position: relative;
        width: 154px;
        aspect-ratio: 1;
        display: grid;
        place-items: center;
        border-radius: 50%;
        background: conic-gradient(var(--success) calc(var(--availability) * 1%), var(--danger) 0);
    }

    .availability-ring::before {
        content: "";
        position: absolute;
        width: 108px;
        aspect-ratio: 1;
        border-radius: 50%;
        background: #ffffff;
        box-shadow: inset 0 0 0 1px var(--line-soft);
    }

    .availability-ring-value {
        position: relative;
        z-index: 1;
        color: var(--navy-900);
        font-size: 1.55rem;
        font-weight: 800;
        letter-spacing: -0.05em;
        line-height: 1;
    }

    .availability-ring-label {
        position: relative;
        z-index: 1;
        margin-top: -37px;
        color: var(--muted-light);
        font-size: 0.58rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .availability-stats {
        display: grid;
        gap: 0;
    }

    .availability-stat {
        display: grid;
        grid-template-columns: 10px minmax(0, 1fr) auto;
        align-items: center;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid var(--line-soft);
    }

    .availability-stat:last-child {
        border-bottom: 0;
    }

    .availability-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--success);
    }

    .availability-dot.offline { background: var(--danger); }
    .availability-dot.issue { background: var(--warning); }

    .availability-name {
        color: var(--muted);
        font-size: 0.7rem;
        font-weight: 700;
    }

    .availability-count {
        color: var(--navy-900);
        font-size: 0.82rem;
        font-weight: 820;
        font-variant-numeric: tabular-nums;
    }

    .branch-chart {
        display: grid;
        gap: 13px;
    }

    .branch-chart-row {
        display: grid;
        grid-template-columns: minmax(112px, 0.65fr) minmax(220px, 1.7fr) 58px;
        align-items: center;
        gap: 13px;
    }

    .chart-branch-name {
        min-width: 0;
    }

    .chart-branch-name strong,
    .chart-branch-name span {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .chart-branch-name strong {
        color: var(--navy-900);
        font-size: 0.68rem;
        font-weight: 760;
    }

    .chart-branch-name span {
        margin-top: 4px;
        color: var(--muted-light);
        font-size: 0.58rem;
    }

    .branch-bar {
        display: flex;
        height: 17px;
        overflow: hidden;
        background: #edf1f5;
    }

    .branch-bar-online { background: var(--blue-500); }
    .branch-bar-offline { background: var(--danger); }

    .branch-chart-value {
        color: var(--navy-900);
        font-size: 0.66rem;
        font-weight: 820;
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    .branch-chart-value small {
        display: block;
        margin-top: 3px;
        color: var(--warning);
        font-size: 0.53rem;
        font-weight: 800;
    }

    .secondary-overview-grid {
        display: grid;
        grid-template-columns: minmax(330px, 0.85fr) minmax(560px, 1.4fr);
        gap: 14px;
        margin-bottom: 20px;
    }

    .region-chart {
        display: grid;
        gap: 16px;
    }

    .region-row {
        display: grid;
        gap: 7px;
    }

    .region-label {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 14px;
    }

    .region-label strong {
        overflow: hidden;
        color: var(--navy-900);
        font-size: 0.68rem;
        font-weight: 760;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .region-label span {
        flex: 0 0 auto;
        color: var(--muted);
        font-size: 0.6rem;
        font-variant-numeric: tabular-nums;
    }

    .region-track {
        height: 11px;
        background: #edf1f5;
    }

    .region-volume {
        display: flex;
        min-width: 2px;
        height: 100%;
        overflow: hidden;
    }

    .region-online { background: var(--blue-500); }
    .region-offline { background: var(--danger); }

    .chart-legend-note {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 18px;
        color: var(--muted);
        font-size: 0.61rem;
    }

    .chart-legend-note span {
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .chart-legend-note i {
        width: 8px;
        height: 8px;
        background: var(--blue-500);
    }

    .chart-legend-note i.offline { background: var(--danger); }

    .attention-panel {
        min-width: 0;
        overflow: hidden;
    }

    .attention-panel .inventory-header {
        padding: 21px 22px 16px;
    }

    .attention-table {
        width: 100%;
        min-width: 720px;
        border-collapse: collapse;
    }

    .attention-table th {
        padding: 10px 12px;
        border-bottom: 1px solid var(--line);
        color: #607286;
        background: #f6f8fa;
        font-size: 0.56rem;
        font-weight: 850;
        letter-spacing: 0.08em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .attention-table td {
        padding: 12px;
        border-bottom: 1px solid var(--line-soft);
        font-size: 0.68rem;
        vertical-align: middle;
    }

    .attention-table tbody tr:last-child td { border-bottom: 0; }

    .availability-inline {
        display: grid;
        grid-template-columns: minmax(70px, 1fr) 38px;
        align-items: center;
        gap: 8px;
        min-width: 120px;
    }

    .availability-inline .progress { height: 5px; }

    .availability-inline .progress > span {
        display: block;
        height: 100%;
        background: var(--success);
    }

    .availability-inline strong {
        color: var(--navy-900);
        font-size: 0.62rem;
        font-variant-numeric: tabular-nums;
    }

    .attention-number {
        color: var(--navy-900);
        font-weight: 800;
        font-variant-numeric: tabular-nums;
    }

    .attention-number.is-alert { color: var(--danger); }
    .attention-number.is-warning { color: var(--warning); }

    .overview-empty {
        display: grid;
        min-height: 210px;
        place-items: center;
        color: var(--muted);
        font-size: 0.72rem;
        text-align: center;
    }

    .inventory-panel {
        overflow: hidden;
    }

    .inventory-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 21px 22px;
        border-bottom: 1px solid var(--line-soft);
    }

    .result-count {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 0.7rem;
    }

    .filters {
        display: grid;
        grid-template-columns: minmax(200px, 1.45fr) repeat(4, minmax(135px, 0.8fr)) auto;
        gap: 10px;
        padding: 16px 22px;
        border-bottom: 1px solid var(--line-soft);
        background: #f9fbfc;
    }

    .filter-search {
        position: relative;
    }

    .filter-search::before {
        content: "";
        position: absolute;
        left: 14px;
        top: 50%;
        width: 9px;
        height: 9px;
        transform: translateY(-65%);
        border: 1.5px solid var(--muted-light);
        border-radius: 50%;
        pointer-events: none;
    }

    .filter-search::after {
        content: "";
        position: absolute;
        left: 23px;
        top: 26px;
        width: 6px;
        height: 1.5px;
        transform: rotate(45deg);
        background: var(--muted-light);
        pointer-events: none;
    }

    .filter-search .control {
        padding-left: 38px;
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .filters.is-loading {
        opacity: 0.72;
        pointer-events: none;
    }

    .filters.is-loading .control,
    .filters.is-loading .button {
        cursor: wait;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .inventory-table {
        width: 100%;
        min-width: 1180px;
        border-collapse: collapse;
    }

    .inventory-table th {
        position: sticky;
        z-index: 2;
        top: 0;
        padding: 13px 14px;
        border-bottom: 1px solid var(--line);
        color: #607286;
        background: #f6f8fa;
        font-size: 0.61rem;
        font-weight: 850;
        letter-spacing: 0.09em;
        text-align: left;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sort-link {
        text-decoration: none;
    }

    .sort-link:hover {
        color: var(--blue-500);
    }

    .inventory-table td {
        padding: 16px 14px;
        border-bottom: 1px solid var(--line-soft);
        vertical-align: middle;
        font-size: 0.72rem;
    }

    .inventory-table tbody tr {
        border-left: 3px solid transparent;
        transition: background 120ms ease;
    }

    .inventory-table tbody tr:hover {
        background: #f9fbfc;
    }

    .inventory-table tbody tr.needs-attention {
        border-left-color: var(--warning);
    }

    .id-cell {
        color: var(--muted-light);
        font-variant-numeric: tabular-nums;
    }

    .cell-primary {
        display: block;
        color: var(--navy-900);
        font-size: 0.77rem;
        font-weight: 760;
        line-height: 1.35;
    }

    .cell-secondary {
        display: block;
        margin-top: 5px;
        color: var(--muted);
        font-size: 0.66rem;
        line-height: 1.4;
    }

    .camera-total {
        display: block;
        margin-bottom: 7px;
        color: var(--navy-900);
        font-weight: 780;
    }

    .camera-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .camera-chip {
        padding: 3px 6px;
        border-radius: 2px;
        color: #15803d;
        background: var(--success-soft);
        font-size: 0.59rem;
        font-weight: 800;
    }

    .camera-chip.offline {
        color: var(--danger);
        background: var(--danger-soft);
    }

    .camera-chip.issue {
        color: var(--warning);
        background: var(--warning-soft);
    }

    .storage-cell {
        min-width: 145px;
    }

    .storage-numbers {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
        color: var(--muted);
        font-size: 0.63rem;
        font-variant-numeric: tabular-nums;
    }

    .summary-cell {
        max-width: 230px;
    }

    .truncate {
        display: -webkit-box;
        overflow: hidden;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        color: var(--muted);
        line-height: 1.45;
    }

    .remarks-flag {
        display: inline-block;
        margin-top: 6px;
        color: var(--blue-500);
        font-size: 0.61rem;
        font-weight: 760;
    }

    .row-actions {
        display: flex;
        gap: 6px;
    }

    .empty-state {
        padding: 70px 24px;
        text-align: center;
    }

    .empty-mark {
        width: 52px;
        height: 42px;
        margin: 0 auto 17px;
        display: grid;
        place-items: center;
        border: 1px solid var(--line);
        color: var(--blue-500);
        font-size: 0.7rem;
        font-weight: 850;
    }

    .empty-state h3 {
        margin: 0;
        font-size: 1rem;
    }

    .empty-state p {
        margin: 8px auto 0;
        color: var(--muted);
        font-size: 0.76rem;
    }

    .pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 16px 22px;
        border-top: 1px solid var(--line-soft);
        color: var(--muted);
        font-size: 0.7rem;
    }

    .import-dialog {
        width: min(720px, calc(100% - 32px));
        max-height: calc(100vh - 32px);
        padding: 0;
        overflow: auto;
        border: 0;
        border-radius: 5px;
        color: var(--ink);
        background: #ffffff;
        box-shadow: 0 28px 80px rgba(7, 17, 31, 0.28);
    }

    .import-dialog::backdrop {
        background: rgba(7, 17, 31, 0.68);
        backdrop-filter: blur(3px);
    }

    .import-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 23px 24px 20px;
        border-bottom: 1px solid var(--line-soft);
    }

    .import-modal-header .page-description {
        margin-top: 7px;
        font-size: 0.76rem;
    }

    .modal-close {
        width: 38px;
        height: 38px;
        flex: 0 0 auto;
        border: 1px solid var(--line);
        border-radius: 3px;
        color: var(--muted);
        background: #ffffff;
        font-size: 1.2rem;
        line-height: 1;
        cursor: pointer;
    }

    .import-options {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        padding: 22px 24px 24px;
    }

    .import-option {
        min-width: 0;
        padding: 20px;
        border: 1px solid var(--line);
        background: #fbfcfd;
    }

    .import-option h3 {
        margin: 0;
        color: var(--navy-900);
        font-size: 0.9rem;
    }

    .import-option p {
        min-height: 52px;
        margin: 8px 0 17px;
        color: var(--muted);
        font-size: 0.7rem;
        line-height: 1.55;
    }

    .import-upload-form {
        display: grid;
        gap: 10px;
    }

    .import-upload-form .control {
        min-height: 43px;
        padding: 8px;
        background: #ffffff;
    }

    .import-upload-form .button,
    .import-option > .button {
        width: 100%;
    }

    .import-help {
        display: block;
        color: var(--muted-light);
        font-size: 0.62rem;
        line-height: 1.45;
    }

    @media (max-width: 1400px) {
        .metrics { grid-template-columns: repeat(3, minmax(160px, 1fr)); }
        .overview-grid { grid-template-columns: minmax(280px, 0.82fr) minmax(430px, 1.3fr); }
        .secondary-overview-grid { grid-template-columns: 1fr; }
        .filters { grid-template-columns: minmax(220px, 1.5fr) repeat(2, minmax(150px, 1fr)) auto; }
        .filter-secondary { display: none; }
    }

    @media (max-width: 880px) {
        .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .overview-grid { grid-template-columns: 1fr; }
        .branch-chart-row { grid-template-columns: minmax(105px, 0.65fr) minmax(180px, 1.7fr) 55px; }
        .health-strip { grid-template-columns: 1fr; }
        .filters { grid-template-columns: 1fr 1fr; }
        .filter-search { grid-column: 1 / -1; }
        .filter-actions { grid-column: 1 / -1; }
        .filter-actions .button { flex: 1; }
    }

    @media (max-width: 680px) {
        .metrics { gap: 10px; }
        .metric-card { padding: 16px; }
        .overview-panel { padding: 17px; }
        .availability-layout { grid-template-columns: 1fr; justify-items: center; }
        .availability-stats { width: 100%; }
        .branch-chart-row { grid-template-columns: minmax(88px, 0.72fr) minmax(105px, 1.3fr) 48px; gap: 8px; }
        .chart-branch-name span { display: none; }
        .secondary-overview-grid { grid-template-columns: 1fr; }
        .attention-panel .inventory-header { padding: 17px; }
        .attention-panel .table-wrap { overflow-x: auto; padding: 0; }
        .inventory-header { align-items: flex-start; flex-direction: column; }
        .filters { grid-template-columns: 1fr; padding: 14px; }
        .filter-search,
        .filter-actions { grid-column: auto; }
        .table-wrap { overflow: visible; padding: 0 14px 14px; }
        .inventory-table { min-width: 0; }
        .inventory-table thead { display: none; }
        .inventory-table,
        .inventory-table tbody,
        .inventory-table tr,
        .inventory-table td { display: block; width: 100%; }
        .inventory-table tbody tr {
            margin: 14px 0;
            padding: 14px;
            border: 1px solid var(--line-soft);
            border-left: 3px solid var(--blue-500);
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(7, 17, 31, 0.045);
        }
        .inventory-table tbody tr.needs-attention { border-left-color: var(--warning); }
        .inventory-table td {
            display: grid;
            grid-template-columns: 104px minmax(0, 1fr);
            gap: 14px;
            padding: 10px 0;
            border-bottom: 1px solid var(--line-soft);
        }
        .inventory-table td:last-child { border-bottom: 0; }
        .inventory-table td::before {
            content: attr(data-label);
            color: var(--muted-light);
            font-size: 0.59rem;
            font-weight: 850;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .summary-cell { max-width: none; }
        .pagination { align-items: stretch; flex-direction: column; }
        .import-options { grid-template-columns: 1fr; padding: 16px; }
        .import-option p { min-height: 0; }
        .import-modal-header { padding: 19px 16px 17px; }
    }
@endsection

@section('content')
    @php
        $onlineWidth = $summary['total'] > 0 ? ($summary['online'] / $summary['total']) * 100 : 0;
        $offlineWidth = $summary['total'] > 0 ? ($summary['offline'] / $summary['total']) * 100 : 0;
        $sortUrl = function (string $column): string {
            $nextDirection = request('sort') === $column && request('direction') === 'asc' ? 'desc' : 'asc';
            return request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]);
        };
    @endphp

    <div class="page-heading">
        <div>
            <p class="page-eyebrow">Operations dashboard</p>
            <h1 class="page-title">Network overview</h1>
            <p class="page-description">
                Live records imported from the Gateway branch CCTV monitoring workbook.
            </p>
        </div>

        <div class="button-row">
            <button class="button" id="openImportModal" type="button">Import XLS</button>
            <a class="button" href="{{ route('cctv-sites.export', request()->query()) }}">Export XLS</a>
            <a class="button button-primary" href="{{ route('cctv-sites.create') }}">Add branch</a>
        </div>
    </div>

    <dialog class="import-dialog" id="importModal" aria-labelledby="importModalTitle">
        <div class="import-modal-header">
            <div>
                <p class="panel-kicker">Inventory workbook</p>
                <h2 class="panel-title" id="importModalTitle">Import CCTV data</h2>
                <p class="page-description">Upload a completed workbook or download a clean copy of the approved Gateway template.</p>
            </div>
            <button class="modal-close" id="closeImportModal" type="button" aria-label="Close import dialog">&times;</button>
        </div>

        <div class="import-options">
            <section class="import-option" aria-labelledby="downloadTemplateTitle">
                <h3 id="downloadTemplateTitle">Get the template</h3>
                <p>Download the required workbook, fill in the inventory rows on Sheet1, and keep the column headings unchanged.</p>
                <a class="button" href="{{ route('cctv-sites.import-template') }}">Download template</a>
            </section>

            <section class="import-option" aria-labelledby="uploadWorkbookTitle">
                <h3 id="uploadWorkbookTitle">Upload a workbook</h3>
                <p>Select a completed Gateway template. Valid rows are added or updated using their worksheet and row number.</p>
                <form class="import-upload-form" id="inventoryImportForm" method="POST" action="{{ route('cctv-sites.import') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="sr-only" for="importFile">CCTV inventory workbook</label>
                    <input class="control" id="importFile" name="import_file" type="file" accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>
                    <span class="import-help">Accepted formats: .xls and .xlsx. Maximum size: 10 MB.</span>
                    @error('import_file')
                        <p class="field-error" id="importFileError" role="alert">{{ $message }}</p>
                    @enderror
                    <button class="button button-primary" id="submitImport" type="submit">Upload and import</button>
                </form>
            </section>
        </div>
    </dialog>

    <section class="metrics" aria-label="IT inventory summary">
        <article class="metric-card">
            <p class="metric-label">Monitored branches</p>
            <strong class="metric-value">{{ number_format($summary['branches']) }}</strong>
            <span class="metric-detail">{{ number_format($summary['records']) }} business-unit records</span>
        </article>

        <article class="metric-card">
            <p class="metric-label">Total cameras</p>
            <strong class="metric-value">{{ number_format($summary['total']) }}</strong>
            <span class="metric-detail">Across all business units</span>
        </article>

        <article class="metric-card metric-online">
            <p class="metric-label">Online cameras</p>
            <strong class="metric-value">{{ number_format($summary['online']) }}</strong>
            <span class="metric-detail">{{ number_format($summary['availability'], 1) }}% network availability</span>
        </article>

        <article class="metric-card metric-offline">
            <p class="metric-label">Offline cameras</p>
            <strong class="metric-value">{{ number_format($summary['offline']) }}</strong>
            <span class="metric-detail">Requires technical attention</span>
        </article>

        <article class="metric-card metric-warning">
            <p class="metric-label">Recording issues</p>
            <strong class="metric-value">{{ number_format($summary['issues']) }}</strong>
            <span class="metric-detail">Review recording integrity</span>
        </article>
    </section>

    @php
        $branchChart = $branchOverview->take(10);
        $regionChart = $regionOverview->take(8);
        $maxRegionTotal = max(1, (int) $regionChart->max('total'));
        $healthyBranchCount = $branchOverview->filter(
            fn (array $branch): bool => $branch['offline'] === 0
                && $branch['issues'] === 0
                && $branch['nvr_attention'] === 0
        )->count();
    @endphp  

    <section class="panel inventory-panel" id="inventory" aria-labelledby="inventory-title">
        <div class="inventory-header">
            <div>
                <p class="panel-kicker">Branch records</p>
                <h2 class="panel-title" id="inventory-title">IT site inventory</h2>
                <p class="result-count">Showing {{ $sites->firstItem() ?? 0 }}–{{ $sites->lastItem() ?? 0 }} of {{ $sites->total() }} database records</p>
            </div>
        </div>

        <form class="filters" id="inventoryFilters" method="GET" action="{{ route('dashboard') }}#inventory" role="search">
            @if (request()->filled('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
            @endif

            <div class="filter-search">
                <label class="sr-only" for="q">Search sites</label>
                <input class="control" id="q" name="q" type="search" value="{{ request('q') }}" placeholder="Search branch, tech, NVR..." autocomplete="off">
            </div>

            <select class="control" id="regionFilter" name="region" aria-label="Filter by region">
                <option value="">All regions</option>
                @foreach ($regions as $region)
                    <option value="{{ $region }}" @selected(request('region') === $region)>{{ $region }}</option>
                @endforeach
            </select>

            <select class="control" id="businessUnitFilter" name="business_unit" aria-label="Filter by business unit">
                <option value="">All business units</option>
                @foreach ($businessUnits as $businessUnit)
                    <option value="{{ $businessUnit }}" @selected(request('business_unit') === $businessUnit)>{{ $businessUnit }}</option>
                @endforeach
            </select>

            <select class="control filter-secondary" id="provinceFilter" name="province" aria-label="Filter by province">
                <option value="">All provinces</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province }}" @selected(request('province') === $province)>{{ $province }}</option>
                @endforeach
            </select>

            <select class="control filter-secondary" id="healthFilter" name="health" aria-label="Filter by health">
                <option value="">All health states</option>
                <option value="healthy" @selected(request('health') === 'healthy')>Healthy</option>
                <option value="offline" @selected(request('health') === 'offline')>Has offline cameras</option>
                <option value="recording" @selected(request('health') === 'recording')>Has recording issues</option>
                <option value="nvr" @selected(request('health') === 'nvr')>NVR attention</option>
            </select>

            <div class="filter-actions">
                <button class="button" id="applyFilters" type="submit">Apply</button>
                <a class="button" id="resetFilters" href="{{ route('dashboard') }}#inventory">Reset</a>
            </div>

            <span class="sr-only" id="filterStatus" role="status" aria-live="polite"></span>
        </form>

        @if ($sites->isEmpty())
            <div class="empty-state">
                <div class="empty-mark" aria-hidden="true">SI</div>
                <h3>No matching sites</h3>
                <p>Adjust your filters or add a new branch record.</p>
                <a class="button button-primary" style="margin-top:18px;" href="{{ route('cctv-sites.create') }}">Add branch</a>
            </div>
        @else
            <div class="table-wrap">
                <table class="inventory-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><a class="sort-link" href="{{ $sortUrl('branch') }}">Branch / unit</a></th>
                            <th><a class="sort-link" href="{{ $sortUrl('region') }}">Location</a></th>
                            <th>Assigned tech</th>
                            <th><a class="sort-link" href="{{ $sortUrl('total_cameras') }}">Cameras</a></th>
                            <th>NVR status</th>
                            <th><a class="sort-link" href="{{ $sortUrl('nvr_hdd_capacity_gb') }}">Storage / retention</a></th>
                            <th>Distribution</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sites as $site)
                            <tr class="{{ $site->requires_attention ? 'needs-attention' : '' }}">
                                <td class="id-cell" data-label="ID">
                                    {{ $site->source_sheet ?: 'Manual' }}-{{ $site->source_id ?: $site->id }}
                                    @if ($site->source_row)
                                        <span class="cell-secondary">Row {{ $site->source_row }}</span>
                                    @endif
                                </td>
                                <td data-label="Branch / unit">
                                    <span class="cell-primary">{{ $site->branch }}</span>
                                    <span class="cell-secondary">{{ $site->business_unit }}</span>
                                </td>
                                <td data-label="Location">
                                    <span class="cell-primary">{{ $site->province }}</span>
                                    <span class="cell-secondary">{{ $site->region }}</span>
                                </td>
                                <td data-label="Assigned tech">
                                    <span class="cell-primary">{{ $site->assigned_tech ?: 'Unassigned' }}</span>
                                </td>
                                <td data-label="Cameras">
                                    <span class="camera-total">{{ number_format($site->total_cameras) }} total</span>
                                    <span class="camera-chips">
                                        <span class="camera-chip">{{ $site->online_cameras }} ON</span>
                                        <span class="camera-chip offline">{{ $site->offline_cameras }} OFF</span>
                                        <span class="camera-chip issue">{{ $site->recording_issue_cameras }} REC</span>
                                    </span>
                                </td>
                                <td data-label="NVR status">
                                    <span class="badge {{ $site->nvr_is_healthy ? 'badge-success' : ($site->nvr_status ? 'badge-warning' : '') }}">
                                        {{ $site->nvr_status_label }}
                                    </span>
                                    <span class="cell-secondary">{{ collect([$site->nvr_brand, $site->nvr_model])->filter()->join(' · ') ?: 'Recorder details not reported' }}</span>
                                    <span class="cell-secondary">Vendor: {{ $site->vendor ?: 'Not reported' }}</span>
                                </td>
                                <td class="storage-cell" data-label="Storage">
                                    <span class="storage-numbers">
                                        <span>{{ $site->storage_status ?: 'Status not reported' }}</span>
                                        <span>{{ $site->storage_capacity_label }}</span>
                                    </span>
                                    <span class="cell-secondary">Retention: {{ $site->recording_days ?: 'Not reported' }}</span>
                                </td>
                                <td class="summary-cell" data-label="Distribution">
                                    @if ($site->distribution_status)
                                        <span class="badge">{{ $site->distribution_status }}</span>
                                    @endif
                                    <span class="cell-secondary truncate">{{ $site->distribution_summary ?: 'No distribution summary' }}</span>
                                    @if ($site->remarks)
                                        <span class="remarks-flag">Remarks available</span>
                                    @endif
                                </td>
                                <td data-label="Actions">
                                    <div class="row-actions">
                                        <a class="button button-small" href="{{ route('cctv-sites.show', $site) }}">View</a>
                                        <a class="button button-small" href="{{ route('cctv-sites.edit', $site) }}">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($sites->hasPages())
                <nav class="pagination" aria-label="Site inventory pagination">
                    <span>Page {{ $sites->currentPage() }} of {{ $sites->lastPage() }}</span>
                    <div class="button-row">
                        @if ($sites->onFirstPage())
                            <span class="button button-small" aria-disabled="true">Previous</span>
                        @else
                            <a class="button button-small" href="{{ $sites->previousPageUrl() }}">Previous</a>
                        @endif

                        @if ($sites->hasMorePages())
                            <a class="button button-small" href="{{ $sites->nextPageUrl() }}">Next</a>
                        @else
                            <span class="button button-small" aria-disabled="true">Next</span>
                        @endif
                    </div>
                </nav>
            @endif
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('inventoryFilters');

            if (!form) return;

            const search = document.getElementById('q');
            const applyButton = document.getElementById('applyFilters');
            const resetButton = document.getElementById('resetFilters');
            const status = document.getElementById('filterStatus');
            const selects = [
                document.getElementById('regionFilter'),
                document.getElementById('businessUnitFilter'),
                document.getElementById('provinceFilter'),
                document.getElementById('healthFilter'),
            ].filter(Boolean);
            let searchTimer;
            let composing = false;

            function submitFilters() {
                if (form.classList.contains('is-loading')) return;

                form.requestSubmit();
            }

            form.addEventListener('submit', function () {
                window.clearTimeout(searchTimer);
                form.classList.add('is-loading');
                form.setAttribute('aria-busy', 'true');

                if (applyButton) {
                    applyButton.disabled = true;
                    applyButton.textContent = 'Filtering…';
                }

                if (status) status.textContent = 'Filtering inventory records.';
            });

            selects.forEach(function (select) {
                select.addEventListener('change', submitFilters);
            });

            if (search) {
                search.addEventListener('compositionstart', function () {
                    composing = true;
                });

                search.addEventListener('compositionend', function () {
                    composing = false;
                    submitFilters();
                });

                search.addEventListener('input', function () {
                    if (composing) return;

                    window.clearTimeout(searchTimer);
                    searchTimer = window.setTimeout(submitFilters, 650);
                });

                search.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape' && search.value !== '') {
                        event.preventDefault();
                        search.value = '';
                        submitFilters();
                    }
                });
            }

            if (resetButton) {
                resetButton.addEventListener('click', function (event) {
                    event.preventDefault();
                    window.clearTimeout(searchTimer);
                    form.reset();
                    window.location.assign(resetButton.href);
                });
            }
        })();
    </script>

    <script>
        (function () {
            const dialog = document.getElementById('importModal');
            const openButton = document.getElementById('openImportModal');
            const closeButton = document.getElementById('closeImportModal');
            const importForm = document.getElementById('inventoryImportForm');
            const submitButton = document.getElementById('submitImport');

            if (!dialog || !openButton || !closeButton) return;

            function openDialog() {
                if (!dialog.open) dialog.showModal();
            }

            function closeDialog() {
                if (dialog.open) dialog.close();
            }

            openButton.addEventListener('click', openDialog);
            closeButton.addEventListener('click', closeDialog);

            dialog.addEventListener('click', function (event) {
                const bounds = dialog.getBoundingClientRect();
                const outside = event.clientX < bounds.left
                    || event.clientX > bounds.right
                    || event.clientY < bounds.top
                    || event.clientY > bounds.bottom;

                if (outside) closeDialog();
            });

            if (importForm) {
                importForm.addEventListener('submit', function () {
                    if (!submitButton) return;

                    submitButton.disabled = true;
                    submitButton.textContent = 'Importing…';
                    importForm.setAttribute('aria-busy', 'true');
                });
            }

            @if ($errors->has('import_file'))
                openDialog();
            @endif
        })();
    </script>
@endpush
