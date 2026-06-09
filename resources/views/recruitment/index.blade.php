<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Recruitment
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Manage vacancies, candidates, applications, CVs, offers, and hiring conversions.
                </p>
            </div>

            <a href="{{ route('recruitment.export') }}"
               style="background:#16a34a; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                Export Recruitment Report
            </a>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        @if (session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:8px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px;" class="dashboard-grid">
            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Create Vacancy</h3>

                <form method="POST" action="{{ route('recruitment.vacancies.store') }}" style="display:grid; gap:12px;">
                    @csrf

                    <select name="job_id" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                        <option value="">Optional linked job</option>
                        @foreach ($jobs as $job)
                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                        @endforeach
                    </select>

                    <input name="title" required placeholder="Vacancy title" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="code" required placeholder="Code e.g. VAC001" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="department" placeholder="Department" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="location" placeholder="Location" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="employment_type" placeholder="Employment type" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="open_positions" type="number" min="1" value="1" required style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="closing_date" type="date" style="padding:10px; border:1px solid #ccc; border-radius:6px;">

                    <select name="status" required style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                        <option value="open">Open</option>
                        <option value="on_hold">On Hold</option>
                        <option value="closed">Closed</option>
                    </select>

                    <textarea name="description" placeholder="Description" style="padding:10px; border:1px solid #ccc; border-radius:6px;"></textarea>

                    <button style="background:#1f2937; color:white; padding:12px 18px; border-radius:6px; font-weight:600;">
                        Save Vacancy
                    </button>
                </form>
            </div>

            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Add Candidate</h3>

                <form method="POST" action="{{ route('recruitment.candidates.store') }}" enctype="multipart/form-data" style="display:grid; gap:12px;">
                    @csrf

                    <input name="first_name" required placeholder="First name" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="last_name" required placeholder="Last name" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="email" type="email" required placeholder="Email" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="phone" placeholder="Phone" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <input name="source" placeholder="Source e.g. LinkedIn" style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <textarea name="notes" placeholder="Notes" style="padding:10px; border:1px solid #ccc; border-radius:6px;"></textarea>

                    <div>
                        <label style="font-weight:600;">CV / Resume</label>
                        <input type="file" name="cv" accept=".pdf,.doc,.docx,image/*"
                               style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                    </div>

                    <button style="background:#1f2937; color:white; padding:12px 18px; border-radius:6px; font-weight:600;">
                        Save Candidate
                    </button>
                </form>
            </div>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Record Application</h3>

            <form method="POST" action="{{ route('recruitment.applications.store') }}" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                @csrf

                <select name="vacancy_id" required style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <option value="">Select vacancy</option>
                    @foreach ($vacancies as $vacancy)
                        <option value="{{ $vacancy->id }}">{{ $vacancy->title }}</option>
                    @endforeach
                </select>

                <select name="candidate_id" required style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <option value="">Select candidate</option>
                    @foreach ($candidates as $candidate)
                        <option value="{{ $candidate->id }}">{{ $candidate->full_name }}</option>
                    @endforeach
                </select>

                <select name="stage" required style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <option value="applied">Applied</option>
                    <option value="screening">Screening</option>
                    <option value="interview">Interview</option>
                    <option value="offer">Offer</option>
                    <option value="hired">Hired</option>
                    <option value="rejected">Rejected</option>
                </select>

                <select name="status" required style="padding:10px; border:1px solid #ccc; border-radius:6px;">
                    <option value="active">Active</option>
                    <option value="hired">Hired</option>
                    <option value="rejected">Rejected</option>
                    <option value="withdrawn">Withdrawn</option>
                </select>

                <input name="score" type="number" min="0" max="100" step="0.01" placeholder="Score" style="padding:10px; border:1px solid #ccc; border-radius:6px;">

                <textarea name="notes" placeholder="Application notes" style="padding:10px; border:1px solid #ccc; border-radius:6px;"></textarea>

                <button style="grid-column:1 / -1; background:#2563eb; color:white; padding:12px 18px; border-radius:6px; font-weight:600;">
                    Save Application
                </button>
            </form>
        </div>

        <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
            <h3 style="font-size:18px; font-weight:700; margin-bottom:16px;">Applications Pipeline</h3>

            @if ($applications->isEmpty())
                <p>No applications yet.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Candidate</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">CV</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Vacancy</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Stage</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Status</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Score</th>
                            <th style="padding:10px; border-bottom:1px solid #ddd; text-align:left;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($applications as $application)
                            @php
                                $cv = $application->candidate->documents
                                    ->filter(fn ($document) => str_starts_with($document->original_name, 'CV -'))
                                    ->sortByDesc('created_at')
                                    ->first();

                                $offerLetter = $application->candidate->documents
                                    ->where('original_name', 'Offer Letter - ' . $application->candidate->full_name)
                                    ->sortByDesc('created_at')
                                    ->first();
                            @endphp

                            <tr>
                                <td style="padding:14px;">{{ $application->candidate->full_name }}</td>

                                <td style="padding:14px;">
                                    @if ($cv)
                                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                            <a href="{{ route('recruitment.documents.file', $cv) }}"
                                               target="_blank"
                                               style="color:#2563eb; font-weight:700; text-decoration:underline;">
                                                View
                                            </a>

                                            <a href="{{ route('recruitment.documents.download', $cv) }}"
                                               style="color:#16a34a; font-weight:700; text-decoration:underline;">
                                                Download
                                            </a>
                                        </div>
                                    @else
                                        <span style="color:#6b7280;">No CV</span>
                                    @endif
                                </td>

                                <td style="padding:14px;">{{ $application->vacancy->title }}</td>
                                <td style="padding:14px;">{{ ucfirst($application->stage) }}</td>
                                <td style="padding:14px;">{{ ucfirst($application->status) }}</td>
                                <td style="padding:14px;">{{ $application->score ?? '-' }}</td>

                                <td style="padding:14px; min-width:280px;">
                                    <div style="display:flex; flex-direction:column; gap:12px;">

                                        <form method="POST"
                                              action="{{ route('recruitment.applications.stage', $application) }}"
                                              style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                                            @csrf

                                            <select name="stage"
                                                    style="padding:8px 10px; border:1px solid #d1d5db; border-radius:10px; background:white;">
                                                @foreach (['applied','screening','interview','offer','hired','rejected'] as $stage)
                                                    <option value="{{ $stage }}" @selected($application->stage === $stage)>
                                                        {{ ucfirst($stage) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <select name="status"
                                                    style="padding:8px 10px; border:1px solid #d1d5db; border-radius:10px; background:white;">
                                                @foreach (['active','hired','rejected','withdrawn'] as $status)
                                                    <option value="{{ $status }}" @selected($application->status === $status)>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button style="background:#111827; color:white; padding:9px 14px; border-radius:10px; border:0; font-weight:700; cursor:pointer;">
                                                Save
                                            </button>
                                        </form>

                                        @if (! $application->offer_letter_generated_at)
                                            <form method="POST"
                                                  action="{{ route('recruitment.applications.offer-letter', $application) }}">
                                                @csrf

                                                <button type="submit"
                                                        style="background:#7c3aed; color:white; padding:9px 14px; border-radius:10px; border:0; font-weight:700; cursor:pointer;">
                                                    Generate Offer
                                                </button>
                                            </form>
                                        @else
                                            <div style="display:inline-flex; align-items:center; gap:6px; background:#ede9fe; color:#6d28d9; padding:7px 12px; border-radius:999px; width:fit-content; font-size:12px; font-weight:800;">
                                                Offer Generated
                                            </div>

                                            @if ($offerLetter)
                                                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                                    <a href="{{ route('recruitment.documents.file', $offerLetter) }}"
                                                       target="_blank"
                                                       style="background:#2563eb; color:white; padding:9px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                                                        View Offer
                                                    </a>

                                                    <a href="{{ route('recruitment.documents.download', $offerLetter) }}"
                                                       style="background:#16a34a; color:white; padding:9px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                                                        Download
                                                    </a>
                                                </div>
                                            @endif
                                        @endif

                                        @if ($application->converted_employee_id)
                                            <div style="background:#dcfce7; color:#166534; padding:8px 12px; border-radius:999px; display:inline-block; font-size:12px; font-weight:800; width:fit-content;">
                                                Converted to EMP #{{ $application->converted_employee_id }}
                                            </div>
                                        @elseif ($application->stage === 'hired' || $application->status === 'hired')
                                            <form method="POST"
                                                  action="{{ route('recruitment.applications.hire', $application) }}">
                                                @csrf

                                                <button type="submit"
                                                        style="background:#16a34a; color:white; padding:9px 14px; border-radius:10px; border:0; font-weight:700; cursor:pointer;">
                                                    Convert to Employee
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-app-layout>