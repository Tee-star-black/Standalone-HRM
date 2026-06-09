<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    HR Reports Center
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Central hub for HR exports, audits, payroll, recruitment, attendance, and employee reports.
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="report-button">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          d="M3 12l9-9 9 9M5 10v11h14V10" />
                </svg>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        <div class="dashboard-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:18px;">

            <div class="report-card">
                <div class="report-icon blue">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                </div>

                <h3>Recruitment Report</h3>
                <p>Export vacancies, candidates, application stages, CV status, offer status, and conversion records.</p>

                <a href="{{ route('recruitment.export') }}" class="report-button green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V4"/>
                    </svg>
                    Download Excel
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon dark">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h3>Attendance Report</h3>
                <p>Review employee clock-in, clock-out, attendance status, and hours worked.</p>

                <a href="{{ route('hr-attendance.index') }}" class="report-button">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"/>
                    </svg>
                    Open Report
                </a>

                <a href="{{ route('hr-reports.attendance.export') }}" class="report-button green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V4"/>
                    </svg>
                    Download Excel
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon amber">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h3>Leave Report</h3>
                <p>Track approved, pending, rejected, and upcoming leave requests across the organization.</p>

                <a href="{{ route('manager-leave.index') }}" class="report-button">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"/>
                    </svg>
                    Open Leave
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon purple">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 12H6L5 9z"/>
                    </svg>
                </div>

                <h3>Payroll Report</h3>
                <p>View payslip records, published payroll documents, net pay summaries, and payroll history.</p>

                <a href="{{ route('hr-payslips.index') }}" class="report-button">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"/>
                    </svg>
                    Open Payroll
                </a>

                <a href="{{ route('hr-reports.payroll.export') }}" class="report-button green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V4"/>
                    </svg>
                    Download Excel
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon blue">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>

                <h3>Employee Directory</h3>
                <p>Access employee profiles, employment details, attendance, payslips, documents, and leave history.</p>

                <a href="{{ route('hr-employees.index') }}" class="report-button">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"/>
                    </svg>
                    Open Employees
                </a>

                <a href="{{ route('hr-reports.employees.export') }}" class="report-button green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V4"/>
                    </svg>
                    Download Excel
                </a>
            </div>

            <div class="report-card">
                <div class="report-icon dark">
                    <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M9 12h6m-6 4h6M9 8h6m2 12H7a2 2 0 01-2-2V6a2 2 0 012-2h5.586A2 2 0 0114 4.586L18.414 9A2 2 0 0119 10.414V18a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <h3>Document Audit</h3>
                <p>Review uploaded HR documents, employee documents, generated PDFs, contracts, and payslips.</p>

                <a href="{{ route('hr-documents.index') }}" class="report-button">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                              d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"/>
                    </svg>
                    Open Documents

                    <a href="{{ route('hr-reports.documents.export') }}" class="report-button green">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V4"/>
                    </svg>
                    Download Excel
                </a>
                </a>
            </div>

        </div>

        <div style="background:#111827; color:white; padding:24px; border-radius:20px; box-shadow:0 12px 30px rgba(17,24,39,.18);">
            <h3 style="font-size:20px; font-weight:900; margin:0;">
                Coming Next
            </h3>

            <p style="color:#d1d5db; margin-top:8px;">
                We can add direct Excel exports for leave, payroll, employee directory, and document audit next.
            </p>
        </div>

    </div>

    <style>
        .report-card {
            background:white;
            border:1px solid #e5e7eb;
            border-radius:20px;
            padding:24px;
            box-shadow:0 8px 24px rgba(15,23,42,.05);
        }

        .report-card h3 {
            font-size:20px;
            font-weight:900;
            margin:14px 0 8px;
            color:#111827;
        }

        .report-card p {
            color:#6b7280;
            line-height:1.5;
            min-height:78px;
        }

        .report-icon {
            width:58px;
            height:58px;
            border-radius:18px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:white;
            box-shadow:0 8px 18px rgba(0,0,0,.12);
        }

        .report-icon.blue {
            background:#2563eb;
        }

        .report-icon.green {
            background:#16a34a;
        }

        .report-icon.amber {
            background:#f59e0b;
        }

        .report-icon.purple {
            background:#7c3aed;
        }

        .report-icon.dark {
            background:#111827;
        }

        .report-button {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            background:#111827;
            color:white;
            padding:10px 14px;
            border-radius:12px;
            text-decoration:none;
            font-weight:800;
            margin-top:10px;
            margin-right:6px;
            box-shadow:0 6px 14px rgba(17,24,39,.16);
            transition:.15s ease;
            border:0;
        }

        .report-button:hover {
            transform:translateY(-1px);
            box-shadow:0 10px 22px rgba(17,24,39,.22);
        }

        .report-button.green {
            background:#16a34a;
            box-shadow:0 6px 14px rgba(22,163,74,.18);
        }

        .report-button.green:hover {
            box-shadow:0 10px 22px rgba(22,163,74,.25);
        }
    </style>
</x-app-layout>