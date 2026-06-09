<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index', [
            'user' => Auth::user(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'theme' => ['required', 'in:light,dark'],
            'timezone' => ['required', 'string', 'max:255'],
            'date_format' => ['required', 'string', 'max:50'],
            'email_notifications' => ['nullable'],
        ]);

        $user = Auth::user();

        $user->update([
            'theme' => $data['theme'],
            'timezone' => $data['timezone'],
            'date_format' => $data['date_format'],
            'email_notifications' => $request->boolean('email_notifications'),
        ]);

        AuditLogger::log(
            'settings_updated',
            'Updated account preferences',
            $user,
            [
                'theme' => $user->theme,
                'timezone' => $user->timezone,
                'date_format' => $user->date_format,
                'email_notifications' => $user->email_notifications,
            ]
        );

        return redirect()
            ->route('settings.index')
            ->with('status', 'Settings updated successfully.');
    }

    public function toggleTheme()
    {
        $user = Auth::user();

        $user->update([
            'theme' => $user->theme === 'dark' ? 'light' : 'dark',
        ]);

        AuditLogger::log(
            'theme_toggled',
            'Toggled theme preference',
            $user,
            [
                'theme' => $user->theme,
            ]
        );

        return back();
    }
}