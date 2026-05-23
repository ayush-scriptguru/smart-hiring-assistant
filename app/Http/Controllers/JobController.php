<?php

namespace App\Http\Controllers;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'department' => ['nullable', 'string', 'max:255'],
        'location' => ['nullable', 'string', 'max:255'],
        'work_mode' => ['required', Rule::in(['remote', 'hybrid', 'onsite'])],
        'min_experience_years' => ['required', 'integer', 'min:0', 'max:25'],
        'skills' => ['required', 'string', 'max:1500'],
        'summary' => ['required', 'string', 'max:2500'],
        'openings' => ['required', 'integer', 'min:1', 'max:50'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Save Job First
    |--------------------------------------------------------------------------
    */

    $job = $request->user()->jobs()->create(
        $validated + [
            'status' => JobStatus::Open,
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Try AI Enhancement
    |--------------------------------------------------------------------------
    */

    try {

        $prompt = "
        Improve this job posting.

        Return ONLY valid JSON:

        {
          \"improved_summary\": \"\",
          \"suggested_skills\": []
        }

        TITLE:
        {$job->title}

        DEPARTMENT:
        {$job->department}

        SKILLS:
        {$job->skills}

        SUMMARY:
        {$job->summary}
        ";

        $response = Http::timeout(30)
            ->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . trim(env('GEMINI_API_KEY')),
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ]
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | AI Failed
        |--------------------------------------------------------------------------
        */

        if (! $response->successful()) {

            return back()->with([
                'warning' => 'Job created successfully, but AI enhancement is temporarily unavailable due to API limits.',
            ]);
        }

        $data = $response->json();

        $content = data_get(
            $data,
            'candidates.0.content.parts.0.text'
        );

        $result = json_decode($content, true);

        /*
        |--------------------------------------------------------------------------
        | Update Job With AI
        |--------------------------------------------------------------------------
        */

        if (is_array($result)) {

            $job->update([
                'summary' => $result['improved_summary'] ?? $job->summary,

                'skills' => ! empty($result['suggested_skills'])
                    ? implode(', ', $result['suggested_skills'])
                    : $job->skills,
            ]);
        }

    } catch (\Throwable $exception) {

        report($exception);

        return back()->with([
            'warning' => 'Job created successfully, but AI services are currently unavailable.',
        ]);
    }

    return back()->with([
        'status' => "Job '{$job->title}' has been added to the hiring board.",
    ]);
}


    public function updateStatus(Request $request, Job $job): RedirectResponse
    {
        $this->authorizeJob($request->user(), $job);

        $validated = $request->validate([
            'status' => ['required', Rule::enum(JobStatus::class)],
        ]);

        $job->update($validated);

        return back()->with('status', "Job '{$job->title}' is now {$job->status->label()}.");
    }

    protected function authorizeJob(User $user, Job $job): void
    {
        abort_unless($user->isAdmin() || $job->recruiter_id === $user->id, 403);
    }
}
