<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Preview Document
            </h2>

            <a href="{{ route('my-documents.index') }}"
               class="px-4 py-2 bg-gray-200 text-gray-800 rounded">
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white p-6 rounded shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ $document->original_name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ $document->mime_type }} • {{ number_format($document->size / 1024, 1) }} KB
                        </p>
                    </div>

                    <a href="{{ route('my-documents.download', $document) }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded">
                        Download
                    </a>
                </div>

                @if (str_contains($document->mime_type ?? '', 'pdf'))
                    <iframe
                        src="{{ $url }}"
                        style="width: 100%; height: 750px; border: 1px solid #ddd; border-radius: 8px;">
                    </iframe>
                @elseif (str_starts_with($document->mime_type ?? '', 'image/'))
                    <div class="flex justify-center bg-gray-100 rounded p-6">
                        <img src="{{ $url }}"
                             alt="{{ $document->original_name }}"
                             style="max-width: 100%; max-height: 750px;">
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded">
                        Preview is not available for this file type. Please download the document.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>