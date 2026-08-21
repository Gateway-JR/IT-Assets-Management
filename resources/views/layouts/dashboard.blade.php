<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f4f7fa">
    <link rel="icon" type="image/png" href="{{ asset('images/Gateway_logo_circle.png') }}">
    <title>@yield('title', 'Inventory Dashboard') | Gateway IT Inventory System</title>

    <style>
        :root {
            color-scheme: light;
            --navy-950: #020d19;
            --navy-900: #061626;
            --navy-800: #081b2b;
            --navy-700: #10283e;
            --blue-600: #c91235;
            --blue-500: #e8193f;
            --blue-100: #fff0f3;
            --cyan-300: #ff4566;
            --accent: #e8193f;
            --accent-hover: #c91235;
            --accent-text: #b51235;
            --accent-on-dark: #ff6b83;
            --accent-soft: rgba(232, 25, 63, 0.1);
            --ink: #122033;
            --muted: #526176;
            --muted-light: #64748b;
            --muted-on-dark: #9fb2c6;
            --line: #d7e0e9;
            --line-soft: #e7edf3;
            --surface: #ffffff;
            --surface-raised: #f6f8fa;
            --surface-hover: #f1f5f8;
            --canvas: #f4f7fa;
            --field: #ffffff;
            --field-ink: #122033;
            --field-placeholder: #64748b;
            --success: #15803d;
            --success-soft: #eaf8ef;
            --danger: #b42318;
            --danger-soft: #fef3f2;
            --warning: #a85d0b;
            --warning-soft: #fff7e6;
            --sidebar-width: 252px;
            --shadow: 0 18px 45px rgba(7, 17, 31, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            min-height: 100%;
            margin: 0;
            color: var(--ink);
            background: var(--canvas);
            font-family: Inter, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        a {
            color: inherit;
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            z-index: 40;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            color: #ffffff;
            background:
                radial-gradient(circle at 0 0, rgba(232, 25, 63, 0.14), transparent 32%),
                var(--navy-950);
            transition: transform 180ms ease;
        }

        .sidebar::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.022) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.022) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, black, transparent 68%);
        }

        .sidebar-brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 88px;
            padding: 20px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
        }

        .brand-mark-wrap {
            width: 60px;
            height: 50px;
            overflow: hidden;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
        }

        .brand-mark-wrap img {
            display: block;
            width: 49px;
            height: auto;
        }

        .brand-name {
            display: block;
            font-size: 0.94rem;
            font-weight: 850;
            line-height: 1;
            letter-spacing: 0.14em;
        }

        .brand-division {
            display: block;
            margin-top: 7px;
            color: #8fa2b7;
            font-size: 0.58rem;
            font-weight: 750;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            position: relative;
            z-index: 1;
            flex: 1;
            padding: 28px 14px;
        }

        .nav-label {
            margin: 0 10px 10px;
            color: #687f97;
            font-size: 0.61rem;
            font-weight: 800;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            margin-bottom: 4px;
            padding: 0 12px;
            border-left: 2px solid transparent;
            color: var(--muted-on-dark);
            font-size: 0.82rem;
            font-weight: 650;
            text-decoration: none;
            transition: color 150ms ease, background 150ms ease, border-color 150ms ease;
        }

        .nav-link:hover,
        .nav-link.active {
            border-left-color: var(--cyan-300);
            color: #ffffff;
            background: var(--accent-soft);
        }

        .nav-icon {
            width: 26px;
            height: 26px;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: var(--accent-on-dark);
            font-size: 0.52rem;
            font-weight: 850;
            letter-spacing: 0.04em;
        }

        .sidebar-footer {
            position: relative;
            z-index: 1;
            margin: 14px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            background: rgba(255, 255, 255, 0.035);
        }

        .system-status {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #dce8f2;
            font-size: 0.72rem;
            font-weight: 700;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.12);
        }

        .system-copy {
            margin: 8px 0 0 16px;
            color: #71869b;
            font-size: 0.65rem;
            line-height: 1.45;
        }

        .main-shell {
            min-width: 0;
            margin-left: var(--sidebar-width);
        }

        .topbar {
            position: sticky;
            z-index: 30;
            top: 0;
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 14px clamp(22px, 3.2vw, 48px);
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .menu-button {
            display: none;
            width: 42px;
            height: 42px;
            padding: 0;
            border: 1px solid var(--line);
            color: var(--ink);
            background: var(--surface-raised);
            cursor: pointer;
        }

        .menu-lines,
        .menu-lines::before,
        .menu-lines::after {
            display: block;
            width: 18px;
            height: 2px;
            margin: auto;
            content: "";
            background: currentColor;
        }

        .menu-lines {
            position: relative;
        }

        .menu-lines::before {
            position: absolute;
            top: -6px;
        }

        .menu-lines::after {
            position: absolute;
            top: 6px;
        }

        .topbar-kicker {
            margin: 0 0 4px;
            color: var(--accent-text);
            font-size: 0.61rem;
            font-weight: 850;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .topbar-title {
            overflow: hidden;
            margin: 0;
            color: var(--ink);
            font-size: 1rem;
            font-weight: 760;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .operator {
            display: flex;
            align-items: center;
            gap: 11px;
            flex: 0 0 auto;
        }

        .operator-copy {
            text-align: right;
        }

        .operator-name {
            display: block;
            font-size: 0.75rem;
            font-weight: 760;
        }

        .operator-role {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 0.65rem;
        }

        .operator-avatar {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            color: #ffffff;
            background: var(--navy-800);
            font-size: 0.65rem;
            font-weight: 850;
            letter-spacing: 0.05em;
        }

        .main-content {
            width: min(1680px, 100%);
            margin: 0 auto;
            padding: clamp(25px, 3.2vw, 48px);
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 28px;
            margin-bottom: 28px;
        }

        .page-eyebrow {
            margin: 0 0 9px;
            color: var(--accent-text);
            font-size: 0.66rem;
            font-weight: 850;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .page-title {
            margin: 0;
            color: var(--ink);
            font-size: clamp(1.75rem, 3.2vw, 2.55rem);
            font-weight: 760;
            line-height: 1.08;
            letter-spacing: -0.035em;
        }

        .page-description {
            max-width: 720px;
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.65;
        }

        .button-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 0 0 auto;
        }

        .button {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px;
            border: 1px solid var(--line);
            border-radius: 3px;
            color: var(--ink);
            background: var(--surface-raised);
            font-size: 0.75rem;
            font-weight: 760;
            text-decoration: none;
            cursor: pointer;
            transition: border-color 150ms ease, background 150ms ease, transform 150ms ease;
        }

        .button:hover {
            border-color: #b9c7d5;
            background: var(--surface-hover);
        }

        .button-primary {
            border-color: var(--blue-500);
            color: #ffffff;
            background: var(--blue-500);
            box-shadow: 0 9px 20px rgba(232, 25, 63, 0.22);
        }

        .button-primary:hover {
            border-color: var(--blue-600);
            background: var(--blue-600);
            transform: translateY(-1px);
        }

        .button-danger {
            border-color: #efc9c5;
            color: var(--danger);
            background: var(--danger-soft);
        }

        .button-small {
            min-height: 34px;
            padding: 0 11px;
            font-size: 0.69rem;
        }

        .flash {
            margin-bottom: 22px;
            padding: 13px 16px;
            border-left: 3px solid var(--success);
            color: #166534;
            background: var(--success-soft);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .flash-error {
            border-left-color: var(--danger);
            color: var(--danger);
            background: var(--danger-soft);
        }

        .panel {
            border: 1px solid var(--line-soft);
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 24px;
            padding: 3px 8px;
            border: 1px solid transparent;
            border-radius: 999px;
            font-size: 0.63rem;
            font-weight: 800;
            line-height: 1;
            white-space: nowrap;
        }

        .badge::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: currentColor;
        }

        .badge-success {
            border-color: #c7ead2;
            color: var(--success);
            background: var(--success-soft);
        }

        .badge-danger {
            border-color: #f3d0cc;
            color: var(--danger);
            background: var(--danger-soft);
        }

        .badge-warning {
            border-color: #f2ddb5;
            color: var(--warning);
            background: var(--warning-soft);
        }

        .badge-neutral {
            border-color: var(--line);
            color: var(--muted);
            background: var(--surface-raised);
        }

        .progress {
            height: 6px;
            overflow: hidden;
            background: var(--line-soft);
        }

        .progress > span {
            display: block;
            height: 100%;
            background: var(--blue-500);
        }

        .progress.warning > span {
            background: var(--warning);
        }

        .progress.danger > span {
            background: var(--danger);
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #314358;
            font-size: 0.69rem;
            font-weight: 780;
            letter-spacing: 0.025em;
        }

        .control {
            width: 100%;
            min-height: 44px;
            padding: 10px 12px;
            border: 1px solid #cbd6e1;
            border-radius: 3px;
            outline: 0;
            color-scheme: light;
            color: var(--field-ink);
            background: var(--field);
            font-size: 0.78rem;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        textarea.control {
            min-height: 110px;
            resize: vertical;
            line-height: 1.55;
        }

        .control:focus {
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(232, 25, 63, 0.14);
        }

        .control::placeholder {
            color: var(--field-placeholder);
        }

        .field-error {
            margin: 6px 0 0;
            color: var(--danger);
            font-size: 0.67rem;
            line-height: 1.45;
        }

        .sidebar-overlay {
            position: fixed;
            z-index: 35;
            inset: 0;
            display: none;
            border: 0;
            background: rgba(2, 11, 22, 0.72);
        }

        :focus-visible {
            outline: 3px solid rgba(232, 25, 63, 0.42);
            outline-offset: 2px;
        }

        @media (max-width: 1100px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .main-shell {
                margin-left: 0;
            }

            .menu-button {
                display: inline-grid;
                place-items: center;
            }
        }

        @media (max-width: 720px) {
            .topbar {
                min-height: 68px;
                padding: 12px 16px;
            }

            .operator-copy {
                display: none;
            }

            .main-content {
                padding: 24px 16px 42px;
            }

            .page-heading {
                align-items: stretch;
                flex-direction: column;
                gap: 20px;
            }

            .button-row {
                width: 100%;
            }

            .button-row .button {
                flex: 1;
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

        @yield('styles')
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar" aria-label="Primary navigation">
            <a class="sidebar-brand" href="{{ route('dashboard') }}">
                <span class="brand-mark-wrap" aria-hidden="true">
                    <img src="{{ asset('images/Gateway_logo_circle.png') }}" alt="">
                </span>
                <span>
                    <span class="brand-name">GATEWAY</span>
                    <span class="brand-division">IT Inventory System</span>
                </span>
            </a>

            <nav class="sidebar-nav">
                <p class="nav-label">Inventory</p>
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-dashboard-section="overview" href="{{ route('dashboard') }}">
                    <span class="nav-icon" aria-hidden="true">OV</span>
                    Overview
                </a>
                <a class="nav-link {{ request()->routeIs('cctv-sites.*') ? 'active' : '' }}" data-dashboard-section="inventory" href="{{ route('dashboard') }}#inventory">
                    <span class="nav-icon" aria-hidden="true">SI</span>
                    Site inventory
                </a>
                <a class="nav-link {{ request()->routeIs('it-assets.*') ? 'active' : '' }}" href="{{ route('it-assets.index') }}">
                    <span class="nav-icon" aria-hidden="true">IT</span>
                    IT assets
                </a>
                @if (auth()->user()->is_admin)
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <span class="nav-icon" aria-hidden="true">US</span>
                        Users
                    </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="system-status">
                    <span class="status-dot" aria-hidden="true"></span>
                    Inventory system active
                </div>
                <p class="system-copy">Protected inventory workspace</p>
            </div>
        </aside>

        <button class="sidebar-overlay" id="sidebarOverlay" type="button" aria-label="Close navigation"></button>

        <div class="main-shell">
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-button" id="menuButton" type="button" aria-controls="sidebar" aria-expanded="false" aria-label="Open navigation">
                        <span class="menu-lines" aria-hidden="true"></span>
                    </button>
                    <div>
                        <p class="topbar-kicker">Gateway IT</p>
                        <p class="topbar-title">@yield('topbar-title', 'IT Inventory System')</p>
                    </div>
                </div>

                <div class="operator" aria-label="Current workspace">
                    <div class="operator-copy">
                        <span class="operator-name">{{ auth()->user()->name }}</span>
                        <span class="operator-role">{{ auth()->user()->is_admin ? 'Administrator' : 'Standard user' }} workspace</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="button button-small" type="submit">Sign out</button>
                    </form>
                </div>
            </header>

            <main class="main-content">
                @if (session('success'))
                    <div class="flash" role="status">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="flash flash-error" role="alert">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const menuButton = document.getElementById('menuButton');
            const overlay = document.getElementById('sidebarOverlay');

            if (!sidebar || !menuButton || !overlay) return;

            function setOpen(open) {
                sidebar.classList.toggle('open', open);
                overlay.classList.toggle('open', open);
                menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
                menuButton.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
            }

            menuButton.addEventListener('click', function () {
                setOpen(!sidebar.classList.contains('open'));
            });

            overlay.addEventListener('click', function () {
                setOpen(false);
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1100) setOpen(false);
            });
        })();
    </script>

    <script>
        (function () {
            const inventory = document.getElementById('inventory');
            const overviewLink = document.querySelector('[data-dashboard-section="overview"]');
            const inventoryLink = document.querySelector('[data-dashboard-section="inventory"]');
            const topbar = document.querySelector('.topbar');

            if (!inventory || !overviewLink || !inventoryLink) return;

            let ticking = false;
            let inventoryNavigationPending = false;
            let inventoryNavigationTimer;

            function setActive(section) {
                const inventoryIsActive = section === 'inventory';

                overviewLink.classList.toggle('active', !inventoryIsActive);
                inventoryLink.classList.toggle('active', inventoryIsActive);

                if (inventoryIsActive) {
                    overviewLink.removeAttribute('aria-current');
                    inventoryLink.setAttribute('aria-current', 'page');
                } else {
                    inventoryLink.removeAttribute('aria-current');
                    overviewLink.setAttribute('aria-current', 'page');
                }
            }

            function updateActiveLink() {
                const topbarHeight = topbar ? topbar.getBoundingClientRect().height : 0;
                const inventoryReached = inventory.getBoundingClientRect().top <= topbarHeight + 32;

                if (inventoryReached) inventoryNavigationPending = false;

                if (!inventoryNavigationPending) {
                    setActive(inventoryReached ? 'inventory' : 'overview');
                }

                ticking = false;
            }

            function requestUpdate() {
                if (ticking) return;

                ticking = true;
                window.requestAnimationFrame(updateActiveLink);
            }

            inventoryLink.addEventListener('click', function () {
                inventoryNavigationPending = true;
                window.clearTimeout(inventoryNavigationTimer);
                setActive('inventory');

                inventoryNavigationTimer = window.setTimeout(function () {
                    inventoryNavigationPending = false;
                    requestUpdate();
                }, 1600);
            });

            overviewLink.addEventListener('click', function () {
                setActive('overview');
            });

            window.addEventListener('scroll', requestUpdate, { passive: true });
            window.addEventListener('resize', requestUpdate);
            window.addEventListener('hashchange', requestUpdate);
            requestUpdate();
        })();
    </script>

    @stack('scripts')
</body>
</html>
