@extends('layouts.app')

@section('content')
    @if ($mode === 'candidate')
        @include('dashboard.partials.candidate', [
            'applications' => $applications,
            'openJobs' => $openJobs,
            'profileCompletion' => $profileCompletion,
        ])
    @else
        @include('dashboard.partials.recruiter', [
            'mode' => $mode,
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
            'filters' => $filters,
        ])
    @endif
@endsection
