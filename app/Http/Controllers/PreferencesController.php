<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PreferencesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        return view('preferences.index', [
            'user' => $user,
            'employee' => $employee,
        ]);
    }
}