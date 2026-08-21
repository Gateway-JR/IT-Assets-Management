<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#020d19">
    <link rel="icon" type="image/png" href="{{ asset('images/Gateway_logo_circle.png') }}">
    <title>Gateway IT Inventory System | Sign In</title>

    <style>
        :root {
            color-scheme: dark;
            --night-950: #020d19;
            --night-900: #061626;
            --night-850: #081b2b;
            --red-500: #e8193f;
            --red-600: #c91235;
            --red-700: #a90d2c;
            --red-text: #ff4566;
            --red-glow: rgba(232, 25, 63, 0.3);
            --blue-line: #12375d;
            --text: #f7f9fc;
            --muted: #a5b1bf;
            --muted-dark: #8292a4;
            --input: #f7f8fa;
            --input-text: #0a1728;
            --line-dark: rgba(255, 255, 255, 0.12);
            --danger-soft: rgba(232, 25, 63, 0.11);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            color: var(--text);
            background: var(--night-950);
            font-family: Inter, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button,
        input {
            font: inherit;
        }

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

        .login-shell {
            position: relative;
            isolation: isolate;
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            place-items: center;
            overflow: hidden;
            padding: 42px 24px 74px;
            background:
                radial-gradient(circle at 50% 42%, rgba(15, 43, 68, 0.38), transparent 33%),
                linear-gradient(112deg, #010a14 0%, var(--night-950) 45%, #071828 100%);
        }

        .login-shell::before {
            content: "";
            position: absolute;
            z-index: -3;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at -8% 35%, rgba(232, 25, 63, 0.09), transparent 25%),
                radial-gradient(circle at 105% 78%, rgba(18, 55, 93, 0.24), transparent 31%);
        }

        .ambient-lines,
        .ambient-lines span {
            position: absolute;
            pointer-events: none;
        }

        .ambient-lines {
            z-index: -2;
            inset: 0;
            overflow: hidden;
        }

        .ambient-lines span {
            left: -18vw;
            width: 112vw;
            height: 1px;
            transform-origin: center;
        }

        .beam-red-one {
            top: 12%;
            transform: rotate(-15deg);
            background: linear-gradient(90deg, transparent 2%, rgba(232, 25, 63, 0.08) 20%, var(--red-500) 60%, transparent 91%);
            box-shadow: 0 0 12px rgba(232, 25, 63, 0.52);
        }

        .beam-red-two {
            top: 20%;
            left: -30vw !important;
            transform: rotate(-31deg);
            background: linear-gradient(90deg, transparent 12%, rgba(232, 25, 63, 0.65) 54%, transparent 90%);
            box-shadow: 0 0 16px rgba(232, 25, 63, 0.38);
        }

        .beam-red-three {
            top: 31%;
            left: -37vw !important;
            height: 4px !important;
            transform: rotate(-36deg);
            background: linear-gradient(90deg, transparent 19%, rgba(169, 13, 44, 0.08) 37%, rgba(232, 25, 63, 0.48) 60%, transparent 85%);
            filter: blur(1px);
            box-shadow: 0 0 21px rgba(232, 25, 63, 0.26);
        }

        .beam-blue {
            top: auto;
            bottom: 3%;
            left: 13vw !important;
            width: 104vw !important;
            height: 5px !important;
            transform: rotate(-20deg);
            background: linear-gradient(90deg, transparent 6%, rgba(18, 55, 93, 0.62) 39%, rgba(24, 74, 123, 0.84) 69%, transparent 96%);
            filter: blur(1px);
            box-shadow: 0 0 20px rgba(18, 55, 93, 0.52);
        }

        .beam-red-four {
            top: auto;
            bottom: 8%;
            left: 28vw !important;
            width: 96vw !important;
            transform: rotate(-15deg);
            background: linear-gradient(90deg, transparent 4%, rgba(232, 25, 63, 0.55) 51%, transparent 95%);
            box-shadow: 0 0 13px rgba(232, 25, 63, 0.4);
        }

        .beam-red-five {
            top: auto;
            bottom: -1%;
            left: 45vw !important;
            width: 80vw !important;
            transform: rotate(-15deg);
            background: linear-gradient(90deg, transparent, rgba(232, 25, 63, 0.4), transparent 88%);
            box-shadow: 0 0 10px rgba(232, 25, 63, 0.28);
        }

        .login-card {
            position: relative;
            z-index: 2;
            width: min(100%, 370px);
            padding: 28px 28px 20px;
            border: 1px solid rgba(232, 25, 63, 0.55);
            border-radius: 14px;
            background:
                linear-gradient(145deg, rgba(8, 27, 43, 0.94), rgba(2, 13, 25, 0.96)),
                var(--night-900);
            box-shadow:
                0 34px 90px rgba(0, 0, 0, 0.52),
                0 0 44px rgba(232, 25, 63, 0.06),
                inset 0 1px 0 rgba(255, 255, 255, 0.035);
            backdrop-filter: blur(16px);
        }

        .identity {
            text-align: center;
        }

        .logo-orb {
            width: 80px;
            height: 80px;
            display: grid;
            place-items: center;
            overflow: hidden;
            margin: 0 auto 15px;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 50%;
            background: #ffffff;
            box-shadow:
                0 0 0 4px rgba(255, 255, 255, 0.035),
                0 0 24px rgba(255, 255, 255, 0.16);
        }

        .logo-orb img {
            display: block;
            width: 62px;
            height: 62px;
            object-fit: cover;
            border-radius: 50%;
        }

        .auth-kicker {
            margin: 0 0 5px;
            color: var(--red-text);
            font-size: 0.58rem;
            font-weight: 850;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .system-title {
            margin: 0;
            color: #ffffff;
            font-size: clamp(1.42rem, 5vw, 1.68rem);
            font-weight: 850;
            line-height: 1.05;
            letter-spacing: -0.035em;
            text-transform: uppercase;
        }

        .system-division {
            margin: 6px 0 0;
            color: #c4ccd6;
            font-size: 0.62rem;
            font-weight: 750;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .sign-in-copy {
            margin: 16px 0 17px;
            color: #d2d8e0;
            font-size: 0.74rem;
        }

        .session-status,
        .validation {
            margin: 0 0 14px;
            padding: 10px 12px;
            border-left: 2px solid #35c477;
            color: #b8f0d0;
            background: rgba(53, 196, 119, 0.09);
            font-size: 0.7rem;
            line-height: 1.45;
        }

        .validation {
            border-left-color: var(--red-500);
            color: #ff9daf;
            background: var(--danger-soft);
        }

        .field {
            margin-bottom: 10px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            z-index: 1;
            top: 50%;
            left: 14px;
            width: 17px;
            height: 17px;
            transform: translateY(-50%);
            color: #647286;
            pointer-events: none;
        }

        .input-wrap input {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 43px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 7px;
            outline: 0;
            color-scheme: light;
            color: var(--input-text);
            background: var(--input);
            font-size: 0.75rem;
            transition: border-color 150ms ease, box-shadow 150ms ease, background 150ms ease;
        }

        .input-wrap input::placeholder {
            color: #647286;
            opacity: 1;
        }

        .input-wrap input:focus {
            border-color: var(--red-500);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(232, 25, 63, 0.15);
        }

        .input-wrap input.error {
            border-color: var(--red-500);
            box-shadow: 0 0 0 2px rgba(232, 25, 63, 0.1);
        }

        .password-input {
            padding-right: 50px !important;
        }

        .password-toggle {
            position: absolute;
            z-index: 2;
            top: 50%;
            right: 2px;
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            transform: translateY(-50%);
            padding: 0;
            border: 0;
            border-radius: 6px;
            color: #647286;
            background: transparent;
            cursor: pointer;
        }

        .password-toggle svg {
            width: 19px;
            height: 19px;
        }

        .password-toggle .eye-closed,
        .password-toggle.is-visible .eye-open {
            display: none;
        }

        .password-toggle.is-visible .eye-closed {
            display: block;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin: 11px 0 16px;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #c7ced7;
            font-size: 0.66rem;
            cursor: pointer;
        }

        .remember input {
            width: 15px;
            height: 15px;
            margin: 0;
            accent-color: var(--red-500);
        }

        .login-button {
            width: 100%;
            height: 44px;
            border: 1px solid var(--red-500);
            border-radius: 7px;
            color: #ffffff;
            background: linear-gradient(90deg, var(--red-500), var(--red-600));
            box-shadow: 0 12px 28px rgba(232, 25, 63, 0.24);
            font-size: 0.76rem;
            font-weight: 820;
            cursor: pointer;
            transition: border-color 150ms ease, background 150ms ease, box-shadow 150ms ease, transform 150ms ease;
        }

        .login-button:hover {
            border-color: var(--red-600);
            background: linear-gradient(90deg, var(--red-600), var(--red-700));
            box-shadow: 0 14px 34px rgba(232, 25, 63, 0.34);
            transform: translateY(-1px);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .security-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid var(--line-dark);
            color: var(--muted-dark);
            font-size: 0.56rem;
            line-height: 1.45;
        }

        .security-icon {
            width: 6px;
            height: 6px;
            flex: 0 0 auto;
            margin-top: 3px;
            background: var(--red-500);
            box-shadow: 0 0 8px var(--red-glow);
        }

        .copyright {
            margin: 12px 0 0;
            color: var(--muted-dark);
            font-size: 0.57rem;
            text-align: center;
        }

        .system-meta {
            position: absolute;
            z-index: 1;
            left: 50%;
            bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            width: min(92%, 620px);
            transform: translateX(-50%);
            color: var(--muted-dark);
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .system-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .status-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #35c477;
            box-shadow: 0 0 0 3px rgba(53, 196, 119, 0.1);
        }

        :focus-visible {
            outline: 3px solid rgba(232, 25, 63, 0.4);
            outline-offset: 2px;
        }

        @media (max-width: 640px) {
            .login-shell {
                place-items: start center;
                overflow-y: auto;
                padding: 28px 16px 88px;
            }

            .login-card {
                margin-block: auto;
                padding: 27px 22px 22px;
            }

            .system-meta {
                bottom: 16px;
                gap: 12px;
                font-size: 0.49rem;
                letter-spacing: 0.04em;
            }
        }

        @media (max-height: 720px) {
            .login-shell {
                place-items: start center;
                overflow-y: auto;
                padding-top: 24px;
            }

            .login-card {
                padding-top: 24px;
            }

            .logo-orb {
                width: 68px;
                height: 68px;
                margin-bottom: 12px;
            }

            .logo-orb img {
                width: 52px;
                height: 52px;
            }

            .sign-in-copy {
                margin: 14px 0;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <div class="ambient-lines" aria-hidden="true">
            <span class="beam-red-one"></span>
            <span class="beam-red-two"></span>
            <span class="beam-red-three"></span>
            <span class="beam-blue"></span>
            <span class="beam-red-four"></span>
            <span class="beam-red-five"></span>
        </div>

        <section class="login-card" aria-labelledby="login-title">
            <header class="identity">
                <span class="logo-orb">
                    <img src="{{ asset('images/Gateway_logo_circle.png') }}" alt="GATEWAY">
                </span>
                <p class="auth-kicker">Protected access</p>
                <h1 class="system-title" id="login-title">IT Inventory System</h1>
                <p class="system-division">Information Technology</p>
                <p class="sign-in-copy">Sign in to your account</p>
            </header>

            @if (session('status'))
                <div class="session-status" role="status">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="validation" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label class="sr-only" for="email">Email address</label>
                    <div class="input-wrap">
                        <svg class="input-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="Email address"
                            autocomplete="username"
                            required
                            autofocus
                            aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                            @if ($errors->has('email')) aria-describedby="emailError" @endif
                            @class(['error' => $errors->has('email')])
                        >
                    </div>
                    @error('email')
                        <span class="sr-only" id="emailError">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label class="sr-only" for="password">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="5" y="10" width="14" height="11" rx="2"></rect>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
                        </svg>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="Password"
                            autocomplete="current-password"
                            required
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            @if ($errors->has('password')) aria-describedby="passwordError" @endif
                            @class(['password-input', 'error' => $errors->has('password')])
                        >
                        <button
                            class="password-toggle"
                            id="passwordToggle"
                            type="button"
                            aria-controls="password"
                            aria-label="Show password"
                            aria-pressed="false"
                        >
                            <svg class="eye-open" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m3 3 18 18"></path>
                                <path d="M10.6 6.2A10.8 10.8 0 0 1 12 6c6.5 0 10 6 10 6a17.5 17.5 0 0 1-2.1 2.8"></path>
                                <path d="M6.6 6.6C3.6 8.4 2 12 2 12s3.5 6 10 6c1.7 0 3.1-.4 4.4-1"></path>
                            </svg>
                            <span class="sr-only">Show or hide password</span>
                        </button>
                    </div>
                    @error('password')
                        <span class="sr-only" id="passwordError">{{ $message }}</span>
                    @enderror
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
        </section>

        <footer class="system-meta" aria-label="System information">
            <span><i class="status-dot" aria-hidden="true"></i>System &nbsp; Operational</span>
            <span>Availability &nbsp; 24 / 7</span>
            <span>Access &nbsp; Authorized only</span>
        </footer>
    </main>

    <script>
        (function () {
            const password = document.getElementById('password');
            const toggle = document.getElementById('passwordToggle');

            if (!password || !toggle) return;

            toggle.addEventListener('click', function () {
                const visible = password.type === 'text';
                password.type = visible ? 'password' : 'text';
                toggle.classList.toggle('is-visible', !visible);
                toggle.setAttribute('aria-label', visible ? 'Show password' : 'Hide password');
                toggle.setAttribute('aria-pressed', visible ? 'false' : 'true');
            });
        })();
    </script>
</body>
</html>
