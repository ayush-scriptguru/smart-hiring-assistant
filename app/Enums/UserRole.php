<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Recruiter = 'recruiter';
    case Candidate = 'candidate';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Recruiter => 'Recruiter',
            self::Candidate => 'Candidate',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function registrationOptions(): array
    {
        return [
            self::Recruiter->value => 'Recruiter',
            self::Candidate->value => 'Candidate',
        ];
    }
}
