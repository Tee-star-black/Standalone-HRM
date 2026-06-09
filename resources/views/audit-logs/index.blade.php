<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Audit Logs
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Track important HR system actions and accountability records.
                </p>
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="{{ route('audit-logs.export') }}"
                   style="background:#16a34a; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Export Excel
                </a>

                <a href="{{ route('dashboard') }}"
                   style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div style="display:grid; gap:20px;">

        <div style="background:white; padding:20px; border-radius:18px; border:1px solid #e5e7eb;">
            <form method="GET" action="{{ route('audit-logs.index') }}"
                  style="display:grid; grid-template-columns:repeat(5, 1fr); gap:12px;"
                  class="dashboard-grid">

                <select name="action" style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>
                            {{ $action }}
                        </option>
                    @endforeach
                </select>

                <select name="user_id" style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                    <option value="">All users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <select name="model_type" style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">
                    <option value="">All models</option>
                    @foreach($modelTypes as $modelType)
                        <option value="{{ $modelType }}" @selected(request('model_type') === $modelType)>
                            {{ class_basename($modelType) }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       style="padding:10px; border:1px solid #d1d5db; border-radius:10px;">

                <button style="background:#2563eb; color:white; padding:10px 14px; border:0; border-radius:10px; font-weight:800;">
                    Filter
                </button>

                <a href="{{ route('audit-logs.index') }}"
                   style="background:#f3f4f6; color:#111827; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800; text-align:center;">
                    Reset
                </a>
            </form>
        </div>

        <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">

            @if($logs->isEmpty())
                <p style="color:#6b7280;">No audit logs found.</p>
            @else
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Date</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">User</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Action</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Description</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">Model</th>
                            <th style="padding:10px; border-bottom:1px solid #e5e7eb; text-align:left;">IP</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    {{ $log->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    {{ $log->user?->name ?? 'System' }}
                                </td>

                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    <span style="background:#dbeafe; color:#1e40af; padding:5px 9px; border-radius:999px; font-size:12px; font-weight:800;">
                                        {{ $log->action }}
                                    </span>
                                </td>

                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    {{ $log->description ?? '-' }}
                                </td>

                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    {{ $log->model_type ? class_basename($log->model_type) . ' #' . $log->model_id : '-' }}
                                </td>

                                <td style="padding:10px; border-bottom:1px solid #f3f4f6;">
                                    {{ $log->ip_address ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:18px;">
                    {{ $logs->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>