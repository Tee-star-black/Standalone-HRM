@php
    $documentType = $documentType ?? ($data['document_type'] ?? 'document');
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ str_replace('_', ' ', ucfirst($documentType)) }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            line-height: 1.6;
        }

        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }

        .logo {
            max-width: 140px;
            max-height: 70px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
        }

        .muted {
            color: #6b7280;
            font-size: 11px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 18px;
            text-transform: uppercase;
            color: #0f172a;
        }

        .section {
            margin-top: 18px;
        }

        .label {
            font-weight: bold;
            width: 180px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        th {
            background: #0f172a;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .total-row {
            background: #e5e7eb;
            font-weight: bold;
        }

        .signature-block {
            margin-top: 45px;
        }

        .signature-image {
            max-width: 170px;
            max-height: 70px;
            margin-bottom: 6px;
        }

        .signature-line {
            border-top: 1px solid #111827;
            width: 260px;
            padding-top: 6px;
        }

        .footer {
            margin-top: 45px;
            border-top: 1px solid #d1d5db;
            padding-top: 8px;
            color: #6b7280;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        @if($company->company_logo_path)
            <img src="{{ public_path('storage/' . $company->company_logo_path) }}" class="logo">
        @endif

        <div class="company-name">{{ $company->company_name ?? 'Company Name' }}</div>

        @if($company->company_address)
            <div class="muted">{{ $company->company_address }}</div>
        @endif

        <div class="muted">
            {{ $company->company_email }}
            @if($company->company_email && $company->company_phone)
                |
            @endif
            {{ $company->company_phone }}
        </div>
    </div>

    @if($documentType === 'offer_letter')
        <div class="title">Formal Offer of Employment</div>

        <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>

        <p>
            <strong>To:</strong><br>
            {{ $candidate?->full_name ?? 'Candidate Name' }}<br>
            {{ $candidate?->email }}<br>
            {{ $candidate?->phone }}<br>
            {{ $data['candidate_address'] ?? '' }}
        </p>

        <p>
            <strong>RE: Formal Offer of Employment – {{ $data['position_title'] ?? '' }}</strong>
        </p>

        <p>Dear {{ $candidate?->first_name ?? $candidate?->full_name ?? 'Candidate' }},</p>

        <p>
            On behalf of {{ $company->company_name }}, we are delighted to offer you the position of
            <strong>{{ $data['position_title'] ?? '' }}</strong>.
        </p>

        <div class="section">
            <p><span class="label">Position Title:</span> {{ $data['position_title'] ?? '' }}</p>
            <p><span class="label">Commencement Date:</span> {{ $data['start_date'] ?? '' }}</p>
            <p><span class="label">Reporting Line:</span> {{ $data['manager_name'] ?? '' }}</p>
            <p><span class="label">Remuneration:</span> {{ $data['salary'] ?? '' }} gross monthly salary</p>
            <p><span class="label">Offer Expiry:</span> {{ $data['offer_expiry_date'] ?? '' }}</p>
        </div>

        <p>
            This offer is subject to verification of credentials, references, legal eligibility,
            and compliance with company confidentiality and POPIA requirements.
        </p>

        <p>
            To accept this offer, please sign and return this letter by the expiry date stated above.
        </p>

        <p>We look forward to welcoming you to {{ $company->company_name }}.</p>

        @include('document-wizard.partials.signature')

        <div class="section">
            <p><strong>Candidate Acceptance</strong></p>
            <p>
                I, {{ $candidate?->full_name ?? 'Candidate Name' }}, hereby accept the offer of employment under the terms stated above.
            </p>

            <br><br>
            <p>_____________________________<br>Candidate Signature</p>
            <p>Date: _______________________</p>
        </div>
    @endif

    @if($documentType === 'salary_confirmation')
        <div class="title">Salary Confirmation Letter</div>

        <p><strong>Date:</strong> {{ now()->format('d M Y') }}</p>

        <p>To whom it may concern,</p>

        <p>
            This letter serves to confirm that <strong>{{ $employee?->full_name ?? 'Employee Name' }}</strong>,
            employee number <strong>{{ $employee?->employee_number ?? '-' }}</strong>, is employed by
            <strong>{{ $company->company_name }}</strong>.
        </p>

        <p>
            The employee currently holds the position of
            <strong>{{ $data['position_title'] ?: ($employee?->job_title ?? '') }}</strong>.
        </p>

        <p>
            Their current gross monthly salary is <strong>{{ $data['salary'] ?? '' }}</strong>.
        </p>

        @if(!empty($data['purpose']))
            <p>This confirmation is issued for the purpose of <strong>{{ $data['purpose'] }}</strong>.</p>
        @endif

        @if(!empty($data['recipient_name']))
            <p>Recipient: <strong>{{ $data['recipient_name'] }}</strong></p>
        @endif

        <p>Please contact {{ $company->company_email }} should further verification be required.</p>

        @include('document-wizard.partials.signature')
    @endif

    @if(in_array($documentType, ['employment_contract', 'supervising_doctor_contract', 'practice_manager_contract', 'clinical_associate_contract']))
        <div class="title">
            {{ str_replace('_', ' ', ucfirst($documentType)) }}
        </div>

        <p>
            This employment contract is entered into between <strong>{{ $company->company_name }}</strong>
            and <strong>{{ $employee?->full_name ?? 'Employee Name' }}</strong>.
        </p>

        <div class="section">
            <p><span class="label">Employee Name:</span> {{ $employee?->full_name ?? '' }}</p>
            <p><span class="label">Employee Number:</span> {{ $employee?->employee_number ?? '' }}</p>
            <p><span class="label">Position:</span> {{ $data['position_title'] ?: ($employee?->job_title ?? '') }}</p>
            <p><span class="label">Start Date:</span> {{ $data['start_date'] ?? '' }}</p>
            <p><span class="label">Reporting Line:</span> {{ $data['manager_name'] ?? '' }}</p>
            <p><span class="label">Working Hours:</span> {{ $data['working_hours'] ?? '' }}</p>
            <p><span class="label">Monthly Salary:</span> {{ $data['salary'] ?? '' }}</p>
            <p><span class="label">Probation Period:</span> {{ $data['probation_period'] ?? '' }}</p>
        </div>

        <div class="section">
            <p><strong>Duties and Responsibilities</strong></p>
            <p>
                The employee agrees to perform the duties associated with the position, comply with company policies,
                maintain professional standards, and act in the best interests of {{ $company->company_name }}.
            </p>
        </div>

        <div class="section">
            <p><strong>Confidentiality and POPIA</strong></p>
            <p>
                The employee agrees to maintain confidentiality of company, employee, patient, and operational information
                and to comply with POPIA and all applicable company policies.
            </p>
        </div>

        <div class="section">
            <p><strong>Leave and Benefits</strong></p>
            <p>
                Leave and benefits shall be administered according to South African labour legislation and company policy.
            </p>
        </div>

        <div class="section">
            <p><strong>Termination</strong></p>
            <p>
                Notice periods and termination conditions shall be governed by applicable labour legislation and company policy.
            </p>
        </div>

        @include('document-wizard.partials.signature')

        <div class="section">
            <br><br>
            <p>_____________________________<br>Employee Signature</p>
            <p>Date: _______________________</p>
        </div>
    @endif

    @if($documentType === 'policy_acknowledgement')
        <div class="title">Policy Acknowledgement Form</div>

        <p>
            I, <strong>{{ $employee?->full_name ?? 'Employee Name' }}</strong>, acknowledge that I have received, read,
            and understood the following company policy:
        </p>

        <p style="font-size:16px;">
            <strong>{{ $data['policy_name'] ?? 'Company Policy' }}</strong>
        </p>

        <p>
            I agree to comply with this policy and understand that failure to do so may result in disciplinary action.
        </p>

        <div class="section">
            <p><span class="label">Employee Name:</span> {{ $employee?->full_name ?? '' }}</p>
            <p><span class="label">Employee Number:</span> {{ $employee?->employee_number ?? '' }}</p>
            <p><span class="label">Date:</span> {{ now()->format('d M Y') }}</p>
        </div>

        <br><br>
        <p>_____________________________<br>Employee Signature</p>

        @include('document-wizard.partials.signature')
    @endif

    @if($documentType === 'payslip')
        <div class="title">Confidential Payslip</div>

        <table>
            <tr>
                <td><strong>Company Name</strong></td>
                <td>{{ $company->company_name }}</td>
                <td><strong>Employee Name</strong></td>
                <td>{{ $employee?->full_name ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Employee Number</strong></td>
                <td>{{ $employee?->employee_number ?? '' }}</td>
                <td><strong>Job Title</strong></td>
                <td>{{ $employee?->job_title ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Pay Period</strong></td>
                <td>{{ $data['pay_period'] ?? '' }}</td>
                <td><strong>Email</strong></td>
                <td>{{ $employee?->email ?? '' }}</td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Earnings Component</th>
                    <th>Amount</th>
                    <th>Deductions</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Monthly Salary</td>
                    <td>R {{ number_format($earnings['basic_salary'], 2) }}</td>
                    <td>PAYE</td>
                    <td>R {{ number_format($deductions['paye'], 2) }}</td>
                </tr>
                <tr>
                    <td>Overtime / Roster Shifts</td>
                    <td>R {{ number_format($earnings['overtime'], 2) }}</td>
                    <td>UIF</td>
                    <td>R {{ number_format($deductions['uif'], 2) }}</td>
                </tr>
                <tr>
                    <td>Allowance</td>
                    <td>R {{ number_format($earnings['allowance'], 2) }}</td>
                    <td>Medical Aid</td>
                    <td>R {{ number_format($deductions['medical_aid'], 2) }}</td>
                </tr>
                <tr>
                    <td>Bonus</td>
                    <td>R {{ number_format($earnings['bonus'], 2) }}</td>
                    <td>Loan / Other Deduction</td>
                    <td>R {{ number_format($deductions['loan_deduction'], 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Gross Earnings</td>
                    <td>R {{ number_format($gross, 2) }}</td>
                    <td>Total Deductions</td>
                    <td>R {{ number_format($totalDeductions, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3">Net Pay Distribution</td>
                    <td>R {{ number_format($netPay, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p class="muted">
            This document constitutes an electronic payroll notification statement.
        </p>
    @endif

    <div class="footer">
        Generated by Standalone HRM on {{ now()->format('d M Y H:i') }}.
    </div>
</body>
</html>