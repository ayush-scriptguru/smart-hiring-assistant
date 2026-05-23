<?php

namespace App\Enums;

enum MeetingMode: string
{
    case Video = 'video';
    case Phone = 'phone';
    case Onsite = 'onsite';

    public function label(): string
    {
        return match ($this) {
            self::Video => 'Video',
            self::Phone => 'Phone',
            self::Onsite => 'Onsite',
        };
    }
}
