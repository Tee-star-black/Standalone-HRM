<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Document Wizard
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Select a document type, complete the required fields, and generate the document.
                </p>
            </div>

            <a href="{{ route('generated-documents.index') }}"
               style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                Generated Documents
            </a>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns:340px 1fr; gap:24px;" class="dashboard-grid">

        <div style="background:white; padding:22px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05); align-self:start;">
            <h3 style="font-size:20px; font-weight:900; margin:0 0 14px; color:#111827;">
                Company Signature
            </h3>

            <p style="color:#6b7280; line-height:1.6;">
                These details appear automatically on generated documents.
            </p>

            <div style="margin-top:16px; display:grid; gap:10px;">
                <div style="background:#f9fafb; padding:12px; border-radius:14px;">
                    <strong>{{ $company->company_name }}</strong>
                    <div style="color:#6b7280; margin-top:4px;">
                        {{ $company->company_address }}
                    </div>
                </div>

                <div style="background:#f9fafb; padding:12px; border-radius:14px;">
                    <strong>{{ $company->ceo_name ?? 'CEO name not set' }}</strong>
                    <div style="color:#6b7280; margin-top:4px;">
                        {{ $company->ceo_title }}
                    </div>

                    @if($company->ceo_signature_path)
                        <img src="{{ asset('storage/' . $company->ceo_signature_path) }}"
                             style="max-width:180px; max-height:70px; margin-top:10px;">
                    @endif
                </div>
            </div>

            <a href="{{ route('document-templates.index') }}"
               style="display:inline-block; margin-top:16px; background:#2563eb; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                Manage Signature
            </a>
        </div>

        <div style="background:white; padding:26px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">

            <form method="POST" action="{{ route('document-wizard.generate') }}" style="display:grid; gap:22px;">
                @csrf

                <div>
                    <h3 style="font-size:22px; font-weight:900; margin:0 0 8px; color:#111827;">
                        1. Choose Document
                    </h3>

                    <select name="document_type" id="document_type" required onchange="showDocumentFields()"
                            style="width:100%; padding:13px; border:1px solid #d1d5db; border-radius:12px; font-weight:700;">
                        <option value="">Select document type</option>
                        @foreach($documentTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="person_panel" style="display:none; background:#f9fafb; border:1px solid #e5e7eb; padding:18px; border-radius:18px;">
                    <h3 style="font-size:18px; font-weight:900; margin:0 0 14px; color:#111827;">
                        2. Select Person
                    </h3>

                    <div id="employee_block">
                        <label style="font-weight:800; display:block; margin-bottom:6px;">Employee</label>
                        <select name="employee_id"
                                style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <option value="">Select employee</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->employee_number }} - {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="candidate_block" style="display:none;">
                        <label style="font-weight:800; display:block; margin-bottom:6px;">Candidate</label>
                        <select name="candidate_id"
                                style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <option value="">Select candidate</option>
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}">
                                    {{ $candidate->full_name }} - {{ $candidate->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="details_panel" style="display:none; background:white; border:1px solid #e5e7eb; padding:20px; border-radius:18px;">
                    <h3 id="details_title" style="font-size:18px; font-weight:900; margin:0 0 14px; color:#111827;">
                        3. Document Details
                    </h3>

                    <div id="shared_fields" style="display:grid; gap:14px;">
                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Position Title</label>
                            <input name="position_title" placeholder="e.g. Practice Manager"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Start Date</label>
                            <input name="start_date" placeholder="e.g. 01 June 2026"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Salary</label>
                            <input name="salary" placeholder="e.g. R25,000"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Manager / Reporting Line</label>
                            <input name="manager_name" placeholder="e.g. Clinical Director"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>
                    </div>

                    <div id="offer_fields" style="display:none; gap:14px; margin-top:14px;">
                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Offer Expiry Date</label>
                            <input name="offer_expiry_date" placeholder="e.g. 5 business days from today"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Candidate Address</label>
                            <input name="candidate_address" placeholder="Residential address"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>
                    </div>

                    <div id="salary_confirmation_fields" style="display:none; gap:14px; margin-top:14px;">
                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Recipient Name</label>
                            <input name="recipient_name" placeholder="e.g. Bank / Embassy / Landlord"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Purpose</label>
                            <input name="purpose" placeholder="e.g. bank application"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>
                    </div>

                    <div id="contract_fields" style="display:none; gap:14px; margin-top:14px;">
                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Working Hours</label>
                            <input name="working_hours" placeholder="e.g. 08:00 to 17:00"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Probation Period</label>
                            <input name="probation_period" placeholder="e.g. 3 months"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>
                    </div>

                    <div id="policy_fields" style="display:none; margin-top:14px;">
                        <label style="font-weight:800; display:block; margin-bottom:6px;">Policy Name</label>
                        <input name="policy_name" placeholder="e.g. POPIA and Confidentiality Policy"
                               style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                    </div>

                    <div id="payslip_fields" style="display:none; gap:14px; margin-top:14px;">
                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">Pay Period</label>
                            <input name="pay_period" placeholder="e.g. May 2026"
                                   style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <div style="display:grid; gap:12px;">
                            <input name="basic_salary" type="number" step="0.01" placeholder="Basic salary"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="overtime" type="number" step="0.01" placeholder="Overtime / shifts"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="allowance" type="number" step="0.01" placeholder="Allowance"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="bonus" type="number" step="0.01" placeholder="Bonus"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="paye" type="number" step="0.01" placeholder="PAYE"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="uif" type="number" step="0.01" placeholder="UIF"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="medical_aid" type="number" step="0.01" placeholder="Medical aid"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <input name="loan_deduction" type="number" step="0.01" placeholder="Loan / other deduction"
                                   style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>
                    </div>
                </div>

                <div id="generate_panel" style="display:none;">
                    <button style="width:100%; background:#16a34a; color:white; padding:14px 16px; border:0; border-radius:14px; font-weight:900; cursor:pointer; font-size:15px;">
                        Generate Document
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function setDisplay(id, value) {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = value;
            }
        }

        function hideAllSpecificFields() {
            setDisplay('offer_fields', 'none');
            setDisplay('salary_confirmation_fields', 'none');
            setDisplay('contract_fields', 'none');
            setDisplay('policy_fields', 'none');
            setDisplay('payslip_fields', 'none');
        }

        function showDocumentFields() {
            const type = document.getElementById('document_type').value;
            const detailsTitle = document.getElementById('details_title');

            if (!type) {
                setDisplay('person_panel', 'none');
                setDisplay('details_panel', 'none');
                setDisplay('generate_panel', 'none');
                return;
            }

            setDisplay('person_panel', 'block');
            setDisplay('details_panel', 'block');
            setDisplay('generate_panel', 'block');

            setDisplay('candidate_block', 'none');
            setDisplay('employee_block', 'block');
            setDisplay('shared_fields', 'grid');

            hideAllSpecificFields();

            if (type === 'offer_letter') {
                setDisplay('employee_block', 'none');
                setDisplay('candidate_block', 'block');
                setDisplay('offer_fields', 'grid');
                detailsTitle.textContent = '3. Offer Letter Details';
            } else if (type === 'salary_confirmation') {
                setDisplay('salary_confirmation_fields', 'grid');
                detailsTitle.textContent = '3. Salary Confirmation Details';
            } else if (
                type === 'employment_contract' ||
                type === 'supervising_doctor_contract' ||
                type === 'practice_manager_contract' ||
                type === 'clinical_associate_contract'
            ) {
                setDisplay('contract_fields', 'grid');
                detailsTitle.textContent = '3. Contract Details';
            } else if (type === 'policy_acknowledgement') {
                setDisplay('shared_fields', 'none');
                setDisplay('policy_fields', 'block');
                detailsTitle.textContent = '3. Policy Details';
            } else if (type === 'payslip') {
                setDisplay('shared_fields', 'none');
                setDisplay('payslip_fields', 'grid');
                detailsTitle.textContent = '3. Payslip Details';
            } else {
                detailsTitle.textContent = '3. Document Details';
            }
        }

        document.addEventListener('DOMContentLoaded', showDocumentFields);
    </script>
</x-app-layout>