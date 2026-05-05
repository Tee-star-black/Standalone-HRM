<?php

namespace App\Http\Controllers;

use App\Models\EmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfileController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        return view('my-profile.index', [
            'employee' => $employee->load([
                'user',
                'department',
                'establishment',
                'manager',
                'positions.job',
                'emergencyContacts',
            ]),
        ]);
    }

    public function storeEmergencyContact(Request $request)
    {
        $employee = Auth::user()->employee;

        if (! $employee) {
            abort(403, 'No employee profile linked to this user.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'relationship' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'is_primary' => ['nullable', 'boolean'],
        ]);

        $data['employee_id'] = $employee->id;
        $data['is_primary'] = $request->boolean('is_primary');

        if ($data['is_primary']) {
            EmergencyContact::where('employee_id', $employee->id)->update([
                'is_primary' => false,
            ]);
        }

        EmergencyContact::create($data);

        return redirect()
            ->route('my-profile.index')
            ->with('status', 'Emergency contact added.');
    }
}