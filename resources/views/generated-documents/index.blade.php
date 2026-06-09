<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Generated Documents
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    {{ $isHrView ?? false ? 'All generated employee documents.' : 'Your generated employee documents.' }}
                </p>
            </div>

            @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin']))
                <a href="{{ route('document-wizard.index') }}"
                   style="background:#2563eb; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Document Wizard
                </a>
            @endif
        </div>
    </x-slot>

    <div style="display:grid; gap:18px;">

        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px;">
                {{ session('status') }}
            </div>
        @endif

        <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">

            @if($documents->isEmpty())
                <p style="color:#6b7280;">No generated documents yet.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Title</th>
                            @if($isHrView ?? false)
                                <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Employee</th>
                            @endif
                            <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Type</th>
                            <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">File</th>
                            <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Generated</th>
                            <th style="padding:12px; border-bottom:1px solid #e5e7eb; text-align:left;">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($documents as $document)
                            @php
                                $extension = strtolower(pathinfo($document->path, PATHINFO_EXTENSION));
                            @endphp

                            <tr>
                                <td style="padding:14px; border-bottom:1px solid #f3f4f6;">
                                    <strong>{{ $document->title }}</strong>
                                </td>

                                @if($isHrView ?? false)
                                    <td style="padding:14px; border-bottom:1px solid #f3f4f6;">
                                        {{ $document->employee?->full_name ?? 'Unknown employee' }}
                                    </td>
                                @endif

                                <td style="padding:14px; border-bottom:1px solid #f3f4f6;">
                                    {{ str_replace('_', ' ', ucfirst($document->type)) }}
                                </td>

                                <td style="padding:14px; border-bottom:1px solid #f3f4f6;">
                                    <span style="background:#eff6ff; color:#1e40af; padding:5px 9px; border-radius:999px; font-size:12px; font-weight:900;">
                                        {{ strtoupper($extension ?: 'FILE') }}
                                    </span>
                                </td>

                                <td style="padding:14px; border-bottom:1px solid #f3f4f6;">
                                    {{ $document->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td style="padding:14px; border-bottom:1px solid #f3f4f6;">
                                    <a href="{{ route('generated-documents.download', $document) }}"
                                       style="background:#16a34a; color:white; padding:8px 12px; border-radius:10px; text-decoration:none; font-weight:800;">
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