<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Notifications
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Your HR alerts, approvals, documents, payroll, and system updates.
                </p>
            </div>

            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button style="background:#111827; color:white; padding:10px 14px; border:0; border-radius:10px; font-weight:800;">
                    Mark All Read
                </button>
            </form>
        </div>
    </x-slot>

    <div style="display:grid; gap:16px;">
        @if(session('status'))
            <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px;">
                {{ session('status') }}
            </div>
        @endif

        @forelse($notifications as $notification)
            <div style="
                background:{{ $notification->read_at ? '#ffffff' : '#eff6ff' }};
                border:1px solid {{ $notification->read_at ? '#e5e7eb' : '#bfdbfe' }};
                border-radius:18px;
                padding:18px;
                box-shadow:0 8px 22px rgba(15,23,42,.05);
                display:flex;
                justify-content:space-between;
                gap:16px;
                align-items:center;
                flex-wrap:wrap;
            ">
                <div>
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                        <strong style="font-size:16px; color:#111827;">
                            {{ $notification->title }}
                        </strong>

                        @if(! $notification->read_at)
                            <span style="background:#2563eb; color:white; padding:3px 8px; border-radius:999px; font-size:11px; font-weight:800;">
                                New
                            </span>
                        @endif
                    </div>

                    <p style="color:#6b7280; margin:0;">
                        {{ $notification->message }}
                    </p>

                    <div style="font-size:12px; color:#9ca3af; margin-top:8px;">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>

                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                    @csrf
                    <button style="background:#111827; color:white; padding:9px 13px; border:0; border-radius:10px; font-weight:800;">
                        Open
                    </button>
                </form>
            </div>
        @empty
            <div style="background:white; padding:24px; border-radius:18px; border:1px solid #e5e7eb;">
                <strong>No notifications yet.</strong>
                <p style="color:#6b7280;">You’ll see HR alerts here once actions start creating notifications.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>