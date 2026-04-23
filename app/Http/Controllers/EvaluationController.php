<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return Evaluation::with(['employee', 'reviewer', 'items.skill'])->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'reviewer_id' => ['nullable', 'exists:employees,id'],
            'period' => ['required', 'string', 'max:255'],
            'evaluation_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'strengths' => ['nullable', 'string'],
            'areas_for_improvement' => ['nullable', 'string'],
            'manager_comments' => ['nullable', 'string'],
        ]);

        $evaluation = Evaluation::create($data);

        return response()->json(
            $evaluation->load(['employee', 'reviewer']),
            201
        );
    }

    public function show(Evaluation $evaluation)
    {
        return $evaluation->load(['employee', 'reviewer', 'items.skill']);
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $data = $request->validate([
            'employee_id' => ['sometimes', 'exists:employees,id'],
            'reviewer_id' => ['nullable', 'exists:employees,id'],
            'period' => ['sometimes', 'string', 'max:255'],
            'evaluation_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:100'],
            'summary' => ['nullable', 'string'],
            'strengths' => ['nullable', 'string'],
            'areas_for_improvement' => ['nullable', 'string'],
            'manager_comments' => ['nullable', 'string'],
        ]);

        $evaluation->update($data);

        return $evaluation->load(['employee', 'reviewer', 'items.skill']);
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return response()->json(['message' => 'Evaluation deleted']);
    }
}