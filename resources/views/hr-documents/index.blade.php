<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            HR Documents
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 text-green-700 p-4 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Upload Document for Employee</h3>

                <form method="POST" action="{{ route('hr-documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Employee</label>
                        <select name="employee_id" required class="w-full border rounded p-2">
                            <option value="">Select employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">
                                    {{ $employee->employee_number }} - {{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type</label>
                        <select name="document_type" required class="w-full border rounded p-2">
                            <option value="">Select document type</option>
                            <option value="Employment Contract">Employment Contract</option>
                            <option value="Offer Letter">Offer Letter</option>
                            <option value="ID / Passport">ID / Passport</option>
                            <option value="Certificate">Certificate</option>
                            <option value="Policy Acknowledgement">Policy Acknowledgement</option>
                            <option value="Payslip">Payslip</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">File</label>
                        <input type="file" name="file" required class="w-full border rounded p-2">
                    </div>

                    <button type="submit"
                            style="background:#2563eb; color:white; padding:12px 20px; border-radius:8px; font-weight:bold;">
                        UPLOAD FOR EMPLOYEE
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">All Employee Documents</h3>

                @if ($documents->isEmpty())
                    <p class="text-gray-500">No documents uploaded yet.</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Employee</th>
                                <th class="border-b p-2">Document</th>
                                <th class="border-b p-2">Type</th>
                                <th class="border-b p-2">Size</th>
                                <th class="border-b p-2">Uploaded</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td class="p-2">
                                        @if ($document->documentable)
                                            {{ $document->documentable->full_name ?? 'Record #' . $document->documentable->id }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="p-2">{{ $document->original_name }}</td>
                                    <td class="p-2">{{ $document->mime_type ?? '-' }}</td>
                                    <td class="p-2">{{ number_format($document->size / 1024, 1) }} KB</td>
                                    <td class="p-2">{{ $document->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>