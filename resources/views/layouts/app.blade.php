<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Standalone HRM') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body style="margin:0; background:#f3f4f6; font-family:Arial, sans-serif;">
    @include('layouts.navigation')

    <div style="margin-left:260px; min-height:100vh;">
        <header style="height:70px; background:white; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between; padding:0 28px;">
            <strong>{{ Auth::user()->name }}</strong>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button style="background:#dc2626; color:white; border:0; padding:12px 20px; border-radius:8px; font-weight:bold;">
                    Logout
                </button>
            </form>
        </header>

        @isset($header)
            <div style="background:white; padding:24px 32px; border-bottom:1px solid #e5e7eb;">
                {{ $header }}
            </div>
        @endisset

        <main style="padding:32px;">
            {{ $slot }}
        </main>
    </div>
</body>
</html>