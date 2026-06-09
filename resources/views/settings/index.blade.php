<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
            <div>
                <h2 style="font-size:28px; font-weight:900; color:#111827; margin:0;">
                    Settings
                </h2>
                <p style="color:#6b7280; margin-top:6px;">
                    Manage your theme, timezone, date format, and notification preferences.
                </p>
            </div>

            <a href="{{ route('preferences.index') }}"
               style="background:#111827; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; font-weight:800;">
                Back to Preferences
            </a>
        </div>
    </x-slot>

    <div style="display:grid; grid-template-columns:360px 1fr; gap:24px;" class="dashboard-grid">

        <div style="background:#111827; color:white; padding:24px; border-radius:22px; box-shadow:0 12px 30px rgba(17,24,39,.18); align-self:start;">
            <h3 style="font-size:22px; font-weight:900; margin:0;">
                My Preferences
            </h3>

            <p style="color:#d1d5db; line-height:1.6; margin-top:10px;">
                These settings are saved to your account and will apply when you log in on another device.
            </p>

            <div style="margin-top:18px; display:grid; gap:10px;">
                <div style="background:rgba(255,255,255,.08); padding:12px; border-radius:14px;">
                    <strong>Current Theme</strong>
                    <div style="color:#cbd5e1; margin-top:4px;">
                        {{ ucfirst($user->theme ?? 'light') }}
                    </div>
                </div>

                <div style="background:rgba(255,255,255,.08); padding:12px; border-radius:14px;">
                    <strong>Timezone</strong>
                    <div style="color:#cbd5e1; margin-top:4px;">
                        {{ $user->timezone ?? 'Africa/Johannesburg' }}
                    </div>
                </div>
            </div>
        </div>

        <div style="background:white; padding:24px; border-radius:22px; border:1px solid #e5e7eb; box-shadow:0 8px 24px rgba(15,23,42,.05);">

            @if(session('status'))
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:10px; margin-bottom:16px;">
                    {{ session('status') }}
                </div>
            @endif

            <h3 style="font-size:20px; font-weight:900; margin:0 0 18px; color:#111827;">
                Account Preferences
            </h3>

            <form method="POST" action="{{ route('settings.update') }}" style="display:grid; gap:18px;">
                @csrf

                <div>
                    <label style="font-weight:800; display:block; margin-bottom:6px;">
                        Theme
                    </label>

                    <select name="theme"
                            style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        <option value="light" @selected(($user->theme ?? 'light') === 'light')>
                            Light Mode
                        </option>
                        <option value="dark" @selected(($user->theme ?? 'light') === 'dark')>
                            Dark Mode
                        </option>
                    </select>

                    <p style="color:#6b7280; font-size:13px; margin-top:6px;">
                        Saved theme will apply across devices after login.
                    </p>
                </div>

                <div>
                    <label style="font-weight:800; display:block; margin-bottom:6px;">
                        Timezone
                    </label>

                    <select name="timezone"
                            style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        <option value="Africa/Johannesburg" @selected(($user->timezone ?? '') === 'Africa/Johannesburg')>
                            Africa/Johannesburg
                        </option>
                        <option value="UTC" @selected(($user->timezone ?? '') === 'UTC')>
                            UTC
                        </option>
                        <option value="Europe/London" @selected(($user->timezone ?? '') === 'Europe/London')>
                            Europe/London
                        </option>
                        <option value="America/New_York" @selected(($user->timezone ?? '') === 'America/New_York')>
                            America/New York
                        </option>
                    </select>
                </div>

                <div>
                    <label style="font-weight:800; display:block; margin-bottom:6px;">
                        Date Format
                    </label>

                    <select name="date_format"
                            style="width:100%; padding:11px; border:1px solid #d1d5db; border-radius:10px;">
                        <option value="d M Y" @selected(($user->date_format ?? 'd M Y') === 'd M Y')>
                            11 May 2026
                        </option>
                        <option value="Y-m-d" @selected(($user->date_format ?? '') === 'Y-m-d')>
                            2026-05-11
                        </option>
                        <option value="d/m/Y" @selected(($user->date_format ?? '') === 'd/m/Y')>
                            11/05/2026
                        </option>
                        <option value="m/d/Y" @selected(($user->date_format ?? '') === 'm/d/Y')>
                            05/11/2026
                        </option>
                    </select>
                </div>

                <div style="background:#f9fafb; border:1px solid #e5e7eb; padding:16px; border-radius:16px;">
                    <label style="display:flex; align-items:center; gap:10px; font-weight:800;">
                        <input type="checkbox"
                               name="email_notifications"
                               value="1"
                               @checked($user->email_notifications ?? true)>
                        Enable email notifications
                    </label>

                    <p style="color:#6b7280; font-size:13px; margin:8px 0 0;">
                        Later this will control leave, payslip, announcement, and document emails.
                    </p>
                </div>

                <button style="background:#2563eb; color:white; padding:12px 16px; border:0; border-radius:12px; font-weight:900; cursor:pointer;">
                    Save Settings
                </button>
            </form>
        </div>

    </div>
</x-app-layout>