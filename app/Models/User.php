<?php

namespace App\Models;

use App\Enums\UserRole;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'headline',
    'company',
    'email',
    'role',
    'skills',
    'years_of_experience',
    'resume_summary',
    'profile_image_path',
    'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'years_of_experience' => 'integer',
        ];
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'recruiter_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isRecruiter(): bool
    {
        return $this->role === UserRole::Recruiter;
    }

    public function isCandidate(): bool
    {
        return $this->role === UserRole::Candidate;
    }

    public function canManageHiring(): bool
    {
        return $this->isAdmin() || $this->isRecruiter();
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

    public function profileImageUrl(): ?string
    {
        return $this->profile_image_path
        ? Storage::url($this->profile_image_path)
        : null;
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->trim()
            ->explode(' ')
            ->filter()
            ->take(2)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');
    }
}
