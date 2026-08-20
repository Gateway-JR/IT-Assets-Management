<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#07111f">
    <link rel="icon" type="image/png" href="{{ asset('images/gateway-mark.png') }}">
    <title>Gateway IT Inventory System | Sign In</title>

    <style>
        :root {
            color-scheme: light;
            --navy-950: #07111f;
            --navy-900: #0a1728;
            --navy-800: #10243b;
            --blue-500: #1c7ed6;
            --cyan-300: #67d7e7;
            --ink: #122033;
            --muted: #64748b;
            --line: #dce4ec;
            --surface-soft: #f4f7fa;
            --success: #22c55e;
            --danger: #b42318;
            --danger-soft: #fef3f2;
            --shadow: 0 28px 70px rgba(7, 17, 31, 0.18);
        }

        * { box-sizing: border-box; }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            min-height: 100vh;
            color: var(--ink);
            background: #e9eef3;
            font-family: Inter, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button,
        input { font: inherit; }

        .shell {
            position: relative;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr;
            overflow: hidden;
            background: var(--navy-950);
        }

        .brand-panel {
            position: relative;
            isolation: isolate;
            min-height: 100%;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: clamp(36px, 5vw, 72px);
            color: #ffffff;
            background:
                linear-gradient(135deg, rgba(7, 17, 31, 0.98), rgba(10, 36, 61, 0.94)),
                var(--navy-950);
        }

        .brand-panel::before {
            content: "";
            position: absolute;
            z-index: -2;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 56px 56px;
            mask-image: linear-gradient(to bottom right, black, transparent 72%);
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            z-index: -1;
            width: min(48vw, 680px);
            aspect-ratio: 1;
            right: -18%;
            top: 16%;
            border: 1px solid rgba(103, 215, 231, 0.18);
            border-radius: 50%;
            box-shadow:
                0 0 0 70px rgba(28, 126, 214, 0.04),
                0 0 0 150px rgba(28, 126, 214, 0.025);
        }

        .brand-lockup {
            display: inline-flex;
            align-items: center;
            gap: 15px;
            width: fit-content;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.07);
            backdrop-filter: blur(10px);
        }

        .brand-mark img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .brand-name {
            display: block;
            font-size: 1.05rem;
            font-weight: 850;
            letter-spacing: 0.16em;
        }

        .brand-division {
            display: block;
            margin-top: 5px;
            color: #a9bfd2;
            font-size: 0.7rem;
            font-weight: 650;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .brand-content {
            position: relative;
            width: min(31vw, 440px);
            padding: 60px 0;
        }

        .eyebrow {
            margin: 0 0 18px;
            color: var(--cyan-300);
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .brand-title {
            max-width: 440px;
            margin: 0;
            font-size: clamp(2.6rem, 4.4vw, 4.5rem);
            font-weight: 760;
            line-height: 0.98;
            letter-spacing: -0.055em;
        }

        .brand-title span {
            display: block;
            color: #8fd9f2;
        }

        .visual-grid {
            width: min(100%, 560px);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 42px;
        }

        .visual-cell {
            height: 7px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.09);
        }

        .visual-cell::before {
            content: "";
            display: block;
            width: var(--level);
            height: 100%;
            background: linear-gradient(90deg, var(--blue-500), var(--cyan-300));
        }

        .operations {
            width: min(34vw, 500px);
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
        }

        .operation {
            padding: 17px 18px;
            background: rgba(7, 17, 31, 0.72);
        }

        .operation-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #8da5b8;
            font-size: 0.61rem;
            font-weight: 750;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .operation-value {
            display: block;
            margin-top: 8px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--success);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
        }

        .auth-panel {
            position: absolute;
            z-index: 3;
            inset: 0;
            display: grid;
            place-items: center;
            padding: clamp(32px, 6vw, 84px);
            pointer-events: none;
        }

        .auth-card {
            width: min(100%, 430px);
            padding: clamp(30px, 4vw, 46px);
            border: 1px solid #e0e6ec;
            background: #ffffff;
            box-shadow:
                0 30px 90px rgba(0, 0, 0, 0.32),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            pointer-events: auto;
        }

        .auth-kicker {
            margin: 0 0 10px;
            color: var(--blue-500);
            font-size: 0.65rem;
            font-weight: 850;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .auth-title {
            margin: 0 0 30px;
            color: var(--navy-950);
            font-size: clamp(1.65rem, 3vw, 2.1rem);
            letter-spacing: -0.035em;
        }

        .session-status,
        .validation {
            margin-bottom: 20px;
            padding: 12px 14px;
            border-left: 3px solid var(--success);
            color: #176b3a;
            background: #effaf3;
            font-size: 0.78rem;
            line-height: 1.45;
        }

        .validation {
            border-left-color: var(--danger);
            color: var(--danger);
            background: var(--danger-soft);
        }

        .field { margin-bottom: 21px; }

        .field label {
            display: block;
            margin-bottom: 8px;
            color: #34465a;
            font-size: 0.72rem;
            font-weight: 750;
        }

        .input-wrap { position: relative; }

        .input-wrap input {
            width: 100%;
            height: 50px;
            padding: 0 14px;
            border: 1px solid var(--line);
            border-radius: 0;
            outline: none;
            color: var(--ink);
            background: #ffffff;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        .input-wrap input:focus {
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(28, 126, 214, 0.12);
        }

        .input-wrap input.error { border-color: var(--danger); }
        .password-input { padding-right: 66px !important; }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: 0;
            padding: 6px;
            color: var(--blue-500);
            background: transparent;
            font-size: 0.67rem;
            font-weight: 800;
            cursor: pointer;
        }

        .field-error {
            margin: 7px 0 0;
            color: var(--danger);
            font-size: 0.7rem;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 2px 0 24px;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 0.7rem;
            cursor: pointer;
        }

        .remember input { accent-color: var(--blue-500); }

        .login-button {
            width: 100%;
            height: 50px;
            border: 0;
            color: #ffffff;
            background: var(--blue-500);
            font-weight: 780;
            cursor: pointer;
            transition: background 150ms ease, transform 150ms ease;
        }

        .login-button:hover { background: #146bb8; }
        .login-button:active { transform: translateY(1px); }

        .security-note {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 25px;
            padding-top: 21px;
            border-top: 1px solid #edf0f3;
            color: #7b8998;
            font-size: 0.65rem;
            line-height: 1.55;
        }

        .security-icon {
            width: 8px;
            height: 8px;
            flex: 0 0 auto;
            margin-top: 3px;
            background: var(--blue-500);
        }

        .copyright {
            margin: 22px 0 0;
            color: #98a3af;
            font-size: 0.61rem;
            text-align: center;
        }

        @media (max-width: 1200px) {
            .brand-content,
            .operations { visibility: hidden; }
        }

        @media (max-width: 980px) {
            .shell { grid-template-columns: 1fr; }
            .brand-panel { min-height: auto; padding: 30px; }
            .brand-content,
            .operations { display: none; }
            .auth-panel {
                position: static;
                min-height: auto;
                padding: 56px 24px;
                background: var(--navy-950);
                pointer-events: auto;
            }
        }

        @media (max-width: 620px) {
            .brand-content { padding: 54px 0; }
            .operations { grid-template-columns: 1fr; }
            .operation { display: flex; align-items: center; justify-content: space-between; }
            .operation-value { margin-top: 0; }
            .visual-grid { margin-top: 30px; }
            .auth-card { padding: 30px 24px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="brand-panel" aria-label="Gateway IT Inventory System">
            <header class="brand-lockup">
                <span class="brand-mark" aria-hidden="true">
                    <img src="{{ asset('images/gateway-mark.png') }}" alt="">
                </span>
                <span>
                    <span class="brand-name">GATEWAY</span>
                    <span class="brand-division">IT Inventory System</span>
                </span>
            </header>

            <div class="brand-content">
                <p class="eyebrow">Information Technology</p>
                <h1 class="brand-title">IT Inventory <span>System</span></h1>

                <div class="visual-grid" aria-hidden="true">
                    <span class="visual-cell" style="--level: 84%"></span>
                    <span class="visual-cell" style="--level: 62%"></span>
                    <span class="visual-cell" style="--level: 92%"></span>
                </div>
            </div>

            <div class="operations" aria-label="System information">
                <div class="operation">
                    <span class="operation-label"><span class="status-dot" aria-hidden="true"></span>System</span>
                    <span class="operation-value">Operational</span>
                </div>
                <div class="operation">
                    <span class="operation-label">Availability</span>
                    <span class="operation-value">24 / 7</span>
                </div>
                <div class="operation">
                    <span class="operation-label">Access</span>
                    <span class="operation-value">Authorized only</span>
                </div>
            </div>
        </section>

        <section class="auth-panel" aria-label="Account access">
            <div class="auth-card">
                <p class="auth-kicker">Protected access</p>
                <h2 class="auth-title">Sign in to your account</h2>

                @if (session('status'))
                    <div class="session-status" role="status">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="validation" role="alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="field">
                        <label for="email">Email address</label>
                        <div class="input-wrap">
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                autocomplete="username"
                                required
                                autofocus
                                @class(['error' => $errors->has('email')])
                            >
                            @error('email')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <input
                                class="password-input"
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                @class(['error' => $errors->has('password')])
                            >
                            <button
                                class="password-toggle"
                                id="passwordToggle"
                                type="button"
                                aria-controls="password"
                                aria-label="Show password"
                            >Show</button>
                            @error('password')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <label class="remember">
                            <input type="checkbox" name="remember" value="1">
                            <span>Keep me signed in</span>
                        </label>
                    </div>

                    <button class="login-button" type="submit">Sign in</button>
                </form>

                <div class="security-note">
                    <span class="security-icon" aria-hidden="true"></span>
                    <span>This is a restricted system. Access attempts may be logged and reviewed.</span>
                </div>

                <p class="copyright">&copy; {{ date('Y') }} Gateway IT Inventory System</p>
            </div>
        </section>
    </main>

    <script>
        (function () {
            const password = document.getElementById('password');
            const toggle = document.getElementById('passwordToggle');

            if (!password || !toggle) return;

            toggle.addEventListener('click', function () {
                const visible = password.type === 'text';
                password.type = visible ? 'password' : 'text';
                toggle.textContent = visible ? 'Show' : 'Hide';
                toggle.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
            });
        })();
    </script>
</body>
</html>
