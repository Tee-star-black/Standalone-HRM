<?php

namespace App\Http\Controllers;

use App\Http\Requests\OnboardEmployeeRequest;
use App\Services\EmployeeOnboardingService;
use Illuminate\Http\JsonResponse;

class OnboardingController extends Controller
{
    public function store(OnboardEmployeeRequest $request, EmployeeOnboardingService $service): JsonResponse
    {
        $result = $service->onboard($request->validated());

        return response()->json([
            'message' => 'Employee onboarded successfully.',
            'employee' => $result['employee'],
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
            ],
            'temporary_password' => $result['temporary_password'],
        ], 201);
    }
}