<?php

namespace App\Models;

use App\Enums\ApplicationStage;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'job_id',
    'candidate_id',
    'stage',
    'match_score',
    'screening_snapshot',
    'recruiter_notes',
    'cover_note',
    'resume_path',
])]
class Application extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'stage' => ApplicationStage::class,
            'match_score' => 'integer',
            'screening_snapshot' => 'array',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'candidate_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    /**
     * @return array<int, string>
     */
    public function matchedSkills(): array
    {
        return data_get($this->screening_snapshot, 'matched_skills', []);
    }

    /**
     * @return array<int, string>
     */
    public function missingSkills(): array
    {
        return data_get($this->screening_snapshot, 'missing_skills', []);
    }

    public function recommendation(): ?string
    {
        return data_get($this->screening_snapshot, 'recommendation');
    }

    public function screeningSummary(): ?string
    {
        return data_get($this->screening_snapshot, 'summary');
    }

    public function candidateYears(): int
    {
        return (int) data_get($this->screening_snapshot, 'candidate_years', $this->candidate?->years_of_experience ?? 0);
    }

    public function requiredYears(): int
    {
        return (int) data_get($this->screening_snapshot, 'required_years', $this->job?->min_experience_years ?? 0);
    }

    public function experienceDelta(): int
    {
        return $this->candidateYears() - $this->requiredYears();
    }

    public function skillCoverage(): int
    {
        $totalSkills = count($this->matchedSkills()) + count($this->missingSkills());

        if ($totalSkills === 0) {
            return 0;
        }

        return (int) round((count($this->matchedSkills()) / $totalSkills) * 100);
    }

    public function fitBand(): string
    {
        return match (true) {
            $this->match_score >= 85 => 'Excellent fit',
            $this->match_score >= 70 => 'Strong fit',
            $this->match_score >= 55 => 'Needs review',
            default => 'Low fit',
        };
    }

    public function missingSkillsCount(): int
    {
        return count($this->missingSkills());
    }

    public function nextScheduledInterview(): ?Interview
    {
        return $this->interviews
            ->first(fn (Interview $interview) => $interview->status === \App\Enums\InterviewStatus::Scheduled && $interview->scheduled_at?->isFuture());
    }
}
