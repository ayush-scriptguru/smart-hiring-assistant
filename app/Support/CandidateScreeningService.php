<?php

namespace App\Support;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;

class CandidateScreeningService
{
    /**
     * @return array{
     *     score: int,
     *     matched_skills: array<int, string>,
     *     missing_skills: array<int, string>,
     *     recommendation: string,
     *     summary: string,
     *     candidate_years: int,
     *     required_years: int
     * }
     */
    public function evaluate(User $candidate, Job $job): array
    {
        $jobSkills = $this->normalizeSkills($job->skillsList());
        $candidateSkills = $this->normalizeSkills($candidate->skillsList());

        $matchedSkills = collect($jobSkills)
            ->filter(fn (string $label, string $normalized) => array_key_exists($normalized, $candidateSkills))
            ->values()
            ->all();

        $missingSkills = collect($jobSkills)
            ->reject(fn (string $label, string $normalized) => array_key_exists($normalized, $candidateSkills))
            ->values()
            ->all();

        $skillsScore = empty($jobSkills)
            ? 65
            : (int) round((count($matchedSkills) / count($jobSkills)) * 75);

        $candidateYears = max(0, (int) $candidate->years_of_experience);
        $requiredYears = max(0, (int) $job->min_experience_years);
        $experienceRatio = $requiredYears === 0
            ? 1
            : min(1.2, $candidateYears / max(1, $requiredYears));
        $experienceScore = (int) round(min(25, $experienceRatio * 25));

        $profileBonus = filled($candidate->resume_summary) ? 5 : 0;
        $score = min(100, $skillsScore + $experienceScore + $profileBonus);

        $recommendation = match (true) {
            $score >= 82 => 'Shortlist now',
            $score >= 65 => 'Review with recruiter',
            default => 'Keep in warm pipeline',
        };

        $summary = $this->buildSummary(
            matchedSkills: $matchedSkills,
            missingSkills: $missingSkills,
            candidateYears: $candidateYears,
            requiredYears: $requiredYears,
            score: $score,
        );

        return [
            'score' => $score,
            'matched_skills' => $matchedSkills,
            'missing_skills' => $missingSkills,
            'recommendation' => $recommendation,
            'summary' => $summary,
            'candidate_years' => $candidateYears,
            'required_years' => $requiredYears,
        ];
    }

    public function refreshApplication(Application $application): Application
    {
        $snapshot = $this->evaluate($application->candidate, $application->job);

        $application->forceFill([
            'match_score' => $snapshot['score'],
            'screening_snapshot' => $snapshot,
        ])->save();

        return $application->refresh();
    }

    /**
     * @param array<int, string> $skills
     * @return array<string, string>
     */
    protected function normalizeSkills(array $skills): array
    {
        $normalized = [];

        foreach ($skills as $skill) {
            $label = trim($skill);

            if ($label === '') {
                continue;
            }

            $key = mb_strtolower($label);
            $normalized[$key] = $label;
        }

        return $normalized;
    }

    /**
     * @param array<int, string> $matchedSkills
     * @param array<int, string> $missingSkills
     */
    protected function buildSummary(
        array $matchedSkills,
        array $missingSkills,
        int $candidateYears,
        int $requiredYears,
        int $score,
    ): string {
        $skillsSummary = empty($matchedSkills)
            ? 'No direct skill overlap found yet.'
            : 'Strong overlap in '.implode(', ', array_slice($matchedSkills, 0, 3)).'.';

        $experienceSummary = $requiredYears === 0
            ? "This role is open to mixed experience levels, and the candidate shows {$candidateYears} years of experience."
            : match (true) {
                $candidateYears >= $requiredYears => "Experience target met with {$candidateYears} years against a {$requiredYears}-year requirement.",
                default => "Experience is slightly short at {$candidateYears} years versus the {$requiredYears}-year target.",
            };

        $gapSummary = empty($missingSkills)
            ? 'No major skill gaps detected.'
            : 'Main gaps: '.implode(', ', array_slice($missingSkills, 0, 3)).'.';

        return "Score {$score}/100. {$skillsSummary} {$experienceSummary} {$gapSummary}";
    }
}
