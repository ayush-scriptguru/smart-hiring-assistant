<?php

namespace Tests\Feature;

use App\Enums\ApplicationStage;
use App\Enums\InterviewStatus;
use App\Enums\MeetingMode;
use App\Enums\JobStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HiringAssistantFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_as_recruiter(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Riya Recruiter',
            'email' => 'riya@example.com',
            'role' => UserRole::Recruiter->value,
            'headline' => 'Talent Partner',
            'company' => 'Velocity Labs',
            'skills' => 'Sourcing, Interviewing',
            'years_of_experience' => 4,
            'resume_summary' => 'Runs lean hiring loops.',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'riya@example.com',
            'role' => UserRole::Recruiter->value,
            'company' => 'Velocity Labs',
        ]);
    }

    public function test_candidate_can_apply_to_an_open_role_and_receives_a_screening_score(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $candidate = User::factory()->candidate()->create([
            'skills' => 'Laravel, PHP, Tailwind CSS, MySQL',
            'years_of_experience' => 4,
        ]);

        $job = Job::query()->create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Laravel Engineer',
            'department' => 'Engineering',
            'location' => 'Remote',
            'work_mode' => 'remote',
            'min_experience_years' => 3,
            'skills' => 'Laravel, PHP, MySQL, APIs',
            'summary' => 'Build a lean hiring dashboard.',
            'openings' => 1,
            'status' => JobStatus::Open,
        ]);

        $response = $this
            ->actingAs($candidate)
            ->post(route('applications.store', $job), [
                'cover_note' => 'I have shipped Laravel dashboards and internal tools.',
            ]);

        $response->assertRedirect();

        $application = Application::query()->firstOrFail();

        $this->assertSame($candidate->id, $application->candidate_id);
        $this->assertGreaterThan(0, $application->match_score);
        $this->assertContains('Laravel', $application->matchedSkills());
    }

    public function test_recruiter_can_update_application_stage_while_candidate_cannot(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $candidate = User::factory()->candidate()->create();

        $job = Job::query()->create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Operations Analyst',
            'department' => 'People Ops',
            'location' => 'Hybrid',
            'work_mode' => 'hybrid',
            'min_experience_years' => 2,
            'skills' => 'Communication, Operations, Excel',
            'summary' => 'Support hiring operations and scheduling.',
            'openings' => 1,
            'status' => JobStatus::Open,
        ]);

        $application = Application::query()->create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'stage' => ApplicationStage::Applied,
            'match_score' => 72,
            'screening_snapshot' => [
                'matched_skills' => ['Communication'],
                'missing_skills' => ['Operations'],
                'recommendation' => 'Review with recruiter',
                'summary' => 'Strong communicator with room to grow in operations.',
            ],
        ]);

        $this->actingAs($candidate)
            ->patch(route('applications.update', $application), [
                'stage' => ApplicationStage::Hired->value,
                'recruiter_notes' => 'Candidate should not be able to do this.',
            ])
            ->assertForbidden();

        $this->actingAs($recruiter)
            ->patch(route('applications.update', $application), [
                'stage' => ApplicationStage::Screening->value,
                'recruiter_notes' => 'Move forward to recruiter screen.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'stage' => ApplicationStage::Screening->value,
            'recruiter_notes' => 'Move forward to recruiter screen.',
        ]);
    }

    public function test_recruiter_can_run_workflow_automation_for_high_fit_candidates(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $candidate = User::factory()->candidate()->create();

        $job = Job::query()->create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Senior Laravel Engineer',
            'department' => 'Engineering',
            'location' => 'Remote',
            'work_mode' => 'remote',
            'min_experience_years' => 4,
            'skills' => 'Laravel, PHP, MySQL, APIs',
            'summary' => 'Lead hiring platform delivery.',
            'openings' => 1,
            'status' => JobStatus::Open,
        ]);

        $application = Application::query()->create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'stage' => ApplicationStage::Applied,
            'match_score' => 90,
            'screening_snapshot' => [
                'matched_skills' => ['Laravel', 'PHP', 'MySQL'],
                'missing_skills' => [],
                'recommendation' => 'Shortlist now',
                'summary' => 'High-signal backend profile.',
                'candidate_years' => 6,
                'required_years' => 4,
            ],
        ]);

        $this->actingAs($recruiter)
            ->post(route('applications.automate', $application), [
                'action' => 'shortlist',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'stage' => ApplicationStage::Screening->value,
        ]);
    }

    public function test_recruiter_can_update_interview_status(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $candidate = User::factory()->candidate()->create();

        $job = Job::query()->create([
            'recruiter_id' => $recruiter->id,
            'title' => 'Product Analyst',
            'department' => 'Operations',
            'location' => 'Hybrid',
            'work_mode' => 'hybrid',
            'min_experience_years' => 2,
            'skills' => 'Analysis, Communication',
            'summary' => 'Run hiring operations analytics.',
            'openings' => 1,
            'status' => JobStatus::Open,
        ]);

        $application = Application::query()->create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'stage' => ApplicationStage::Interview,
            'match_score' => 76,
            'screening_snapshot' => [
                'matched_skills' => ['Communication'],
                'missing_skills' => ['Analysis'],
                'recommendation' => 'Review with recruiter',
                'summary' => 'Promising profile with one main gap.',
            ],
        ]);

        $interview = Interview::query()->create([
            'application_id' => $application->id,
            'scheduled_at' => now()->addDay(),
            'interviewer_name' => 'Riya Recruiter',
            'meeting_mode' => MeetingMode::Video,
            'meeting_details' => 'Meet link',
            'notes' => 'Initial screen',
            'status' => InterviewStatus::Scheduled,
        ]);

        $this->actingAs($recruiter)
            ->patch(route('interviews.update', $interview), [
                'status' => InterviewStatus::Completed->value,
                'notes' => 'Strong communication and delivery ownership.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'status' => InterviewStatus::Completed->value,
            'notes' => 'Strong communication and delivery ownership.',
        ]);
    }
}
