<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Leave
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:8px;">
                    {{ session('status') }}
                </div>
            @endif

            <div style="background:#dbeafe; border:2px solid #2563eb; padding:24px; border-radius:12px;">
                <h1 style="font-size:24px; font-weight:bold; margin-bottom:16px;">
                    Request Leave
                </h1>

                <form method="POST" action="{{ route('my-leave.store') }}">
                    @csrf

                    <div style="margin-bottom:12px;">
                        <label>Leave Type</label><br>
                        <select name="leave_type_id" required style="width:100%; padding:10px; border:1px solid #999;">
                            <option value="">Select leave type</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom:12px;">
                        <label>Start Date</label><br>
                        <input type="date" name="start_date" required style="width:100%; padding:10px; border:1px solid #999;">
                    </div>

                    <div style="margin-bottom:12px;">
                        <label>End Date</label><br>
                        <input type="date" name="end_date" required style="width:100%; padding:10px; border:1px solid #999;">
                    </div>

                    <div style="margin-bottom:12px;">
                        <label>Reason</label><br>
                        <textarea name="reason" rows="3" style="width:100%; padding:10px; border:1px solid #999;"></textarea>
                    </div>

                    <button type="submit" style="background:#16a34a; color:white; padding:14px 24px; border-radius:8px; font-weight:bold;">
                        SUBMIT LEAVE REQUEST
                    </button>
                </form>
            </div>

            <div style="background:white; padding:24px; border-radius:12px; box-shadow:0 1px 3px #ddd;">
                <h2 style="font-size:22px; font-weight:bold; margin-bottom:16px;">My Leave Requests</h2>

                @if ($requests->isEmpty())
                    <p>No leave requests yet.</p>
                @else
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Type</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Dates</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Days</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Status</th>
                                <th style="border-bottom:1px solid #ddd; padding:10px; text-align:left;">Manager Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td style="padding:10px;">{{ $request->leaveType->name }}</td>
                                    <td style="padding:10px;">
                                        {{ $request->start_date->format('Y-m-d') }}
                                        to
                                        {{ $request->end_date->format('Y-m-d') }}
                                    </td>
                                    <td style="padding:10px;">{{ $request->days_requested }}</td>
                                    <td style="padding:10px;">
                                        @if ($request->status === 'approved')
                                            <span style="background:#dcfce7; color:#166534; padding:4px 8px; border-radius:999px;">Approved</span>
                                        @elseif ($request->status === 'rejected')
                                            <span style="background:#fee2e2; color:#991b1b; padding:4px 8px; border-radius:999px;">Rejected</span>
                                        @else
                                            <span style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:999px;">Pending</span>
                                        @endif
                                    </td>
                                    <td style="padding:10px;">{{ $request->manager_comment ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>