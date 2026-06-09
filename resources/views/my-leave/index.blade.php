<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    My Leave
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Request leave, view balances, and track approvals.
                </p>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; gap:24px;">

        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:12px;">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:14px; border-radius:12px;">
                <strong>Please fix the following:</strong>
                <ul style="margin:8px 0 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section style="display:grid; grid-template-columns:380px 1fr; gap:24px;" class="dashboard-grid">

            <div style="background:white; padding:24px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05); align-self:start;">
                <h3 style="font-size:21px; font-weight:900; color:#111827; margin:0 0 16px;">
                    Request Leave
                </h3>

                <form method="POST" action="{{ route('my-leave.store') }}" enctype="multipart/form-data" style="display:grid; gap:14px;">
                    @csrf

                    <div>
                        <label style="font-weight:900; display:block; margin-bottom:6px;">Leave Type</label>

                        <select name="leave_type_id" id="leave_type_id" required onchange="handleLeaveTypeChange()"
                                style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                            <option value="">Select leave type</option>

                            @foreach($leaveTypes as $leaveType)
                                @php
                                    $balance = $balances->firstWhere('leave_type_id', $leaveType->id);
                                    $remaining = $balance ? $balance->calculated_remaining_days : 0;
                                @endphp

                                <option value="{{ $leaveType->id }}"
                                        data-requires-document="{{ $leaveType->requires_document ? 'yes' : 'no' }}"
                                        data-document-after="{{ $leaveType->document_required_after_days }}"
                                        data-remaining="{{ $remaining }}">
                                    {{ $leaveType->name }} — {{ number_format($remaining, 2) }} days left
                                </option>
                            @endforeach
                        </select>

                        <div id="leave_hint" style="display:none; margin-top:10px; padding:10px; border-radius:12px; background:#eff6ff; color:#1e40af; font-size:13px; font-weight:800;"></div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;" class="dashboard-grid">
                        <div>
                            <label style="font-weight:900; display:block; margin-bottom:6px;">Start Date</label>
                            <input type="date" name="start_date" required
                                   style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        </div>

                        <div>
                            <label style="font-weight:900; display:block; margin-bottom:6px;">End Date</label>
                            <input type="date" name="end_date" required
                                   style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;">
                        </div>
                    </div>

                    <div>
                        <label style="font-weight:900; display:block; margin-bottom:6px;">Reason / Notes</label>
                        <textarea name="reason" rows="4" placeholder="Add a short reason or note"
                                  style="width:100%; padding:12px; border:1px solid #d1d5db; border-radius:12px;"></textarea>
                    </div>

                    <div id="supporting_document_block"
                         style="display:none; background:#fff7ed; border:1px solid #fed7aa; padding:14px; border-radius:16px;">
                        <label style="font-weight:900; display:block; margin-bottom:6px; color:#9a3412;">
                            Supporting Document
                        </label>

                        <input type="file"
                               name="supporting_document"
                               accept=".pdf,.jpg,.jpeg,.png"
                               style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:10px; background:white;">

                        <p id="document_reason" style="font-size:12px; color:#9a3412; margin-top:8px;">
                            Please upload the required supporting document.
                        </p>
                    </div>

                    <button style="background:#2563eb; color:white; padding:13px 16px; border:0; border-radius:12px; font-weight:900;">
                        Submit Leave Request
                    </button>
                </form>
            </div>

            <div style="display:grid; gap:18px;">

                <div style="background:white; padding:24px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                    <details open>
                        <summary style="cursor:pointer; font-size:21px; font-weight:900; color:#111827;">
                            Leave Balances
                        </summary>

                        <div style="display:grid; gap:10px; margin-top:16px;">
                            @forelse($balances as $balance)
                                <details style="border:1px solid #e5e7eb; border-radius:16px; padding:14px; background:#f9fafb;">
                                    <summary style="cursor:pointer; font-weight:900; color:#111827; display:flex; justify-content:space-between; gap:12px;">
                                        <span>{{ $balance->leaveType?->name }}</span>
                                        <span style="color:#2563eb;">
                                            {{ number_format($balance->calculated_remaining_days, 2) }} left
                                        </span>
                                    </summary>

                                    <div style="margin-top:14px; display:grid; grid-template-columns:repeat(3, 1fr); gap:12px;" class="dashboard-grid">
                                        <div style="background:white; padding:12px; border-radius:12px;">
                                            <div style="font-size:12px; color:#6b7280; font-weight:800;">Allocated</div>
                                            <div style="font-size:22px; font-weight:900;">
                                                {{ number_format($balance->allocated_days + $balance->carried_forward_days, 2) }}
                                            </div>
                                        </div>

                                        <div style="background:white; padding:12px; border-radius:12px;">
                                            <div style="font-size:12px; color:#6b7280; font-weight:800;">Used</div>
                                            <div style="font-size:22px; font-weight:900;">
                                                {{ number_format($balance->used_days, 2) }}
                                            </div>
                                        </div>

                                        <div style="background:white; padding:12px; border-radius:12px;">
                                            <div style="font-size:12px; color:#6b7280; font-weight:800;">Remaining</div>
                                            <div style="font-size:22px; font-weight:900; color:#16a34a;">
                                                {{ number_format($balance->calculated_remaining_days, 2) }}
                                            </div>
                                        </div>
                                    </div>

                                    @if($balance->leaveType?->requires_document)
                                        <div style="margin-top:12px; color:#92400e; font-size:13px; font-weight:800;">
                                            Supporting document required.
                                        </div>
                                    @elseif($balance->leaveType?->document_required_after_days)
                                        <div style="margin-top:12px; color:#1e40af; font-size:13px; font-weight:800;">
                                            Supporting document required after {{ $balance->leaveType->document_required_after_days }} days.
                                        </div>
                                    @else
                                        <div style="margin-top:12px; color:#16a34a; font-size:13px; font-weight:800;">
                                            No document required by default.
                                        </div>
                                    @endif
                                </details>
                            @empty
                                <p style="color:#6b7280;">No leave balances found.</p>
                            @endforelse
                        </div>
                    </details>
                </div>

                <div style="background:white; padding:24px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                    <h3 style="font-size:21px; font-weight:900; color:#111827; margin:0 0 16px;">
                        Recent Leave Requests
                    </h3>

                    @if($requests->isEmpty())
                        <p style="color:#6b7280;">No leave requests yet.</p>
                    @else
                        <div style="display:grid; gap:10px;">
                            @foreach($requests as $request)
                                @php
                                    $statusColors = [
                                        'pending' => ['#fef3c7', '#92400e'],
                                        'approved' => ['#dcfce7', '#166534'],
                                        'rejected' => ['#fee2e2', '#991b1b'],
                                    ];

                                    [$bg, $fg] = $statusColors[$request->status] ?? ['#eff6ff', '#1e40af'];
                                @endphp

                                <div style="border:1px solid #e5e7eb; border-radius:16px; padding:14px; display:flex; justify-content:space-between; align-items:center; gap:14px; flex-wrap:wrap;">
                                    <div>
                                        <div style="font-weight:900; color:#111827;">
                                            {{ $request->leaveType?->name }}
                                        </div>

                                        <div style="font-size:13px; color:#6b7280; margin-top:4px;">
                                            {{ $request->start_date?->format('Y-m-d') }}
                                            -
                                            {{ $request->end_date?->format('Y-m-d') }}
                                            · {{ $request->days }} day(s)
                                        </div>
                                    </div>

                                    <div style="display:flex; align-items:center; gap:10px;">
                                        @if($request->supporting_document_path)
                                            <a href="{{ route('my-leave.document', $request) }}"
                                               style="background:#111827; color:white; padding:7px 10px; border-radius:10px; text-decoration:none; font-weight:800; font-size:12px;">
                                                Document
                                            </a>
                                        @endif

                                        <span style="background:{{ $bg }}; color:{{ $fg }}; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:900;">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>

    <script>
        function handleLeaveTypeChange() {
            const select = document.getElementById('leave_type_id');
            const selected = select.options[select.selectedIndex];

            const hint = document.getElementById('leave_hint');
            const documentBlock = document.getElementById('supporting_document_block');
            const documentReason = document.getElementById('document_reason');

            if (!selected || !selected.value) {
                hint.style.display = 'none';
                documentBlock.style.display = 'none';
                return;
            }

            const remaining = selected.dataset.remaining || '0';
            const requiresDocument = selected.dataset.requiresDocument === 'yes';
            const documentAfter = selected.dataset.documentAfter;

            hint.style.display = 'block';
            hint.textContent = `You currently have ${remaining} day(s) available for this leave type.`;

            if (requiresDocument) {
                documentBlock.style.display = 'block';
                documentReason.textContent = 'This leave type requires proof before submission.';
            } else if (documentAfter && parseFloat(documentAfter) > 0) {
                documentBlock.style.display = 'block';
                documentReason.textContent = `A document may be required if this request is ${documentAfter} or more days.`;
            } else {
                documentBlock.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', handleLeaveTypeChange);
    </script>
</x-app-layout>