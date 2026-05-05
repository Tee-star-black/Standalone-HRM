<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manager Leave Approvals
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-lg font-semibold mb-4">Pending Leave Requests</h3>

                @if($isHr ?? false)
                <p class="text-sm text-gray-500 mb-4">HR mode: showing all pending requests.</p>
                @else
                <p class="text-sm text-gray-500 mb-4">Manager mode: showing direct reports only.</p>
                @endif

                @if ($requests->isEmpty())
                    <p class="text-gray-500">No pending leave requests.</p>
                @else
                    <table class="w-full text-left">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Employee</th>
                                <th class="border-b p-2">Leave Type</th>
                                <th class="border-b p-2">Dates</th>
                                <th class="border-b p-2">Days</th>
                                <th class="border-b p-2">Reason</th>
                                <th class="border-b p-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr class="align-top">
                                    <td class="p-2">{{ $request->employee->full_name }}</td>
                                    <td class="p-2">{{ $request->leaveType->name }}</td>
                                    <td class="p-2">
                                        {{ $request->start_date->format('Y-m-d') }}
                                        to
                                        {{ $request->end_date->format('Y-m-d') }}
                                    </td>
                                    <td class="p-2">{{ $request->days_requested }}</td>
                                    <td class="p-2">{{ $request->reason ?? '-' }}</td>
                                    <td class="p-2">
                                        <form method="POST" action="{{ route('manager-leave.approve', $request) }}" class="mb-2">
                                            @csrf
                                            <input name="comment" placeholder="Comment" class="w-full rounded border-gray-300 mb-2 text-sm">
                                            <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('manager-leave.reject', $request) }}">
                                            @csrf
                                            <input name="comment" placeholder="Reason" class="w-full rounded border-gray-300 mb-2 text-sm">
                                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm">
                                                Reject
                                            </button>
                                        </form>
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