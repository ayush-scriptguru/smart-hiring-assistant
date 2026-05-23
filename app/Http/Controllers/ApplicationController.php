<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStage;
use App\Enums\JobStatus;
use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Support\CandidateScreeningService;
use App\Support\HiringWorkflowAutomationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\PdfToText\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use OpenAI\Laravel\Facades\OpenAI;
use Smalot\PdfParser\Parser;

class ApplicationController extends Controller
{
    public function __construct(
        protected CandidateScreeningService $screeningService,
        protected HiringWorkflowAutomationService $automationService,
    ) {
    }

public function store(Request $request, Job $job)
{
    $request->validate([
        'resume_pdf' => ['required', 'mimes:pdf'],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Extract Resume Text
    |--------------------------------------------------------------------------
    */

    $parser = new Parser();

    $pdf = $parser->parseFile(
        $request->file('resume_pdf')->path()
    );

    $resumeText = $pdf->getText();

    /*
    |--------------------------------------------------------------------------
    | Clean Resume Text
    |--------------------------------------------------------------------------
    */

    $resumeText = preg_replace('/[^\PC\s]/u', '', $resumeText);

    $resumeText = preg_replace('/\s+/', ' ', $resumeText);

    $resumeText = trim($resumeText);

    /*
    |--------------------------------------------------------------------------
    | Reduce Token Usage
    |--------------------------------------------------------------------------
    */

    $resumeText = substr($resumeText, 0, 6000);

    /*
    |--------------------------------------------------------------------------
    | Build Prompt
    |--------------------------------------------------------------------------
    */

    $prompt = "
    Analyze this resume against the job.

    Return ONLY valid JSON.

    {
      \"ats_score\": 0,
      \"candidate_level\": \"Beginner/Mid/Senior\",
      \"candidate_years\": 0,
      \"required_years\": 0,
      \"shortlisted\": true,
      \"recommendation\": \"Strong Hire / Hire / Maybe / Reject\",
      \"matched_skills\": [],
      \"missing_skills\": [],
      \"strengths\": [],
      \"weaknesses\": [],
      \"summary\": \"\",
      \"risk_flags\": [],
      \"interview_questions\": [],
      \"improvement_suggestions\": [],
      \"final_feedback\": \"\"
    }

    JOB TITLE:
    {$job->title}

    JOB SUMMARY:
    {$job->summary}

    REQUIRED SKILLS:
    {$job->skills}

    REQUIRED EXPERIENCE:
    {$job->min_experience_years}

    RESUME:
    {$resumeText}
    ";

    /*
    |--------------------------------------------------------------------------
    | Default Fallback Result
    |--------------------------------------------------------------------------
    */

    $result = [
        'ats_score' => 50,

        'candidate_level' => 'Unknown',

        'candidate_years' => 0,

        'required_years' => $job->min_experience_years,

        'shortlisted' => false,

        'recommendation' => 'Pending AI Analysis',

        'matched_skills' => [],

        'missing_skills' => [],

        'strengths' => [],

        'weaknesses' => [],

        'summary' => 'AI analysis pending.',

        'risk_flags' => [],

        'interview_questions' => [],

        'improvement_suggestions' => [],

        'final_feedback' => 'AI analysis unavailable currently.',

        'ai_failed' => true,
    ];

    /*
    |--------------------------------------------------------------------------
    | Try AI Analysis
    |--------------------------------------------------------------------------
    */

    try {

        $response = Http::timeout(120)
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
        | Successful Response
        |--------------------------------------------------------------------------
        */

        if ($response->successful()) {

            $data = $response->json();

            $content = data_get(
                $data,
                'candidates.0.content.parts.0.text'
            );

            if ($content) {

                $decoded = json_decode($content, true);

                if (is_array($decoded)) {

                    $result = array_merge($result, $decoded);

                    $result['ai_failed'] = false;
                }
            }
        }

    } catch (\Throwable $exception) {

        report($exception);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Resume
    |--------------------------------------------------------------------------
    */

    $resumePath = $request
        ->file('resume_pdf')
        ->store('resumes', 'public');

    /*
    |--------------------------------------------------------------------------
    | Create Application
    |--------------------------------------------------------------------------
    */

    $application = Application::create([
        'job_id' => $job->id,

        'candidate_id' => auth()->id(),

        'stage' => ApplicationStage::Applied,

        'match_score' => (int) ($result['ats_score'] ?? 50),

        'resume_path' => $resumePath,

        'screening_snapshot' => [

            'ats_score' => (int) ($result['ats_score'] ?? 50),

            'candidate_level' => $result['candidate_level'] ?? null,

            'candidate_years' => (int) ($result['candidate_years'] ?? 0),

            'required_years' => (int) ($result['required_years'] ?? 0),

            'shortlisted' => (bool) ($result['shortlisted'] ?? false),

            'recommendation' => $result['recommendation'] ?? null,

            'matched_skills' => $result['matched_skills'] ?? [],

            'missing_skills' => $result['missing_skills'] ?? [],

            'strengths' => $result['strengths'] ?? [],

            'weaknesses' => $result['weaknesses'] ?? [],

            'summary' => $result['summary'] ?? null,

            'risk_flags' => $result['risk_flags'] ?? [],

            'interview_questions' => $result['interview_questions'] ?? [],

            'improvement_suggestions' => $result['improvement_suggestions'] ?? [],

            'final_feedback' => $result['final_feedback'] ?? null,

            'ai_failed' => $result['ai_failed'] ?? false,
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Success Response
    |--------------------------------------------------------------------------
    */

    return back()->with(
        'status',
        "Application submitted successfully."
    );
}




    public function update(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request->user(), $application);

        $validated = $request->validate([
            'stage' => ['required', Rule::enum(ApplicationStage::class)],
            'recruiter_notes' => ['nullable', 'string', 'max:2500'],
        ]);

        $application->update($validated);

        return back()->with('status', "Application for {$application->candidate->name} updated.");
    }

    public function refresh(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request->user(), $application);

        $this->screeningService->refreshApplication(
            $application->loadMissing(['candidate', 'job'])
        );

        return back()->with('status', "Screening insights refreshed for {$application->candidate->name}.");
    }

    public function automate(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request->user(), $application);

        $validated = $request->validate([
            'action' => ['required', 'string'],
        ]);

        $result = $this->automationService->execute(
            $application->loadMissing(['candidate', 'interviews']),
            $validated['action']
        );

        return back()->with('status', $result['status']);
    }

    protected function authorizeApplication(User $user, Application $application): void
    {
        abort_unless(
            $user->isAdmin() || $application->job->recruiter_id === $user->id,
            403
        );
    }
}
