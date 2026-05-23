<?php

namespace Database\Seeders;

use App\Enums\ApplicationStage;
use App\Enums\InterviewStatus;
use App\Enums\JobStatus;
use App\Enums\MeetingMode;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use App\Support\CandidateScreeningService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Hackathon Admin',
            'email' => 'admin@smartassist.test',
            'role' => UserRole::Admin,
        ]);

        $recruiter = User::factory()->recruiter()->create([
            'name' => 'Riya Recruiter',
            'email' => 'recruiter@smartassist.test',
            'company' => 'Velocity Labs',
        ]);

        $candidate = User::factory()->candidate()->create([
            'name' => 'Arjun Candidate',
            'email' => 'candidate@smartassist.test',
            'skills' => 'Laravel, PHP, Tailwind CSS, MySQL, Communication',
            'years_of_experience' => 5,
            'resume_summary' => 'Built hiring ops dashboards and automated shortlist workflows for internal teams.',
        ]);

        $job = Job::query()->create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Full-stack Laravel Developer',
            'department' => 'Product Engineering',
            'location' => 'Remote',
            'work_mode' => 'hybrid',
            'min_experience_years' => 3,
            'skills' => 'Laravel, PHP, MySQL, Tailwind CSS, Communication',
            'summary' => 'Own a fast-moving MVP that helps recruiters screen, schedule, and move candidates through the funnel.',
            'openings' => 2,
            'status' => JobStatus::Open,
        ]);

        $screening = app(CandidateScreeningService::class)->evaluate($candidate, $job);

        $application = Application::query()->create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'stage' => ApplicationStage::Interview,
            'match_score' => $screening['score'],
            'screening_snapshot' => $screening,
            'cover_note' => 'Happy to help ship a polished hackathon demo quickly.',
            'recruiter_notes' => 'Strong overlap in Laravel and dashboard workflows.',
        ]);

        Interview::query()->create([
            'application_id' => $application->id,
            'scheduled_at' => now()->addDays(2)->setTime(16, 0),
            'interviewer_name' => 'Riya Recruiter',
            'meeting_mode' => MeetingMode::Video,
            'meeting_details' => 'Google Meet link will be shared in the dashboard.',
            'notes' => 'Focus on workflow automation and product thinking.',
            'status' => InterviewStatus::Scheduled,
        ]);
    }
}
