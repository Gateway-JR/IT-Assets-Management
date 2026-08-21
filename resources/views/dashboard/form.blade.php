@extends('layouts.dashboard')

@php($editing = $site->exists)

@section('title', $editing ? 'Edit '.$site->branch : 'Add Inventory Site')
@section('topbar-title', $editing ? 'Edit Site Record' : 'Add Inventory Site')

@section('styles')
    .form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 18px;
        align-items: start;
    }

    .form-card {
        overflow: hidden;
    }

    .form-section {
        padding: 26px;
        border-bottom: 1px solid var(--line-soft);
    }

    .form-section:last-child {
        border-bottom: 0;
    }

    .section-heading {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        margin-bottom: 22px;
    }

    .section-number {
        width: 28px;
        height: 28px;
        display: grid;
        place-items: center;
        flex: 0 0 auto;
        color: #ffffff;
        background: var(--navy-800);
        font-size: 0.61rem;
        font-weight: 850;
    }

    .section-heading h2 {
        margin: 0;
        color: var(--ink);
        font-size: 0.95rem;
        font-weight: 780;
    }

    .section-heading p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 0.69rem;
        line-height: 1.45;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .form-grid.cameras {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .field-wide {
        grid-column: 1 / -1;
    }

    .required::after {
        content: " *";
        color: var(--danger);
    }

    .field-help {
        margin: 6px 0 0;
        color: var(--muted-light);
        font-size: 0.64rem;
        line-height: 1.4;
    }

    .allocation {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 12px 14px;
        border-left: 3px solid var(--blue-500);
        color: var(--accent-text);
        background: var(--blue-100);
        font-size: 0.7rem;
    }

    .allocation strong {
        color: var(--ink);
    }

    .allocation.invalid {
        border-left-color: var(--danger);
        color: var(--danger);
        background: var(--danger-soft);
    }

    .validation-summary {
        margin-bottom: 18px;
        padding: 16px 18px;
        border-left: 3px solid var(--danger);
        color: var(--danger);
        background: var(--danger-soft);
        font-size: 0.75rem;
        line-height: 1.55;
    }

    .validation-summary strong {
        display: block;
        margin-bottom: 5px;
    }

    .validation-summary ul {
        margin: 0;
        padding-left: 18px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 19px 26px;
        border-top: 1px solid var(--line-soft);
        background: var(--surface-raised);
    }

    .context-card {
        position: sticky;
        top: 98px;
        padding: 22px;
    }

    .context-title {
        margin: 0;
        color: var(--ink);
        font-size: 0.88rem;
        font-weight: 780;
    }

    .context-copy {
        margin: 9px 0 0;
        color: var(--muted);
        font-size: 0.7rem;
        line-height: 1.55;
    }

    .context-list {
        margin: 20px 0 0;
        padding: 0;
        list-style: none;
    }

    .context-list li {
        position: relative;
        margin-top: 13px;
        padding-left: 17px;
        color: var(--muted);
        font-size: 0.68rem;
        line-height: 1.45;
    }

    .context-list li::before {
        content: "";
        position: absolute;
        left: 0;
        top: 6px;
        width: 6px;
        height: 6px;
        background: var(--blue-500);
    }

    @media (max-width: 980px) {
        .form-layout { grid-template-columns: 1fr; }
        .context-card { position: static; }
    }

    @media (max-width: 700px) {
        .form-grid,
        .form-grid.cameras { grid-template-columns: 1fr; }
        .field-wide,
        .allocation { grid-column: auto; }
        .form-section { padding: 21px 18px; }
        .form-actions { padding: 16px 18px; }
        .form-actions .button { flex: 1; }
    }
@endsection

@section('content')
    <div class="page-heading">
        <div>
            <p class="page-eyebrow">{{ $editing ? 'Update record' : 'New branch record' }}</p>
            <h1 class="page-title">{{ $editing ? $site->branch : 'Add inventory site' }}</h1>
            <p class="page-description">
                {{ $editing ? 'Keep the site inventory and current operational status accurate.' : 'Register the branch, camera allocation, NVR equipment, and distribution coverage.' }}
            </p>
        </div>
        <div class="button-row">
            <a class="button" href="{{ $editing ? route('cctv-sites.show', $site) : route('dashboard') }}">Cancel</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="validation-summary" role="alert">
            <strong>Please review the highlighted information.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $editing ? route('cctv-sites.update', $site) : route('cctv-sites.store') }}">
        @csrf
        @if ($editing)
            @method('PUT')
        @endif

        <div class="form-layout">
            <div class="panel form-card">
                <section class="form-section" aria-labelledby="branch-section">
                    <div class="section-heading">
                        <span class="section-number" aria-hidden="true">01</span>
                        <div>
                            <h2 id="branch-section">Branch details</h2>
                            <p>Core location, business unit, and technical ownership.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="field-label required" for="branch">Branch</label>
                            <input class="control" id="branch" name="branch" type="text" value="{{ old('branch', $site->branch) }}" maxlength="255" required>
                            @error('branch')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="business_unit">Business unit</label>
                            <input class="control" id="business_unit" name="business_unit" type="text" value="{{ old('business_unit', $site->business_unit) }}" maxlength="255" placeholder="e.g. Honda">
                            @error('business_unit')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="region">Region</label>
                            <input class="control" id="region" name="region" type="text" value="{{ old('region', $site->region) }}" maxlength="255" placeholder="e.g. NCR">
                            @error('region')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="province">Province</label>
                            <input class="control" id="province" name="province" type="text" value="{{ old('province', $site->province) }}" maxlength="255">
                            @error('province')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="field-wide">
                            <label class="field-label" for="assigned_tech">Assigned technician</label>
                            <input class="control" id="assigned_tech" name="assigned_tech" type="text" value="{{ old('assigned_tech', $site->assigned_tech) }}" maxlength="255" placeholder="Technician name">
                            @error('assigned_tech')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="camera-section">
                    <div class="section-heading">
                        <span class="section-number" aria-hidden="true">02</span>
                        <div>
                            <h2 id="camera-section">Device inventory</h2>
                            <p>Online and offline counts make up the total. Recording issues may overlap with offline cameras, matching the workbook.</p>
                        </div>
                    </div>

                    <div class="form-grid cameras">
                        <div>
                            <label class="field-label required" for="total_cameras">Total cameras</label>
                            <input class="control camera-count" id="total_cameras" name="total_cameras" type="number" value="{{ old('total_cameras', $site->total_cameras ?? 0) }}" min="0" max="65535" required>
                            @error('total_cameras')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label required" for="online_cameras">Online</label>
                            <input class="control camera-count" id="online_cameras" name="online_cameras" type="number" value="{{ old('online_cameras', $site->online_cameras ?? 0) }}" min="0" max="65535" required>
                            @error('online_cameras')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label required" for="offline_cameras">Offline</label>
                            <input class="control camera-count" id="offline_cameras" name="offline_cameras" type="number" value="{{ old('offline_cameras', $site->offline_cameras ?? 0) }}" min="0" max="65535" required>
                            @error('offline_cameras')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label required" for="recording_issue_cameras">Recording issue</label>
                            <input class="control camera-count" id="recording_issue_cameras" name="recording_issue_cameras" type="number" value="{{ old('recording_issue_cameras', $site->recording_issue_cameras ?? 0) }}" min="0" max="65535" required>
                            @error('recording_issue_cameras')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="allocation" id="allocationStatus" role="status">
                            <span>Allocated camera statuses</span>
                            <strong id="allocationValue">0 of 0</strong>
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="nvr-section">
                    <div class="section-heading">
                        <span class="section-number" aria-hidden="true">03</span>
                        <div>
                            <h2 id="nvr-section">NVR and storage</h2>
                            <p>Recorder health, supplier information, equipment identity, and capacity in gigabytes.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div>
                            <label class="field-label" for="nvr_status">NVR status</label>
                            <input class="control" id="nvr_status" name="nvr_status" type="text" value="{{ old('nvr_status', $site->nvr_status) }}" maxlength="100" placeholder="e.g. Good">
                            @error('nvr_status')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="vendor">Vendor</label>
                            <input class="control" id="vendor" name="vendor" type="text" value="{{ old('vendor', $site->vendor) }}" maxlength="255">
                            @error('vendor')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="nvr_brand">NVR brand</label>
                            <input class="control" id="nvr_brand" name="nvr_brand" type="text" value="{{ old('nvr_brand', $site->nvr_brand) }}" maxlength="255">
                            @error('nvr_brand')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="nvr_model">NVR model</label>
                            <input class="control" id="nvr_model" name="nvr_model" type="text" value="{{ old('nvr_model', $site->nvr_model) }}" maxlength="255">
                            @error('nvr_model')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="storage_status">Storage status</label>
                            <input class="control" id="storage_status" name="storage_status" type="text" value="{{ old('storage_status', $site->storage_status) }}" maxlength="100" placeholder="e.g. Full/Overwrite">
                            @error('storage_status')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="nvr_hdd_capacity_gb">HDD capacity (GB)</label>
                            <input class="control" id="nvr_hdd_capacity_gb" name="nvr_hdd_capacity_gb" type="number" value="{{ old('nvr_hdd_capacity_gb', $site->nvr_hdd_capacity_gb) }}" min="0" max="100000000">
                            @error('nvr_hdd_capacity_gb')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="nvr_hdd_capacity">HDD capacity label</label>
                            <input class="control" id="nvr_hdd_capacity" name="nvr_hdd_capacity" type="text" value="{{ old('nvr_hdd_capacity', $site->nvr_hdd_capacity) }}" maxlength="100" placeholder="e.g. 12TB">
                            @error('nvr_hdd_capacity')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="recording_days">Recording days</label>
                            <input class="control" id="recording_days" name="recording_days" type="text" value="{{ old('recording_days', $site->recording_days) }}" maxlength="100" placeholder="e.g. 30 Days">
                            @error('recording_days')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="field-wide">
                            <label class="field-label" for="nvr_rlp">NVR RLP</label>
                            <input class="control" id="nvr_rlp" name="nvr_rlp" type="text" value="{{ old('nvr_rlp', $site->nvr_rlp) }}" maxlength="150">
                            @error('nvr_rlp')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <section class="form-section" aria-labelledby="distribution-section">
                    <div class="section-heading">
                        <span class="section-number" aria-hidden="true">04</span>
                        <div>
                            <h2 id="distribution-section">Distribution</h2>
                            <p>Coverage status, installation summary, and operational remarks.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="field-wide">
                            <label class="field-label" for="distribution_status">Distribution status</label>
                            <input class="control" id="distribution_status" name="distribution_status" type="text" value="{{ old('distribution_status', $site->distribution_status) }}" maxlength="100">
                            @error('distribution_status')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="distribution_summary">Distribution summary</label>
                            <textarea class="control" id="distribution_summary" name="distribution_summary" maxlength="2000" placeholder="Summarize camera coverage and distribution across the site.">{{ old('distribution_summary', $site->distribution_summary) }}</textarea>
                            @error('distribution_summary')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label" for="remarks">Remarks</label>
                            <textarea class="control" id="remarks" name="remarks" maxlength="5000" placeholder="Add maintenance notes, blockers, or follow-up actions.">{{ old('remarks', $site->remarks) }}</textarea>
                            @error('remarks')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </section>

                <div class="form-actions">
                    <a class="button" href="{{ $editing ? route('cctv-sites.show', $site) : route('dashboard') }}">Cancel</a>
                    <button class="button button-primary" type="submit">{{ $editing ? 'Save changes' : 'Add inventory site' }}</button>
                </div>
            </div>

            <aside class="panel context-card" aria-label="Data guidance">
                <p class="panel-kicker">Record guidance</p>
                <h2 class="context-title">Keep the dashboard reliable</h2>
                <p class="context-copy">Accurate operational data makes the summary cards and attention filters meaningful.</p>
                <ul class="context-list">
                    <li>Keep the capacity label exactly as reported in the workbook.</li>
                    <li>Online and offline camera counts must add up to the total.</li>
                    <li>Use remarks for actionable maintenance context.</li>
                    <li>Update NVR status whenever equipment health changes.</li>
                </ul>
            </aside>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        (function () {
            const total = document.getElementById('total_cameras');
            const online = document.getElementById('online_cameras');
            const offline = document.getElementById('offline_cameras');
            const status = document.getElementById('allocationStatus');
            const value = document.getElementById('allocationValue');

            if (!total || !online || !offline || !status || !value) return;

            function updateAllocation() {
                const expected = Number(total.value || 0);
                const allocated = Number(online.value || 0) + Number(offline.value || 0);
                const valid = allocated === expected;

                value.textContent = allocated + ' of ' + expected;
                status.classList.toggle('invalid', !valid);
            }

            [total, online, offline].forEach(function (input) {
                input.addEventListener('input', updateAllocation);
            });

            updateAllocation();
        })();
    </script>
@endpush
