<div class="asset-form-grid">
    <h2 class="asset-section-heading">Asset identity</h2>

    <div class="asset-field">
        <label class="field-label" for="asset_tag">Asset tag</label>
        <input class="control" id="asset_tag" name="asset_tag" type="text" value="{{ old('asset_tag', $itAsset->asset_tag) }}" maxlength="150" placeholder="e.g. GM-LAP-001">
        @error('asset_tag') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="asset_name">Asset name</label>
        <input class="control" id="asset_name" name="asset_name" type="text" value="{{ old('asset_name', $itAsset->asset_name) }}" maxlength="255" placeholder="e.g. Dell Latitude 5440">
        @error('asset_name') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="category">Category <span class="required">*</span></label>
        <input class="control" id="category" name="category" type="text" value="{{ old('category', $itAsset->category) }}" maxlength="100" placeholder="Laptop, Desktop, Monitor..." required>
        @error('category') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="status">Status</label>
        <input class="control" id="status" name="status" type="text" value="{{ old('status', $itAsset->status) }}" maxlength="100" placeholder="Assigned, Stock, For Repair...">
        @error('status') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field span-2">
        <label class="field-label" for="condition">Condition</label>
        <input class="control" id="condition" name="condition" type="text" value="{{ old('condition', $itAsset->condition) }}" maxlength="150" placeholder="Good, Damage, Not working...">
        @error('condition') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <h2 class="asset-section-heading">Assignment and location</h2>

    <div class="asset-field">
        <label class="field-label" for="branch">Branch</label>
        <input class="control" id="branch" name="branch" type="text" value="{{ old('branch', $itAsset->branch) }}" maxlength="150" placeholder="Gateway branch">
        @error('branch') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="location">Location</label>
        <input class="control" id="location" name="location" type="text" value="{{ old('location', $itAsset->location) }}" maxlength="190" placeholder="IT room, office, department area...">
        @error('location') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="assigned_user">Assigned user</label>
        <input class="control" id="assigned_user" name="assigned_user" type="text" value="{{ old('assigned_user', $itAsset->assigned_user) }}" maxlength="150" placeholder="Employee or role">
        @error('assigned_user') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="department">Department</label>
        <input class="control" id="department" name="department" type="text" value="{{ old('department', $itAsset->department) }}" maxlength="150" placeholder="IT, Finance, Service...">
        @error('department') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <h2 class="asset-section-heading">Equipment and network details</h2>

    <div class="asset-field">
        <label class="field-label" for="brand">Brand</label>
        <input class="control" id="brand" name="brand" type="text" value="{{ old('brand', $itAsset->brand) }}" maxlength="120" placeholder="Dell, HP, Lenovo...">
        @error('brand') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="model">Model</label>
        <input class="control" id="model" name="model" type="text" value="{{ old('model', $itAsset->model) }}" maxlength="190" placeholder="Model name or number">
        @error('model') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field span-2">
        <label class="field-label" for="serial_number">Serial number</label>
        <input class="control asset-code" id="serial_number" name="serial_number" type="text" value="{{ old('serial_number', $itAsset->serial_number) }}" maxlength="190" placeholder="Manufacturer serial number">
        @error('serial_number') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="ip_address">IP address</label>
        <input class="control asset-code" id="ip_address" name="ip_address" type="text" value="{{ old('ip_address', $itAsset->ip_address) }}" maxlength="45" placeholder="192.168.1.100">
        @error('ip_address') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="mac_address">MAC address</label>
        <input class="control asset-code" id="mac_address" name="mac_address" type="text" value="{{ old('mac_address', $itAsset->mac_address) }}" maxlength="50" placeholder="AA:BB:CC:DD:EE:FF">
        @error('mac_address') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <h2 class="asset-section-heading">Procurement, warranty, and notes</h2>

    <div class="asset-field">
        <label class="field-label" for="purchase_date">Purchase date</label>
        <input class="control" id="purchase_date" name="purchase_date" type="text" value="{{ old('purchase_date', $itAsset->purchase_date) }}" maxlength="50" placeholder="YYYY-MM-DD or source value">
        @error('purchase_date') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="supplier">Supplier</label>
        <input class="control" id="supplier" name="supplier" type="text" value="{{ old('supplier', $itAsset->supplier) }}" maxlength="190" placeholder="Supplier or vendor">
        @error('supplier') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="warranty_start">Warranty start</label>
        <input class="control" id="warranty_start" name="warranty_start" type="text" value="{{ old('warranty_start', $itAsset->warranty_start) }}" maxlength="50" placeholder="YYYY-MM-DD or source value">
        @error('warranty_start') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field">
        <label class="field-label" for="warranty_end">Warranty end</label>
        <input class="control" id="warranty_end" name="warranty_end" type="text" value="{{ old('warranty_end', $itAsset->warranty_end) }}" maxlength="50" placeholder="YYYY-MM-DD or source value">
        @error('warranty_end') <p class="field-error">{{ $message }}</p> @enderror
    </div>

    <div class="asset-field span-2">
        <label class="field-label" for="remarks">Remarks</label>
        <textarea class="control" id="remarks" name="remarks" maxlength="5000" placeholder="Condition details, repair notes, or other context.">{{ old('remarks', $itAsset->remarks) }}</textarea>
        @error('remarks') <p class="field-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="asset-form-actions">
    <a class="button" href="{{ $itAsset->exists ? route('it-assets.show', $itAsset) : route('it-assets.index') }}">Cancel</a>
    <button class="button button-primary" type="submit">{{ $submitLabel }}</button>
</div>
