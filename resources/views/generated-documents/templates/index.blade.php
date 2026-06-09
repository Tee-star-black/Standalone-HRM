<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Generated Documents
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Generate official HR letters and download your document history.
                </p>
            </div>

            <a href="{{ route('preferences.index') }}"
               style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:700;">
                Back to Preferences
            </a>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        @if (session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="background:white; border:1px solid #e5e7eb; padding:24px; border-radius:20px; box-shadow:0 8px 24px rgba(15,23,42,.05);">
            <h3 style="font-size:20px; font-weight:900; margin:0 0 14px;">
                Generate New Document
            </h3>

            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px;" class="dashboard-grid">
                <div style="border:1px solid #e5e7eb; border-radius:16px; padding:18px;">
                    <h4 style="font-size:18px; font-weight:900; margin:0;">
                        Employment Confirmation
                    </h4>
                    <p style="color:#6b7280; line-height:1.5;">
                        Generate a PDF confirming your current employment details.
                    </p>

                    <form method="POST" action="{{ route('generated-documents.employment-confirmation') }}">
                        @csrf
                        <button type="submit"
                                style="background:#2563eb; color:white; padding:10px 14px; border:0; border-radius:10px; font-weight:800; cursor:pointer;">
                            Generate PDF
                        </button>
                    </form>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:16px; padding:18px; opacity:.65;">
                    <h4 style="font-size:18px; font-weight:900; margin:0;">
                        Salary Confirmation
                    </h4>
                    <p style="color:#6b7280; line-height:1.5;">
                        Coming soon: generate salary confirmation letters.
                    </p>
                    <span style="color:#6b7280; font-weight:800;">Coming Soon</span>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:16px; padding:18px; opacity:.65;">
                    <h4 style="font-size:18px; font-weight:900; margin:0;">
                        Leave Confirmation
                    </h4>
                    <p style="color:#6b7280; line-height:1.5;">
                        Coming soon: generate leave confirmation letters.
                    </p>
                    <span style="color:#6b7280; font-weight:800;">Coming Soon</span>
                </div>
            </div>
        </div>

        <div style="background:white; border:1px solid #e5e7eb; padding:24px; border-radius:20px; box-shadow:0 8px 24px rgba(15,23,42,.05);">
            <h3 style="font-size:20px; font-weight:900; margin:0 0 14px;">
                Document History
            </h3>

            @if ($documents->isEmpty())
                <p style="color:#6b7280;">No generated documents yet.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Title</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Type</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Generated</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $document)
                            <tr>
                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">{{ $document->title }}</td>
                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">{{ str_replace('_', ' ', ucfirst($document->type)) }}</td>
                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    <a href="{{ route('generated-documents.download', $document) }}"
                                       style="color:#2563eb; font-weight:800; text-decoration:underline;">
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</x-app-layout>