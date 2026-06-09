<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Company Announcements
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Company-wide notices, updates, and internal communication.
                </p>
            </div>

            <a href="{{ route('dashboard') }}"
               style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns:380px 1fr; gap:24px;" class="dashboard-grid">

        <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05); align-self:start;">
            <h3 style="font-size:20px; font-weight:900; margin:0 0 16px;">
                Create Announcement
            </h3>

            @if(session('status'))
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px; margin-bottom:14px;">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('announcements.store') }}" style="display:grid; gap:12px;">
                @csrf

                <input name="title" required placeholder="Announcement title"
                       style="padding:11px; border:1px solid #d1d5db; border-radius:10px;">

                <textarea name="body" required rows="7" placeholder="Write announcement..."
                          style="padding:11px; border:1px solid #d1d5db; border-radius:10px;"></textarea>

                <label style="display:flex; align-items:center; gap:8px; font-weight:700;">
                    <input type="checkbox" name="is_pinned" value="1">
                    Pin announcement
                </label>

                <button style="background:#2563eb; color:white; padding:11px 14px; border:0; border-radius:10px; font-weight:800;">
                    Publish Announcement
                </button>
            </form>
        </div>

        <div style="display:grid; gap:16px;">
            @forelse($announcements as $announcement)
                <div style="background:white; padding:22px; border-radius:20px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">
                    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                        <div>
                            @if($announcement->is_pinned)
                                <span style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:999px; font-size:12px; font-weight:900;">
                                    Pinned
                                </span>
                            @endif

                            <h3 style="font-size:22px; font-weight:900; margin:10px 0 6px;">
                                {{ $announcement->title }}
                            </h3>

                            <div style="font-size:12px; color:#6b7280;">
                                Published {{ $announcement->published_at?->diffForHumans() ?? $announcement->created_at->diffForHumans() }}
                            </div>
                        </div>

                        @if(Auth::user()->hasAnyRole(['Super Admin', 'HR Admin']))
                            <form method="POST" action="{{ route('announcements.destroy', $announcement) }}">
                                @csrf
                                @method('DELETE')
                                <button style="background:#fee2e2; color:#991b1b; border:0; padding:8px 12px; border-radius:10px; font-weight:800;">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </div>

                    <p style="color:#374151; line-height:1.7; margin-top:16px; white-space:pre-line;">
                        {{ $announcement->body }}
                    </p>
                </div>
            @empty
                <div style="background:white; padding:24px; border-radius:20px; border:1px solid #e5e7eb;">
                    <strong>No announcements yet.</strong>
                    <p style="color:#6b7280;">Published announcements will appear here.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>