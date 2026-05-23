<?php

namespace App\Models;

use App\Enums\InterviewStatus;
use App\Enums\MeetingMode;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'application_id',
    'scheduled_at',
    'interviewer_name',
    'meeting_mode',
    'meeting_details',
    'notes',
    'status',
])]
class Interview extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'meeting_mode' => MeetingMode::class,
            'status' => InterviewStatus::class,
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
