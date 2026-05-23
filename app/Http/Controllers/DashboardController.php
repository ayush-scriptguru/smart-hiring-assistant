<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStage;
use App\Enums\InterviewStatus;
use App\Enums\JobStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use App\Support\HiringWorkflowAutomationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected HiringWorkflowAutomationService $automationService,
    ) {
    }

    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->canManageHiring()) {
            return $this->recruiterDashboard($user, $request);
        }

        return $this->candidateDashboard($user, $request);
    }

    protected function recruiterDashboard(User $user, Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $stageFilter = trim((string) $request->input('stage', ''));
        $jobFilter = trim((string) $request->input('job', ''));
        $sort = trim((string) $request->input('sort', 'score')) ?: 'score';
        $activeTab = trim((string) $request->input('tab', 'pipeline')) ?: 'pipeline';

        $profileCompletionChecks = [
            filled($user->headline),
            filled($user->company),
            filled($user->skills),
            filled($user->years_of_experience),
            filled($user->resume_summary), // Using resume_summary as a general bio field for recruiters
        ];

        $profileCompletion = (int) round((collect($profileCompletionChecks)->filter()->count() / count($profileCompletionChecks)) * 100);

        $jobsQuery = Job::query()
            ->withCount('applications')
            ->latest();

        $applicationsQuery = Application::query()
            ->with([
                'job.recruiter',
                'candidate',
                'interviews' => fn ($query) => $query->orderBy('scheduled_at'),
            ])
            ->latest();

        $interviewsQuery = Interview::query()
            ->with([
                'application.job',
                'application.candidate',
            ])
            ->latest('scheduled_at');

        if (! $user->isAdmin()) {
            $jobsQuery->where('recruiter_id', $user->id);
            $applicationsQuery->whereHas('job', fn ($query) => $query->where('recruiter_id', $user->id));
            $interviewsQuery->whereHas('application.job', fn ($query) => $query->where('recruiter_id', $user->id));
        }

        if ($search !== '') {
            $applicationsQuery->where(function ($query) use ($search): void {
                $query->whereHas('candidate', function ($candidateQuery) use ($search): void {
                    $candidateQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('headline', 'like', "%{$search}%")
                        ->orWhere('skills', 'like', "%{$search}%");
                })->orWhereHas('job', function ($jobQuery) use ($search): void {
                    $jobQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%")
                        ->orWhere('skills', 'like', "%{$search}%");
                });
            });
        }

        if ($stageFilter !== '' && ApplicationStage::tryFrom($stageFilter)) {
            $applicationsQuery->where('stage', $stageFilter);
        }

        if ($jobFilter !== '' && ctype_digit($jobFilter)) {
            $applicationsQuery->where('job_id', (int) $jobFilter);
        }

        $jobs = $jobsQuery->get();
        $applications = $applicationsQuery->get();

        $applications = match ($sort) {
            'recent' => $applications->sortByDesc('created_at')->values(),
            'name' => $applications->sortBy(fn (Application $application) => mb_strtolower($application->candidate->name))->values(),
            'stage' => $applications->sortBy(fn (Application $application) => $application->stage->value)->values(),
            default => $applications->sortByDesc('match_score')->values(),
        };

        $interviewTimeline = $interviewsQuery
            ->get();

        $upcomingInterviews = $interviewTimeline ->filter(function (Interview $interview) { if (! $interview->scheduled_at) { return false; } return $interview->scheduled_at->isFuture() || $interview->status === InterviewStatus::Scheduled; }) ->sortBy('scheduled_at') ->take(6) ->values(); $interviewTimeline = $interviewTimeline ->take(8) ->values();

        $candidatePool = User::query()
            ->where('role', UserRole::Candidate)
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'open_jobs' => $jobs->where('status', JobStatus::Open)->count(),
            'applications' => $applications->count(),
            'high_fit' => $applications->where('match_score', '>=', 80)->count(),
            'interviews' => $upcomingInterviews->count(),
        ];

        $pipeline = collect(ApplicationStage::cases())
            ->mapWithKeys(fn (ApplicationStage $stage) => [
                $stage->value => $applications->where('stage', $stage)->count(),
            ])
            ->all();

        $analysisMetrics = [
            'average_score' => $applications->isEmpty() ? 0 : (int) round($applications->avg('match_score')),
            'shortlist_ready' => $applications->filter(fn (Application $application) => $application->match_score >= 80)->count(),
            'needs_interview' => $applications->filter(fn (Application $application) => $application->stage === ApplicationStage::Screening && ! $application->nextScheduledInterview())->count(),
            'high_gap_profiles' => $applications->filter(fn (Application $application) => $application->missingSkillsCount() >= 3)->count(),
        ];

        $topCandidates = $applications
            ->sortByDesc('match_score')
            ->take(5)
            ->values();

        $automationQueue = $applications
            ->map(fn (Application $application) => [
                'application' => $application,
                'suggestion' => $this->automationService->suggest($application),
            ])
            ->filter(fn (array $item) => filled($item['suggestion']))
            ->take(6)
            ->values();

        return view('dashboard.index', [
            'mode' => $user->isAdmin() ? 'admin' : 'recruiter',
            'jobs' => $jobs,
            'applications' => $applications,
            'candidatePool' => $candidatePool,
            'upcomingInterviews' => $upcomingInterviews,
            'interviewTimeline' => $interviewTimeline,
            'stats' => $stats,
            'pipeline' => $pipeline,
            'analysisMetrics' => $analysisMetrics,
            'topCandidates' => $topCandidates,
            'automationQueue' => $automationQueue,
            'filters' => [
                'search' => $search,
                'stage' => $stageFilter,
                'job' => $jobFilter,
                'sort' => $sort,
                'tab' => $activeTab,
            ],
            'profileCompletion' => $profileCompletion, // Pass profile completion for recruiter
        ]);
    }

    protected function candidateDashboard(User $user, Request $request): View
    {
        $activeTab = trim((string) $request->input('tab', 'jobs')) ?: 'jobs';
        $search = trim((string) $request->input('search', ''));

        $openJobsQuery = Job::query()
            ->where('status', JobStatus::Open)
            ->with('recruiter')
            ->latest();

        if ($search !== '') {
            $openJobsQuery->where(function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('skills', 'like', "%{$search}%");
            });
        }

        $openJobs = $openJobsQuery->get();

        $applications = $user->applications()
            ->with([
                'job.recruiter',
                'interviews' => fn ($query) => $query->orderBy('scheduled_at'),
            ])
            ->latest()
            ->get();

        $recruiters = [];
        if ($activeTab === 'recruiters') {
            $recruiters = User::query()
                ->where('role', UserRole::Recruiter)
                ->whereNotNull('company')
                ->get();
        }

        $profileCompletionChecks = [
            filled($user->headline),
            filled($user->skills),
            filled($user->resume_summary),
            filled($user->years_of_experience),
        ];

        $profileCompletion = (int) round(
            (collect($profileCompletionChecks)->filter()->count() / count($profileCompletionChecks)) * 100
        );

        return view('dashboard.index', [
            'mode' => 'candidate',
            'openJobs' => $openJobs,
            'applications' => $applications,
            'recruiters' => $recruiters,
            'profileCompletion' => $profileCompletion,
            'filters' => [
                'tab' => $activeTab,
                'search' => $search,
            ],
        ]);
    }
}
