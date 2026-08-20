@php
    $site = $cctvSite ?? null;
@endphp

<div class="card-body">
    <div class="form-grid">
        <h3 class="section-heading">Branch Information</h3>

        <div class="field">
            <label for="branch">Branch <span class="required">*</span></label>
            <input
                class="@error('branch') input-error @enderror"
                id="branch"
                name="branch"
                type="text"
                value="{{ old('branch', $site?->branch) }}"
                placeholder="e.g. Gateway Cubao"
                maxlength="150"
                required
            >
            @error('branch') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="business_unit">Business Unit</label>
            <input
                class="@error('business_unit') input-error @enderror"
                id="business_unit"
                name="business_unit"
                type="text"
                value="{{ old('business_unit', $site?->business_unit) }}"
                placeholder="e.g. Retail Operations"
                maxlength="120"
            >
            @error('business_unit') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="region">Region <span class="required">*</span></label>
            <input
                class="@error('region') input-error @enderror"
                id="region"
                name="region"
                type="text"
                value="{{ old('region', $site?->region) }}"
                placeholder="e.g. NCR"
                maxlength="100"
                required
            >
            @error('region') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="province">Province <span class="required">*</span></label>
            <input
                class="@error('province') input-error @enderror"
                id="province"
                name="province"
                type="text"
                value="{{ old('province', $site?->province) }}"
                placeholder="e.g. Metro Manila"
                maxlength="100"
                required
            >
            @error('province') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field span-2">
            <label for="assigned_tech">Assigned Technician</label>
            <input
                class="@error('assigned_tech') input-error @enderror"
                id="assigned_tech"
                name="assigned_tech"
                type="text"
                value="{{ old('assigned_tech', $site?->assigned_tech) }}"
                placeholder="Technician or support team name"
                maxlength="150"
            >
            @error('assigned_tech') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <h3 class="section-heading">Camera Status</h3>

        <div class="field">
            <label for="total_cameras">Total Cameras <span class="required">*</span></label>
            <input
                class="camera-count @error('total_cameras') input-error @enderror"
                id="total_cameras"
                name="total_cameras"
                type="number"
                value="{{ old('total_cameras', $site?->total_cameras ?? 0) }}"
                min="0"
                max="10000"
                required
            >
            @error('total_cameras') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="online_cameras">Online <span class="required">*</span></label>
            <input
                class="camera-count @error('online_cameras') input-error @enderror"
                id="online_cameras"
                name="online_cameras"
                type="number"
                value="{{ old('online_cameras', $site?->online_cameras ?? 0) }}"
                min="0"
                max="10000"
                required
            >
            @error('online_cameras') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="offline_cameras">Offline <span class="required">*</span></label>
            <input
                class="camera-count @error('offline_cameras') input-error @enderror"
                id="offline_cameras"
                name="offline_cameras"
                type="number"
                value="{{ old('offline_cameras', $site?->offline_cameras ?? 0) }}"
                min="0"
                max="10000"
                required
            >
            @error('offline_cameras') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="recording_issue_cameras">Recording Issues <span class="required">*</span></label>
            <input
                class="camera-count @error('recording_issue_cameras') input-error @enderror"
                id="recording_issue_cameras"
                name="recording_issue_cameras"
                type="number"
                value="{{ old('recording_issue_cameras', $site?->recording_issue_cameras ?? 0) }}"
                min="0"
                max="10000"
                required
            >
            @error('recording_issue_cameras') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="count-preview" id="countPreview" aria-live="polite">
            <span>Accounted: <strong id="accountedCameras">0</strong></span>
            <span>Unclassified: <strong id="unclassifiedCameras">0</strong></span>
        </div>

        <h3 class="section-heading">NVR and Storage</h3>

        <div class="field">
            <label for="nvr_status">NVR Status <span class="required">*</span></label>
            <select class="@error('nvr_status') input-error @enderror" id="nvr_status" name="nvr_status" required>
                @foreach (['Operational', 'Offline', 'Maintenance', 'Unknown'] as $status)
                    <option value="{{ $status }}" @selected(old('nvr_status', $site?->nvr_status ?? 'Unknown') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            @error('nvr_status') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="vendor">Vendor</label>
            <input
                class="@error('vendor') input-error @enderror"
                id="vendor"
                name="vendor"
                type="text"
                value="{{ old('vendor', $site?->vendor) }}"
                placeholder="Vendor or service provider"
                maxlength="120"
            >
            @error('vendor') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="nvr_brand">NVR Brand</label>
            <input
                class="@error('nvr_brand') input-error @enderror"
                id="nvr_brand"
                name="nvr_brand"
                type="text"
                value="{{ old('nvr_brand', $site?->nvr_brand) }}"
                placeholder="e.g. Hikvision"
                maxlength="120"
            >
            @error('nvr_brand') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="nvr_model">NVR Model</label>
            <input
                class="@error('nvr_model') input-error @enderror"
                id="nvr_model"
                name="nvr_model"
                type="text"
                value="{{ old('nvr_model', $site?->nvr_model) }}"
                placeholder="Model number"
                maxlength="120"
            >
            @error('nvr_model') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="storage_used_gb">Storage Used (GB)</label>
            <input
                class="@error('storage_used_gb') input-error @enderror"
                id="storage_used_gb"
                name="storage_used_gb"
                type="number"
                value="{{ old('storage_used_gb', $site?->storage_used_gb) }}"
                placeholder="0.00"
                min="0"
                step="0.01"
            >
            @error('storage_used_gb') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="hdd_capacity_gb">NVR HDD Capacity (GB)</label>
            <input
                class="@error('hdd_capacity_gb') input-error @enderror"
                id="hdd_capacity_gb"
                name="hdd_capacity_gb"
                type="number"
                value="{{ old('hdd_capacity_gb', $site?->hdd_capacity_gb) }}"
                placeholder="e.g. 4096"
                min="0"
                step="0.01"
            >
            <p class="field-help">For reference: 1 TB = 1,024 GB.</p>
            @error('hdd_capacity_gb') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <h3 class="section-heading">Distribution and Notes</h3>

        <div class="field span-2">
            <label for="distribution">Distribution</label>
            <input
                class="@error('distribution') input-error @enderror"
                id="distribution"
                name="distribution"
                type="text"
                value="{{ old('distribution', $site?->distribution) }}"
                placeholder="e.g. 8 indoor, 4 outdoor, 2 entrances"
                maxlength="255"
            >
            @error('distribution') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="distribution_summary">Distribution Summary</label>
            <textarea
                class="@error('distribution_summary') input-error @enderror"
                id="distribution_summary"
                name="distribution_summary"
                placeholder="Describe how cameras are distributed across the site."
                maxlength="3000"
            >{{ old('distribution_summary', $site?->distribution_summary) }}</textarea>
            @error('distribution_summary') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label for="remarks">Remarks</label>
            <textarea
                class="@error('remarks') input-error @enderror"
                id="remarks"
                name="remarks"
                placeholder="Maintenance notes, issues, or follow-up actions."
                maxlength="3000"
            >{{ old('remarks', $site?->remarks) }}</textarea>
            @error('remarks') <p class="field-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>

<div class="form-actions">
    <a class="button button-secondary" href="{{ route('admin.cctv-sites.index') }}">Cancel</a>
    <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
</div>

@push('scripts')
    <script>
        (function () {
            const total = document.getElementById('total_cameras');
            const online = document.getElementById('online_cameras');
            const offline = document.getElementById('offline_cameras');
            const issues = document.getElementById('recording_issue_cameras');
            const accountedOutput = document.getElementById('accountedCameras');
            const unclassifiedOutput = document.getElementById('unclassifiedCameras');
            const preview = document.getElementById('countPreview');

            if (!total || !online || !offline || !issues) return;

            function numericValue(input) {
                return Math.max(0, Number.parseInt(input.value || '0', 10) || 0);
            }

            function refreshCameraCount() {
                const totalCount = numericValue(total);
                const accounted = numericValue(online) + numericValue(offline) + numericValue(issues);
                const unclassified = totalCount - accounted;

                accountedOutput.textContent = accounted;
                unclassifiedOutput.textContent = unclassified;
                preview.style.borderLeftColor = unclassified < 0 ? '#b42318' : '#1c7ed6';
                unclassifiedOutput.style.color = unclassified < 0 ? '#b42318' : '#0a1728';
            }

            [total, online, offline, issues].forEach(function (input) {
                input.addEventListener('input', refreshCameraCount);
            });

            refreshCameraCount();
        })();
    </script>
@endpush
