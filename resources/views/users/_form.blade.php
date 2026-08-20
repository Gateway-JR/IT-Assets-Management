<div class="form-section">
    <h2 class="form-section-title">Account details</h2>
    <p class="form-section-copy">The email address and password are the credentials this user will enter on the login page.</p>

    <div class="form-grid">
        <div>
            <label class="field-label" for="name">Full name</label>
            <input class="control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}" maxlength="255" autocomplete="name" required autofocus>
            @error('name')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="field-label" for="email">Email address</label>
            <input class="control" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required>
            @error('email')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="form-section">
    <h2 class="form-section-title">Login password</h2>
    <p class="form-section-copy">{{ $user->exists ? 'Leave both password fields blank to keep the current password.' : 'Use at least 8 characters and share it with the user securely.' }}</p>

    <div class="form-grid">
        <div>
            <label class="field-label" for="password">{{ $user->exists ? 'New password' : 'Password' }}</label>
            <input class="control" id="password" name="password" type="password" autocomplete="new-password" {{ $user->exists ? '' : 'required' }}>
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="field-label" for="password_confirmation">Confirm password</label>
            <input class="control" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" {{ $user->exists ? '' : 'required' }}>
        </div>
    </div>
</div>

<div class="form-section">
    <h2 class="form-section-title">Access level</h2>
    <p class="form-section-copy">Administrators can manage user accounts. Standard users can access the CCTV inventory but cannot open User Management.</p>

    <div class="form-grid">
        <div class="field-wide">
            <label class="field-label" for="is_admin">Role</label>
            <select class="control" id="is_admin" name="is_admin" required>
                <option value="0" @selected((string) old('is_admin', (int) $user->is_admin) === '0')>Standard user</option>
                <option value="1" @selected((string) old('is_admin', (int) $user->is_admin) === '1')>Administrator</option>
            </select>
            @error('is_admin')<p class="field-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
