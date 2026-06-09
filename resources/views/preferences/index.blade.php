<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    My Preferences
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Your personal self-service hub for documents, payslips, calendar, and settings.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        <div style="background:#111827; color:white; padding:26px; border-radius:22px; box-shadow:0 12px 30px rgba(17,24,39,.18);">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:18px; flex-wrap:wrap;">
                <div>
                    <h3 style="font-size:24px; font-weight:900; margin:0;">
                        Welcome, {{ $user->name }}
                    </h3>
                    <p style="color:#d1d5db; margin-top:6px;">
                        Manage your personal HR services from one place.
                    </p>
                </div>

                <div style="background:white; color:#111827; padding:12px 16px; border-radius:14px;">
                    <strong>{{ $employee?->employee_number ?? 'No employee profile' }}</strong>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:18px;">

            <a href="{{ route('my-documents.index') }}" class="pref-card">
                <div class="pref-icon">📄</div>
                <h3>My Documents</h3>
                <p>Upload, download, and manage contracts, IDs, certificates, tax forms, and HR documents.</p>
                <span>Open Documents →</span>
            </a>

            <a href="{{ route('my-payslips.index') }}" class="pref-card">
                <div class="pref-icon">💳</div>
                <h3>My Payslips</h3>
                <p>View monthly payslips, download PDFs, and review salary breakdowns.</p>
                <span>Open Payslips →</span>
            </a>

            <a href="{{ route('leave-calendar.index') }}" class="pref-card">
                <div class="pref-icon">📅</div>
                <h3>Calendar</h3>
                <p>View public out-of-office information, company events, and future calendar features.</p>
                <span>Open Calendar →</span>
            </a>

            <a href="{{ route('generated-documents.index') }}" class="pref-card">
                <div class="pref-icon">🧾</div>
                <h3>Generated Documents</h3>
                <p>Generate confirmation letters, salary letters, leave letters, contracts, and tax documents.</p>
                <span>Open Generated Documents →</span>
            </a>

            <a href="#" class="pref-card muted-card">
                <div class="pref-icon">⚙️</div>
                <h3>Settings</h3>
                <p>Manage account settings, notifications, privacy, language, theme, and security preferences.</p>
                <span>Coming Soon</span>
            </a>

            <a href="{{ route('attendance.index') }}" class="pref-card">
                <div class="pref-icon">⏱️</div>
                <h3>Clock In / Out</h3>
                <p>Quickly access your attendance page and daily clock-in/clock-out actions.</p>
                <span>Open Attendance →</span>
            </a>

        </div>

        <div style="background:white; border:1px solid #e5e7eb; padding:24px; border-radius:20px; box-shadow:0 8px 24px rgba(15,23,42,.05);">
            <h3 style="font-size:20px; font-weight:900; margin:0 0 14px;">
                Planned Preference Features
            </h3>

            <div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;">
                <div class="feature-box">
                    <strong>Document Enhancements</strong>
                    <p>Expiry reminders, version tracking, HR verification status.</p>
                </div>

                <div class="feature-box">
                    <strong>Payroll Enhancements</strong>
                    <p>Annual summaries, tax certificates, payslip comparisons.</p>
                </div>

                <div class="feature-box">
                    <strong>Personal Calendar</strong>
                    <p>Birthdays, holidays, events, reviews, training schedules.</p>
                </div>
            </div>
        </div>

    </div>

    <style>
        .pref-card {
            background:white;
            border:1px solid #e5e7eb;
            border-radius:20px;
            padding:24px;
            text-decoration:none;
            color:#111827;
            box-shadow:0 8px 24px rgba(15,23,42,.05);
            transition:.15s ease;
            display:block;
        }

        .pref-card:hover {
            transform:translateY(-2px);
            box-shadow:0 14px 34px rgba(15,23,42,.10);
        }

        .pref-card h3 {
            font-size:20px;
            font-weight:900;
            margin:10px 0 8px;
        }

        .pref-card p {
            color:#6b7280;
            line-height:1.5;
            min-height:72px;
        }

        .pref-card span {
            color:#2563eb;
            font-weight:800;
        }

        .pref-icon {
            width:48px;
            height:48px;
            border-radius:16px;
            background:#f3f4f6;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:24px;
        }

        .muted-card {
            opacity:.75;
        }

        .feature-box {
            background:#f9fafb;
            border:1px solid #e5e7eb;
            border-radius:16px;
            padding:16px;
        }

        .feature-box p {
            color:#6b7280;
            margin:6px 0 0;
        }
    </style>
</x-app-layout>