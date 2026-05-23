<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable([
    'recruiter_id',
    'title',
    'department',
    'location',
    'work_mode',
    'min_experience_years',
    'skills',
    'summary',
    'openings',
    'status',
])]
class Job extends Model
{
    use HasFactory;

    protected $table = 'job_openings';

    protected function casts(): array
    {
        return [
            'min_experience_years' => 'integer',
            'openings' => 'integer',
            'status' => JobStatus::class,
        ];
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function interviews(): HasManyThrough
    {
        return $this->hasManyThrough(Interview::class, Application::class);
    }

    /**
     * @return array<int, string>
     */
    public function skillsList(): array
    {
        return collect(preg_split('/[\r\n,]+/', (string) $this->skills))
            ->map(fn (?string $skill) => trim((string) $skill))
            ->filter()
            ->unique(fn (string $skill) => mb_strtolower($skill))
            ->values()
            ->all();
    }
}
