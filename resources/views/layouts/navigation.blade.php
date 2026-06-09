<aside class="app-sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo-wrap">
            <x-application-logo class="brand-logo" />
        </div>
        <div class="brand-text">
            <div class="brand-title">MedConnect</div>
            <div class="brand-subtitle">Workforce Platform</div>
        </div>

        <button type="button" class="mobile-close" onclick="closeMobileSidebar()" title="Close menu">
            ×
        </button>
    </div>

    <button type="button" onclick="toggleSidebar()" class="sidebar-toggle desktop-toggle" title="Minimize sidebar">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
        </svg>
        <span class="nav-label">Minimize</span>
    </button>

    <nav class="sidebar-nav">
        @php
            $base = 'nav-link';
            $active = 'active';
        @endphp

        <a href="{{ route('dashboard') }}" class="{{ $base }} {{ request()->routeIs('dashboard') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zM13 21h8v-8h-8v8zM13 3v8h8V3h-8zM3 21h8v-6H3v6z"/>
            </svg>
            <span class="nav-label">Dashboard</span>
        </a>

        <div class="nav-section"><span class="nav-label">Employee</span></div>

        <a href="{{ route('my-profile.index') }}" class="{{ $base }} {{ request()->routeIs('my-profile.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="nav-label">My Profile</span>
        </a>

        <a href="{{ route('announcements.index') }}" class="{{ $base }} {{ request()->routeIs('announcements.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-2.634 1.527L5 18.882H3a1 1 0 01-1-1V9a1 1 0 011-1h2l3.366-1.885A1.76 1.76 0 0111 7.642zm0 0L19 3v18l-8-3.882"/>
            </svg>
            <span class="nav-label">Announcements</span>
        </a>
        
        <a href="{{ route('preferences.index') }}" class="{{ $base }} {{ request()->routeIs('preferences.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 15.5A3.5 3.5 0 1112 8a3.5 3.5 0 010 7.5zM19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06A1.65 1.65 0 0015 19.4a1.65 1.65 0 00-1 .6 1.65 1.65 0 01-2 0 1.65 1.65 0 00-1-.6 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.6 15a1.65 1.65 0 00-.6-1 1.65 1.65 0 010-2 1.65 1.65 0 00.6-1 1.65 1.65 0 00-.33-1.82l-.06-.06A2 2 0 017.04 6.3l.06.06A1.65 1.65 0 009 4.6a1.65 1.65 0 001-.6 1.65 1.65 0 012 0 1.65 1.65 0 001 .6 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9c0 .36.12.7.33 1a1.65 1.65 0 010 2 1.65 1.65 0 00-.33 3z"/>
            </svg>
            <span class="nav-label">My Preferences</span>
        </a>

        <a href="{{ route('my-leave.index') }}" class="{{ $base }} {{ request()->routeIs('my-leave.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="nav-label">My Leave</span>
        </a>

        <a href="{{ route('attendance.index') }}" class="{{ $base }} {{ request()->routeIs('attendance.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"/>
            </svg>
            <span class="nav-label">Attendance</span>
        </a>

        <a href="{{ route('my-documents.index') }}" class="{{ $base }} {{ request()->routeIs('my-documents.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h6M5 3h10l4 4v14H5V3z"/>
            </svg>
            <span class="nav-label">My Documents</span>
        </a>

        <a href="{{ route('generated-documents.index') }}" class="{{ $base }} {{ request()->routeIs('generated-documents.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586A2 2 0 0114 4.586L18.414 9A2 2 0 0119 10.414V18a2 2 0 01-2 2z"/>
            </svg>
            <span class="nav-label">Generated Docs</span>
        </a>

        <a href="{{ route('my-payslips.index') }}" class="{{ $base }} {{ request()->routeIs('my-payslips.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4 .895 4 2-1.79 2-4 2m0-8V6m0 10v2M4 6h16v12H4V6z"/>
            </svg>
            <span class="nav-label">My Payslips</span>
        </a>

        <a href="{{ route('leave-calendar.index') }}" class="{{ $base }} {{ request()->routeIs('leave-calendar.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
            </svg>
            <span class="nav-label">Leave Calendar</span>
        </a>

        <a href="{{ route('company-calendar.index') }}" class="{{ $base }} {{ request()->routeIs('company-calendar.*') ? $active : '' }}">
            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
            </svg>
            <span class="nav-label">Company Calendar</span>
        </a>

        @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin', 'Manager']))
            <div class="nav-section"><span class="nav-label">Manager</span></div>

            <a href="{{ route('manager-leave.index') }}" class="{{ $base }} {{ request()->routeIs('manager-leave.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-7-3-7 3V6a2 2 0 012-2z"/>
                </svg>
                <span class="nav-label">Leave Approvals</span>
            </a>
        @endif

        @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin']))
            <div class="nav-section"><span class="nav-label">HR Admin</span></div>

            <a href="{{ route('hr-employees.index') }}" class="{{ $base }} {{ request()->routeIs('hr-employees.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
                <span class="nav-label">Employees</span>
            </a>

            <a href="{{ route('document-templates.index') }}" class="{{ $base }} {{ request()->routeIs('document-templates.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.5L19 8.5V18a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-label">Document Templates</span>
            </a>

            <a href="{{ route('generated-documents.index') }}" class="{{ $base }} {{ request()->routeIs('generated-documents.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M7 7h10M7 11h10M7 15h6M5 3h10l4 4v14H5V3z"/>
                </svg>
                <span class="nav-label">Generated Documents</span>
            </a>

            <a href="{{ route('document-wizard.index') }}" class="{{ $base }} {{ request()->routeIs('document-wizard.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.5L19 8.5V18a2 2 0 01-2 2z"/>
                </svg>
                <span class="nav-label">Document Wizard</span>
            </a>

            <a href="{{ route('hr-documents.index') }}" class="{{ $base }} {{ request()->routeIs('hr-documents.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 7h6l2 2h10v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                </svg>
                <span class="nav-label">HR Documents</span>
            </a>

            <a href="{{ route('hr-payslips.index') }}" class="{{ $base }} {{ request()->routeIs('hr-payslips.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 12H6L5 9z"/>
                </svg>
                <span class="nav-label">HR Payslips</span>
            </a>

            <a href="{{ route('hr-attendance.index') }}" class="{{ $base }} {{ request()->routeIs('hr-attendance.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>
                <span class="nav-label">HR Attendance</span>
            </a>

            <a href="{{ route('recruitment.index') }}" class="{{ $base }} {{ request()->routeIs('recruitment.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z"/>
                </svg>
                <span class="nav-label">Recruitment</span>
            </a>

            <a href="{{ route('audit-logs.index') }}" class="{{ $base }} {{ request()->routeIs('audit-logs.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-7-3-7 3V6a2 2 0 012-2z"/>
                </svg>
                <span class="nav-label">Audit Logs</span>
            </a>

            <a href="{{ route('hr-reports.index') }}" class="{{ $base }} {{ request()->routeIs('hr-reports.*') ? $active : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6m4 6V7m4 10v-4M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <span class="nav-label">Reports</span>
            </a>
        @endif
    </nav>
