<?php

use App\Enums\InterviewStatus;
use App\Enums\MeetingMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('interviewer_name');
            $table->enum('meeting_mode', array_column(MeetingMode::cases(), 'value'))
                ->default(MeetingMode::Video->value);
            $table->string('meeting_details')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', array_column(InterviewStatus::cases(), 'value'))
                ->default(InterviewStatus::Scheduled->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
