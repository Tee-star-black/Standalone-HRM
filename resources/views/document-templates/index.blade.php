<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Document Templates
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Manage reusable HR templates, uploaded Word files, company logo, CEO signature, and generated documents.
                </p>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('document-wizard.index') }}"
                   style="background:#2563eb; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Document Wizard
                </a>

                <a href="{{ route('generated-documents.index') }}"
                   style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Generated Documents
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="display:grid; grid-template-columns:380px 1fr; gap:24px;" class="dashboard-grid">

            <div style="display:grid; gap:20px; align-self:start;">

                <div style="background:white; padding:22px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                    <h3 style="font-size:20px; font-weight:900; margin:0 0 16px; color:#111827;">
                        Company Document Settings
                    </h3>

                    <form method="POST"
                          action="{{ route('document-templates.company-settings') }}"
                          enctype="multipart/form-data"
                          style="display:grid; gap:12px;">
                        @csrf

                        <input name="company_name"
                               value="{{ old('company_name', $company->company_name) }}"
                               placeholder="Company name"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">
                                Company Logo
                            </label>

                            @if($company->company_logo_path)
                                <div style="background:#f9fafb; padding:12px; border-radius:12px; margin-bottom:10px;">
                                    <img src="{{ asset('storage/' . $company->company_logo_path) }}"
                                         alt="Company Logo"
                                         style="max-width:180px; max-height:90px;">
                                </div>
                            @endif

                            <input type="file"
                                   name="company_logo"
                                   accept="image/*"
                                   style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <input name="ceo_name"
                               value="{{ old('ceo_name', $company->ceo_name) }}"
                               placeholder="CEO name"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <input name="ceo_title"
                               value="{{ old('ceo_title', $company->ceo_title) }}"
                               placeholder="CEO title"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <textarea name="company_address"
                                  placeholder="Company address"
                                  rows="3"
                                  style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">{{ old('company_address', $company->company_address) }}</textarea>

                        <input name="company_email"
                               value="{{ old('company_email', $company->company_email) }}"
                               placeholder="Company email"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <input name="company_phone"
                               value="{{ old('company_phone', $company->company_phone) }}"
                               placeholder="Company phone"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">
                                CEO Signature Image
                            </label>

                            @if($company->ceo_signature_path)
                                <div style="background:#f9fafb; padding:12px; border-radius:12px; margin-bottom:10px;">
                                    <img src="{{ asset('storage/' . $company->ceo_signature_path) }}"
                                         alt="CEO Signature"
                                         style="max-width:220px; max-height:80px;">
                                </div>
                            @endif

                            <input type="file"
                                   name="ceo_signature"
                                   accept="image/*"
                                   style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                        </div>

                        <button style="background:#2563eb; color:white; padding:11px 14px; border:0; border-radius:10px; font-weight:900;">
                            Save Company Settings
                        </button>
                    </form>
                </div>

                <div style="background:white; padding:22px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                    <h3 style="font-size:20px; font-weight:900; margin:0 0 16px; color:#111827;">
                        Create Template
                    </h3>

                    <form method="POST"
                          action="{{ route('document-templates.store') }}"
                          enctype="multipart/form-data"
                          style="display:grid; gap:12px;">
                        @csrf

                        <input name="name"
                               required
                               placeholder="Template name e.g. Salary Confirmation Letter"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <select name="type"
                                required
                                style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                            <option value="employment_confirmation">Employment Confirmation</option>
                            <option value="salary_confirmation">Salary Confirmation</option>
                            <option value="offer_letter">Offer Letter</option>
                            <option value="employment_contract">Employment Contract</option>
                            <option value="warning_letter">Warning Letter</option>
                            <option value="promotion_letter">Promotion Letter</option>
                            <option value="policy_acknowledgement">Policy Acknowledgement</option>
                            <option value="payslip">Payslip</option>
                            <option value="general">General</option>
                        </select>

                        <textarea name="description"
                                  rows="2"
                                  placeholder="Short description"
                                  style="padding:11px; border:1px solid #d1d5db; border-radius:10px;"></textarea>

                        <input name="fields"
                               placeholder="Editable fields e.g. salary, manager_name, reason, start_date"
                               style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                        <div>
                            <label style="font-weight:800; display:block; margin-bottom:6px;">
                                Upload Word Template
                            </label>

                            <input type="file"
                                   name="template_file"
                                   accept=".doc,.docx"
                                   style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                            <p style="color:#6b7280; font-size:13px; margin-top:6px;">
                                DOCX placeholders should use ${employee_name}, ${salary}, ${ceo_signature}, ${company_logo}, etc.
                            </p>
                        </div>

                        <textarea name="content"
                                  rows="12"
                                  placeholder="Optional: paste template content with placeholders"
                                  style="padding:11px; border:1px solid #d1d5db; border-radius:10px; font-family:monospace;"></textarea>

                        <div style="background:#f9fafb; border:1px solid #e5e7eb; padding:12px; border-radius:12px; font-size:13px; color:#6b7280;">
                            <strong>Available DOCX placeholders:</strong><br>
                            ${company_logo},
                            ${employee_name},
                            ${employee_number},
                            ${employee_email},
                            ${employee_phone},
                            ${job_title},
                            ${employment_type},
                            ${hire_date},
                            ${company_name},
                            ${company_address},
                            ${company_email},
                            ${company_phone},
                            ${ceo_name},
                            ${ceo_title},
                            ${ceo_signature},
                            ${today}
                        </div>

                        <button style="background:#111827; color:white; padding:11px 14px; border:0; border-radius:10px; font-weight:900;">
                            Save Template
                        </button>
                    </form>
                </div>

            </div>

            <div style="display:grid; gap:18px;">
                @forelse($templates as $template)
                    <div style="background:white; padding:22px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                            <div>
                                <h3 style="font-size:20px; font-weight:900; margin:0; color:#111827;">
                                    {{ $template->name }}
                                </h3>

                                <div style="margin-top:6px; color:#6b7280; font-size:13px;">
                                    {{ str_replace('_', ' ', ucfirst($template->type)) }}
                                </div>

                                @if($template->description)
                                    <p style="color:#6b7280; margin-top:8px;">
                                        {{ $template->description }}
                                    </p>
                                @endif

                                @if($template->file_path)
                                    <div style="margin-top:8px; background:#eff6ff; color:#1e40af; padding:8px 10px; border-radius:10px; font-size:13px; font-weight:800; width:fit-content;">
                                        Word template uploaded: {{ $template->file_original_name }}
                                    </div>

                                    <a href="{{ route('document-templates.download', $template) }}"
                                       style="display:inline-block; margin-top:10px; background:#2563eb; color:white; padding:8px 12px; border-radius:10px; text-decoration:none; font-weight:800;">
                                        Download DOCX Template
                                    </a>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('document-templates.destroy', $template) }}">
                                @csrf
                                @method('DELETE')

                                <button style="background:#fee2e2; color:#991b1b; border:0; padding:8px 12px; border-radius:10px; font-weight:800;">
                                    Delete
                                </button>
                            </form>
                        </div>

                        <div style="margin-top:18px; background:#f9fafb; border:1px solid #e5e7eb; padding:14px; border-radius:14px;">
                            <strong style="display:block; margin-bottom:8px;">Generate From Template</strong>

                            <form method="POST"
                                  action="{{ route('document-templates.generate', $template) }}"
                                  style="display:grid; gap:12px;">
                                @csrf

                                <select name="employee_id"
                                        required
                                        style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                                    <option value="">Select employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->employee_number }} - {{ $employee->full_name }}
                                        </option>
                                    @endforeach
                                </select>

                                @foreach(($template->fields ?? []) as $field)
                                    <input name="custom_fields[{{ $field }}]"
                                           placeholder="{{ str_replace('_', ' ', ucfirst($field)) }}"
                                           style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                                @endforeach

                                <button style="background:#16a34a; color:white; padding:10px 14px; border:0; border-radius:10px; font-weight:900;">
                                    Generate Document
                                </button>
                            </form>
                        </div>

                        @if($template->content)
                            <details style="margin-top:14px;">
                                <summary style="cursor:pointer; font-weight:800; color:#2563eb;">
                                    View Template Content
                                </summary>

                                <pre style="white-space:pre-wrap; background:#111827; color:white; padding:14px; border-radius:14px; overflow:auto; margin-top:10px;">{{ $template->content }}</pre>
                            </details>
                        @endif
                    </div>
                @empty
                    <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb;">
                        <strong>No templates yet.</strong>
                        <p style="color:#6b7280;">Create your first reusable HR document template.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>