</aside>

<style>
    .app-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 270px;
        height: 100vh;
        background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
        color: white;
        z-index: 9000;
        box-shadow: 0 18px 40px rgba(15, 23, 42, .35);
        overflow-y: auto;
        transition: width .25s ease, transform .25s ease;
    }

    body.sidebar-collapsed .app-sidebar {
        width: 86px;
    }

    .sidebar-brand {
        height: 76px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 20px;
        border-bottom: 1px solid rgba(148, 163, 184, .22);
        position: relative;
    }

    .brand-logo-wrap {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 6px;
        flex-shrink: 0;
        box-shadow: 0 8px 18px rgba(15, 23, 42, .22);
    }

    .brand-logo {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .brand-title {
        font-size: 17px;
        font-weight: 800;
        white-space: nowrap;
    }

    .brand-subtitle {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 2px;
        white-space: nowrap;
    }

    .mobile-close {
        display: none;
        margin-left: auto;
        background: rgba(255,255,255,.1);
        color: white;
        border: 0;
        width: 36px;
        height: 36px;
        border-radius: 12px;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
    }

    .sidebar-toggle {
        margin: 14px 18px 4px;
        width: calc(100% - 36px);
        display: flex;
        align-items: center;
        gap: 12px;
        border: 0;
        background: rgba(255,255,255,.08);
        color: #cbd5e1;
        padding: 11px 14px;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
    }

    .sidebar-nav {
        padding: 12px 18px 36px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #cbd5e1;
        padding: 11px 14px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background .15s ease, color .15s ease, transform .15s ease;
    }

    .nav-link:hover {
        background: rgba(255,255,255,.08);
        color: white;
        transform: translateX(2px);
    }

    .nav-link.active {
        background: #2563eb;
        color: white;
        box-shadow: 0 8px 18px rgba(37, 99, 235, .35);
    }

    .nav-icon {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }

    .nav-section {
        margin-top: 18px;
        padding: 14px 14px 6px;
        color: #64748b;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    body.sidebar-collapsed .brand-text,
    body.sidebar-collapsed .nav-label,
    body.sidebar-collapsed .nav-section {
        display: none;
    }

    body.sidebar-collapsed .sidebar-brand {
        justify-content: center;
        padding: 0;
    }

    body.sidebar-collapsed .sidebar-toggle {
        width: 50px;
        margin-left: 18px;
        justify-content: center;
    }

    body.sidebar-collapsed .nav-link {
        justify-content: center;
        padding: 13px 0;
    }

    @media (max-width: 768px) {
        .app-sidebar {
            width: 285px;
            max-width: 86vw;
            transform: translateX(-105%);
            z-index: 9999;
        }

        body.mobile-sidebar-open .app-sidebar {
            transform: translateX(0);
        }

        body.sidebar-collapsed .app-sidebar {
            width: 285px;
        }

        body.sidebar-collapsed .brand-text,
        body.sidebar-collapsed .nav-label,
        body.sidebar-collapsed .nav-section {
            display: block;
        }

        body.sidebar-collapsed .sidebar-brand {
            justify-content: flex-start;
            padding: 0 20px;
        }

        body.sidebar-collapsed .nav-link {
            justify-content: flex-start;
            padding: 11px 14px;
        }

        .desktop-toggle {
            display: none;
        }

        .mobile-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    }
</style>
