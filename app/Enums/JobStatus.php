<?php

namespace App\Enums;

enum JobStatus: string
{
    case Open = 'open';
    case Paused = 'paused';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Paused => 'Paused',
            self::Closed => 'Closed',
        };
    }
}
