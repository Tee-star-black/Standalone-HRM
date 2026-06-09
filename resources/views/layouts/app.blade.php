<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Standalone HRM') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            background: #f3f4f6;
            font-family: Arial, sans-serif;
        }

        .mobile-overlay {
            display: none;
        }

        .app-content {
            margin-left: 270px;
            width: calc(100% - 270px);
            min-height: 100vh;
            transition: all .25s ease;
        }

        body.sidebar-collapsed .app-content {
            margin-left: 86px;
            width: calc(100% - 86px);
        }

        .topbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .mobile-menu-button {
            display: none;
            background: #111827;
            color: white;
            border: 0;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
        }

        .page-header {
            background: white;
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
        }

        main {
            padding: 32px;
        }

        .pref-link {
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: #111827;
            font-weight: 600;
            font-size: 14px;
        }

        .pref-link:hover {
            background: #f3f4f6;
        }

        .theme-toggle {
            background: white;
            border: 1px solid #d1d5db;
            color: #111827;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        body.dark-mode {
            background: #020617;
            color: #e5e7eb;
        }

        body.dark-mode .topbar,
        body.dark-mode .page-header {
            background: #0f172a;
            border-color: #1e293b;
        }

        body.dark-mode main {
            background: #020617;
        }

        body.dark-mode div,
        body.dark-mode section,
        body.dark-mode aside {
            border-color: #1e293b;
        }

        body.dark-mode [style*="background:white"],
        body.dark-mode [style*="background: white"] {
            background: #0f172a !important;
            color: #e5e7eb !important;
        }

        body.dark-mode [style*="background:#f9fafb"],
        body.dark-mode [style*="background: #f9fafb"],
        body.dark-mode [style*="background:#f3f4f6"],
        body.dark-mode [style*="background: #f3f4f6"] {
            background: #1e293b !important;
            color: #e5e7eb !important;
        }

        body.dark-mode [style*="color:#111827"],
        body.dark-mode [style*="color: #111827"],
        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode h5,
        body.dark-mode h6,
        body.dark-mode strong,
        body.dark-mode label,
        body.dark-mode td,
        body.dark-mode th {
            color: #f9fafb !important;
        }

        body.dark-mode [style*="color:#6b7280"],
        body.dark-mode [style*="color: #6b7280"],
        body.dark-mode [style*="color:#9ca3af"],
        body.dark-mode [style*="color: #9ca3af"],
        body.dark-mode p {
            color: #94a3b8 !important;
        }

        body.dark-mode a {
            color: #93c5fd;
        }

        body.dark-mode input,
        body.dark-mode select,
        body.dark-mode textarea {
            background: #020617 !important;
            color: #e5e7eb !important;
            border-color: #334155 !important;
        }

        body.dark-mode .theme-toggle {
            background: #1e293b;
            border-color: #334155;
            color: #e5e7eb;
        }

        body.dark-mode .pref-link {
            color: #e5e7eb;
        }

        body.dark-mode .pref-link:hover {
            background: #1e293b;
        }

        body.dark-mode table,
        body.dark-mode tr,
        body.dark-mode td,
        body.dark-mode th {
            border-color: #334155 !important;
        }

        @media (max-width: 768px) {
            .mobile-menu-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .app-content,
            body.sidebar-collapsed .app-content {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                height: auto;
                min-height: 64px;
                padding: 10px 14px;
                gap: 10px;
            }

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 0;
            }

            .topbar-user {
                min-width: 0;
            }

            .topbar-user strong,
            .topbar-user div {
                max-width: 170px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .topbar-actions {
                gap: 8px !important;
            }

            .preferences-button-text {
                display: none;
            }

            #preferences-menu {
                right: 0 !important;
                width: 250px !important;
            }

            .page-header {
                padding: 18px 16px;
            }

            main {
                padding: 16px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .dashboard-grid,
            .calendar-layout {
                grid-template-columns: 1fr !important;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .55);
                z-index: 8999;
            }

            body.mobile-sidebar-open .mobile-overlay {
                display: block;
            }

            body.mobile-sidebar-open {
                overflow: hidden;
            }
        }
    </style>
</head>

