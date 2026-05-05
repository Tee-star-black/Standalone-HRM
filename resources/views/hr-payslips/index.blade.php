<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            HR Payslips
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:8px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:20px; font-weight:bold; margin-bottom:16px;">
                    Create / Update Payslip
                </h3>

                <form method="POST" action="{{ route('hr-payslips.store') }}">
                    @csrf

                    <div style="margin-bottom:14px;">
                        <label style="font-weight:600;">Employee</label><br>
                        <select name="employee_id" required style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                            <option value="">Select employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->employee_number }} - {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                        <div>
                            <label style="font-weight:600;">Year</label><br>
                            <input name="year" type="number" value="{{ now()->year }}" required style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                        </div>

                        <div>
                            <label style="font-weight:600;">Month</label><br>
                            <input name="month" type="number" min="1" max="12" value="{{ now()->month }}" required style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">
                        <div>
                            <label style="font-weight:600;">Basic Salary</label><br>
                            <input name="basic_salary" type="number" step="0.01" required style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                        </div>

                        <div>
                            <label style="font-weight:600;">Allowances</label><br>
                            <input name="allowances" type="number" step="0.01" value="0" style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                        </div>

                        <div>
                            <label style="font-weight:600;">Deductions</label><br>
                            <input name="deductions" type="number" step="0.01" value="0" style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                        </div>

                        <div>
                            <label style="font-weight:600;">Tax</label><br>
                            <input name="tax" type="number" step="0.01" value="0" style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                        </div>
                    </div>

                    <div style="margin-bottom:14px;">
                        <label style="font-weight:600;">Status</label><br>
                        <select name="status" required style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div style="margin-bottom:18px;">
                        <label style="font-weight:600;">Notes</label><br>
                        <textarea name="notes" rows="3" style="width:100%; padding:10px; border:1px solid #999; border-radius:6px;"></textarea>
                    </div>

                    <button type="submit"
                        style="background:#1f2937; color:white; padding:12px 20px; border-radius:6px; font-weight:600; border:0; cursor:pointer;">
                        Save Payslip
                    </button>
                </form>
            </div>

            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:20px; font-weight:bold; margin-bottom:16px;">
                    All Payslips
                </h3>

                @if ($payslips->isEmpty())
                    <p>No payslips created yet.</p>
                @else
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Employee</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Period</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Net Pay</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Status</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($payslips as $payslip)
                                <tr>
                                    <td style="padding:10px;">{{ $payslip->employee->full_name }}</td>
                                    <td style="padding:10px;">{{ $payslip->period }}</td>
                                    <td style="padding:10px;">{{ number_format($payslip->net_pay, 2) }}</td>
                                    <td style="padding:10px;">
                                        @if ($payslip->status === 'published')
                                            <span style="background:#dcfce7; color:#166534; padding:6px 10px; border-radius:999px; font-size:13px;">
                                                Published
                                            </span>
                                        @else
                                            <span style="background:#fef3c7; color:#92400e; padding:6px 10px; border-radius:999px; font-size:13px;">
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:10px;">
                                        @if ($payslip->status !== 'published')
                                            <form method="POST" action="{{ route('hr-payslips.publish', $payslip) }}">
                                                @csrf
                                                <button type="submit"
                                                    style="background:#16a34a; color:white; padding:8px 12px; border-radius:6px; border:0; font-weight:600; cursor:pointer;">
                                                    Publish
                                                </button>
                                            </form>
                                        @else
                                            <span style="color:#6b7280;">No action</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>