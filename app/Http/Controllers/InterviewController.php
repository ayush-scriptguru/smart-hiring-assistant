<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStage;
use App\Enums\InterviewStatus;
use App\Enums\MeetingMode;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InterviewController extends Controller
{
    public function store(Request $request, Application $application): RedirectResponse
    {
        $this->authorizeApplication($request->user(), $application);

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'interviewer_name' => ['required', 'string', 'max:255'],
            'meeting_mode' => ['required', Rule::enum(MeetingMode::class)],
            'meeting_details' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2500'],
        ]);

        $application->interviews()->create($validated + [
            'status' => InterviewStatus::Scheduled,
        ]);

        if ($application->stage !== ApplicationStage::Interview) {
            $application->update([
                'stage' => ApplicationStage::Interview,
            ]);
        }

        return back()->with('status', "Interview scheduled for {$application->candidate->name}.");
    }

    public function update(Request $request, Interview $interview): RedirectResponse
    {
        $this->authorizeApplication($request->user(), $interview->application);

        $validated = $request->validate([
            'status' => ['required', Rule::enum(InterviewStatus::class)],
            'notes' => ['nullable', 'string', 'max:2500'],
        ]);

        $interview->update($validated);

        return back()->with('status', "Interview for {$interview->application->candidate->name} updated to {$interview->status->label()}.");
    }

    protected function authorizeApplication(User $user, Application $application): void
    {
        abort_unless(
            $user->isAdmin() || $application->job->recruiter_id === $user->id,
            403
        );
    }
}
