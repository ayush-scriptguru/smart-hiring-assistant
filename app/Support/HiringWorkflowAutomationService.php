<?php

namespace App\Support;

use App\Enums\ApplicationStage;
use App\Enums\InterviewStatus;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Validation\ValidationException;

class HiringWorkflowAutomationService
{
    /**
     * @return array{
     *     action: string,
     *     label: string,
     *     description: string,
     *     note: string
     * }|null
     */
    public function suggest(Application $application): ?array
    {
        $nextInterview = $application->nextScheduledInterview();
        $completedInterview = $application->interviews
            ->contains(fn (Interview $interview) => $interview->status === InterviewStatus::Completed);

        return match (true) {
            $application->stage === ApplicationStage::Applied && $application->match_score >= 82 => [
                'action' => 'shortlist',
                'label' => 'Auto-shortlist',
                'description' => 'High fit score detected. Move the candidate into recruiter screening immediately.',
                'note' => 'Auto-shortlisted from applied stage after strong score alignment.',
            ],
            $application->stage === ApplicationStage::Screening && ! $nextInterview && $application->match_score >= 68 => [
                'action' => 'move_to_interview',
                'label' => 'Move To Interview',
                'description' => 'Screening signals are strong enough to push this profile into interview planning.',
                'note' => 'Workflow automation moved candidate into interview planning queue.',
            ],
            $application->stage === ApplicationStage::Interview && $completedInterview => [
                'action' => 'offer_ready',
                'label' => 'Mark Offer Ready',
                'description' => 'Completed interview loop found. Surface this candidate for offer discussion.',
                'note' => 'Automation promoted candidate to offer review after completed interview loop.',
            ],
            $application->match_score < 50 && $application->stage !== ApplicationStage::Rejected => [
                'action' => 'reject',
                'label' => 'Send To Rejected',
                'description' => 'Low alignment detected. Move the profile out of the active funnel.',
                'note' => 'Automation moved candidate to rejected after sustained low fit score.',
            ],
            default => null,
        };
    }

    /**
     * @return array{status: string, stage: ApplicationStage}
     */
    public function execute(Application $application, string $action): array
    {
        $payload = match ($action) {
            'shortlist' => [
                'stage' => ApplicationStage::Screening,
                'status' => "Candidate {$application->candidate->name} was auto-shortlisted.",
                'note' => 'Auto-shortlisted from applied stage after strong score alignment.',
            ],
            'move_to_interview' => [
                'stage' => ApplicationStage::Interview,
                'status' => "Candidate {$application->candidate->name} was moved into the interview queue.",
                'note' => 'Workflow automation moved candidate into interview planning queue.',
            ],
            'offer_ready' => [
                'stage' => ApplicationStage::Offer,
                'status' => "Candidate {$application->candidate->name} is now marked as offer-ready.",
                'note' => 'Automation promoted candidate to offer review after completed interview loop.',
            ],
            'reject' => [
                'stage' => ApplicationStage::Rejected,
                'status' => "Candidate {$application->candidate->name} was moved to rejected.",
                'note' => 'Automation moved candidate to rejected after sustained low fit score.',
            ],
            default => throw ValidationException::withMessages([
                'action' => 'Unsupported automation action.',
            ]),
        };

        $application->update([
            'stage' => $payload['stage'],
            'recruiter_notes' => $this->appendNote($application->recruiter_notes, $payload['note']),
        ]);

        return [
            'status' => $payload['status'],
            'stage' => $payload['stage'],
        ];
    }

    protected function appendNote(?string $existingNotes, string $note): string
    {
        return collect([
            trim((string) $existingNotes),
            '['.now()->format('d M Y H:i')."] {$note}",
        ])
            ->filter()
            ->implode(PHP_EOL);
    }
}
