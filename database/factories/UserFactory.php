<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'headline' => fake()->jobTitle(),
            'company' => fake()->company(),
            'email' => fake()->unique()->safeEmail(),
            'role' => UserRole::Candidate,
            'skills' => 'Laravel, PHP, Communication',
            'years_of_experience' => fake()->numberBetween(1, 8),
            'resume_summary' => fake()->sentence(18),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Admin,
            'company' => 'Smart Hiring HQ',
        ]);
    }

    public function recruiter(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Recruiter,
            'company' => fake()->company(),
            'headline' => 'Talent Partner',
            'skills' => 'Sourcing, Interviewing, Stakeholder Management',
            'resume_summary' => 'Focused on moving candidates through a fast, structured hiring process.',
        ]);
    }

    public function candidate(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Candidate,
            'company' => null,
            'headline' => 'Full-stack Developer',
            'skills' => 'Laravel, PHP, MySQL, JavaScript',
            'years_of_experience' => 4,
            'resume_summary' => 'Built internal tools, hiring dashboards, and automation workflows.',
        ]);
    }
}
