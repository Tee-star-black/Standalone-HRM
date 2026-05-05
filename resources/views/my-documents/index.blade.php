<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Documents
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
                <h3 class="text-lg font-semibold mb-4">Upload Document</h3>

                <form method="POST" action="{{ route('my-documents.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Document Type</label>
                        <select name="document_type" required class="w-full border rounded p-2">
                            <option value="">Select document type</option>
                            <option value="Employment Contract">Employment Contract</option>
                            <option value="Offer Letter">Offer Letter</option>
                            <option value="ID / Passport">ID / Passport</option>
                            <option value="Certificate">Certificate</option>
                            <option value="Policy Acknowledgement">Policy Acknowledgement</option>
                            <option value="Other">Other</option>
                        </select>
                        @error('document_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">File</label>
                        <input type="file" name="file" required class="w-full border rounded p-2">
                        @error('file')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            style="background:#2563eb; color:white; padding:12px 20px; border-radius:8px; font-weight:bold;">
                        SUBMIT / UPLOAD DOCUMENT
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">My Uploaded Documents</h3>

                @if ($documents->isEmpty())
                    <p class="text-gray-500">No documents uploaded yet.</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Name</th>
                                <th class="border-b p-2">Type</th>
                                <th class="border-b p-2">Size</th>
                                <th class="border-b p-2">Uploaded</th>
                                <th class="border-b p-2">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($documents as $document)
                                <tr>
                                    <td class="p-2">{{ $document->original_name }}</td>
                                    <td class="p-2">{{ $document->mime_type ?? '-' }}</td>
                                    <td class="p-2">{{ number_format($document->size / 1024, 1) }} KB</td>
                                    <td class="p-2">{{ $document->created_at->format('Y-m-d') }}</td>

                                    <td class="p-2">
                                        <div class="flex items-center gap-3">
                                            <!-- View -->
                                            <a href="{{ route('my-documents.preview', $document) }}"
                                               title="View"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700 hover:bg-green-200">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="w-5 h-5"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            <!-- Download -->
                                            <a href="{{ route('my-documents.download', $document) }}"
                                               title="Download"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="w-5 h-5"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M7 10l5 5m0 0l5-5m-5 5V4" />
                                                </svg>
                                            </a>

                                            <!-- Delete -->
                                            <form method="POST"
                                                  action="{{ route('my-documents.destroy', $document) }}"
                                                  onsubmit="return confirm('Delete this document?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        title="Delete"
                                                        class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-red-100 text-red-700 hover:bg-red-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="w-5 h-5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7" />
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M10 11v6m4-6V4h6v3m-9 0h12" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
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