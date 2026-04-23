<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return Job::with('skills')->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255', 'unique:hr_jobs,code'],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'grade' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $job = Job::create($data);

        return response()->json($job, 201);
    }

    public function show(Job $job)
    {
        return $job->load(['skills', 'positions']);
    }

    public function update(Request $request, Job $job)
    {
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255', 'unique:hr_jobs,code,' . $job->id],
            'summary' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'grade' => ['nullable', 'string', 'max:100'],
            'employment_type' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $job->update($data);

        return $job;
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }

    public function attachSkill(Request $request, Job $job)
    {
        $data = $request->validate([
            'skill_id' => ['required', 'exists:skills,id'],
            'required_level' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $job->skills()->syncWithoutDetaching([
            $data['skill_id'] => [
                'required_level' => $data['required_level'],
            ],
        ]);

        return response()->json($job->load('skills'), 200);
    }
}