<body class="{{ Auth::check() && Auth::user()->theme === 'dark' ? 'dark-mode' : '' }}">
    <div class="mobile-overlay" onclick="closeMobileSidebar()"></div>

    @include('layouts.navigation')

    <div class="app-content">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="mobile-menu-button" onclick="openMobileSidebar()" title="Open menu">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="topbar-user">
                    <strong style="color:#111827;">{{ Auth::user()->name }}</strong>
                    <div style="font-size:12px; color:#6b7280;">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>

            <div class="topbar-actions" style="display:flex; align-items:center; gap:12px; position:relative;">

                <form method="POST" action="{{ route('settings.toggle-theme') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="theme-toggle" title="Toggle theme">
                        <span>{{ Auth::user()->theme === 'dark' ? '☀️' : '🌙' }}</span>
                    </button>
                </form>

                @php
                    $unreadNotifications = \App\Models\HrNotification::where('user_id', Auth::id())
                        ->whereNull('read_at')
                        ->count();
                @endphp

                <a href="{{ route('notifications.index') }}"
                   style="position:relative; background:white; border:1px solid #d1d5db; color:#111827; padding:10px 12px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; text-decoration:none;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-5-5.9V4a1 1 0 10-2 0v1.1A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0"/>
                    </svg>

                    @if($unreadNotifications > 0)
                        <span style="position:absolute; top:-6px; right:-6px; background:#dc2626; color:white; min-width:18px; height:18px; border-radius:999px; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:900;">
                            {{ $unreadNotifications }}
                        </span>
                    @endif
                </a>

                <div style="position:relative;">
                    <button onclick="togglePreferencesMenu()"
                            style="background:white;
                                   border:1px solid #d1d5db;
                                   color:#111827;
                                   padding:10px 14px;
                                   border-radius:10px;
                                   font-weight:600;
                                   display:flex;
                                   align-items:center;
                                   gap:8px;
                                   cursor:pointer;">

                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4 .895 4 2-1.79 2-4 2m0-8V6m0 10v2M4 6h16v12H4V6z"/>
                        </svg>

                        <span class="preferences-button-text">Preferences</span>
                    </button>

                    <div id="preferences-menu"
                         style="display:none;
                                position:absolute;
                                right:0;
                                top:52px;
                                width:260px;
                                background:white;
                                border-radius:14px;
                                box-shadow:0 12px 30px rgba(0,0,0,.15);
                                border:1px solid #e5e7eb;
                                overflow:hidden;
                                z-index:9999;">

                        <div style="padding:14px 16px; border-bottom:1px solid #f3f4f6;">
                            <strong>{{ Auth::user()->name }}</strong>
                            <div style="font-size:12px; color:#6b7280;">
                                Quick Access
                            </div>
                        </div>

                        <div style="padding:8px; display:grid; gap:4px;">
                            <a href="{{ route('my-profile.index') }}" class="pref-link">My Profile</a>
                            <a href="{{ route('preferences.index') }}" class="pref-link">My Preferences Hub</a>
                            <a href="{{ route('attendance.index') }}" class="pref-link">Attendance / Clock In</a>
                            <a href="{{ route('my-documents.index') }}" class="pref-link">My Documents</a>
                            <a href="{{ route('my-payslips.index') }}" class="pref-link">My Payslips</a>
                            <a href="{{ route('company-calendar.index') }}" class="pref-link">Calendar</a>
                            <a href="{{ route('generated-documents.index') }}" class="pref-link">Generated Documents</a>
                            <a href="{{ route('settings.index') }}" class="pref-link">Settings</a>
                        </div>

                        <div style="padding:12px; border-top:1px solid #f3f4f6;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <button style="width:100%;
                                               background:#111827;
                                               color:white;
                                               border:0;
                                               padding:10px;
                                               border-radius:8px;
                                               font-weight:600;">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @isset($header)
            <div class="page-header">
                {{ $header }}
            </div>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');

            localStorage.setItem(
                'sidebar-collapsed',
                document.body.classList.contains('sidebar-collapsed') ? 'yes' : 'no'
            );
        }

        if (localStorage.getItem('sidebar-collapsed') === 'yes') {
            document.body.classList.add('sidebar-collapsed');
        }

        function openMobileSidebar() {
            document.body.classList.add('mobile-sidebar-open');
        }

        function closeMobileSidebar() {
            document.body.classList.remove('mobile-sidebar-open');
        }

        function togglePreferencesMenu() {
            const menu = document.getElementById('preferences-menu');

            if (menu.style.display === 'block') {
                menu.style.display = 'none';
            } else {
                menu.style.display = 'block';
            }
        }

        window.addEventListener('click', function(e) {
            const menu = document.getElementById('preferences-menu');

            if (
                menu &&
                ! e.target.closest('#preferences-menu') &&
                ! e.target.closest('[onclick="togglePreferencesMenu()"]')
            ) {
                menu.style.display = 'none';
            }
        });
    </script>
</body>
</html>