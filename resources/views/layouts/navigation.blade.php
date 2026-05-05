<aside style="position:fixed; top:0; left:0; width:270px; height:100vh; background:#0f172a; color:white; z-index:9999; box-shadow:0 10px 25px rgba(0,0,0,.25); overflow-y:auto;">
    <div style="height:78px; display:flex; align-items:center; padding:0 20px; border-bottom:1px solid #334155;">
        <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:12px; color:white; text-decoration:none;">
            <x-application-logo style="width:46px; height:46px; flex-shrink:0; object-fit:contain;" />
            <span style="font-size:20px; font-weight:800; letter-spacing:.3px; line-height:1.1;">MedConnect</span>
        </a>
    </div>

    <nav style="padding:18px; display:flex; flex-direction:column; gap:8px; padding-bottom:50px;">

        @php
            $base = 'display:flex; align-items:center; gap:12px; color:white; padding:11px 14px; border-radius:10px; text-decoration:none; font-size:14px;';
            $active = 'background:#2563eb;';
            $inactive = 'background:transparent;';
            $icon = 'width:20px; height:20px; flex-shrink:0;';
            $section = 'margin-top:18px; padding-top:16px; border-top:1px solid #334155; color:#94a3b8; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em;';
        @endphp

        <a href="{{ route('dashboard') }}" style="{{ $base }} {{ request()->routeIs('dashboard') ? $active : $inactive }}">
            <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zM13 21h8v-8h-8v8zM13 3v8h8V3h-8zM3 21h8v-6H3v6z"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div style="{{ $section }}">Employee</div>

        <a href="{{ route('my-profile.index') }}" style="{{ $base }} {{ request()->routeIs('my-profile.*') ? $active : $inactive }}">
            <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>My Profile</span>
        </a>

        <a href="{{ route('attendance.index') }}" style="{{ $base }} {{ request()->routeIs('attendance.*') ? $active : $inactive }}">
             <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
              <span>Attendance</span>
        </a>

        <a href="{{ route('my-leave.index') }}" style="{{ $base }} {{ request()->routeIs('my-leave.*') ? $active : $inactive }}">
            <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>My Leave</span>
        </a>

        <a href="{{ route('leave-calendar.index') }}" style="{{ $base }} {{ request()->routeIs('leave-calendar.*') ? $active : $inactive }}">
            <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
            </svg>
            <span>Leave Calendar</span>
        </a>

        <a href="{{ route('my-documents.index') }}" style="{{ $base }} {{ request()->routeIs('my-documents.*') ? $active : $inactive }}">
            <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 11h10M7 15h6M5 3h10l4 4v14H5V3z"/>
            </svg>
            <span>My Documents</span>
        </a>

        <a href="{{ route('my-payslips.index') }}" style="{{ $base }} {{ request()->routeIs('my-payslips.*') ? $active : $inactive }}">
            <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4 .895 4 2-1.79 2-4 2m0-8V6m0 10v2M4 6h16v12H4V6z"/>
            </svg>
            <span>My Payslips</span>
        </a>

        <a href="{{ route('recruitment.index') }}" style="{{ $base }} {{ request()->routeIs('recruitment.*') ? $active : $inactive }}">
    <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 100-6 3 3 0 000 6z"/>
    </svg>
    <span>Recruitment</span>
    </a>

        @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin', 'Manager']))
            <div style="{{ $section }}">Manager</div>

            <a href="{{ route('manager-leave.index') }}" style="{{ $base }} {{ request()->routeIs('manager-leave.*') ? $active : $inactive }}">
                <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-7-3-7 3V6a2 2 0 012-2z"/>
                </svg>
                <span>Leave Approvals</span>
            </a>
        @endif

        @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin']))
            <div style="{{ $section }}">HR Admin</div>

            <a href="{{ route('hr-documents.index') }}" style="{{ $base }} {{ request()->routeIs('hr-documents.*') ? $active : $inactive }}">
                <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 7h6l2 2h10v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                </svg>
                <span>HR Documents</span>
            </a>

            <a href="{{ route('hr-payslips.index') }}" style="{{ $base }} {{ request()->routeIs('hr-payslips.*') ? $active : $inactive }}">
                <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 12H6L5 9z"/>
                </svg>
                <span>HR Payslips</span>
            </a>

            <a href="{{ route('hr-attendance.index') }}" style="{{ $base }} {{ request()->routeIs('hr-attendance.*') ? $active : $inactive }}">
                <svg style="{{ $icon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>
                <span>HR Attendance</span>
            </a>
        @endif
    </nav>
</aside>
