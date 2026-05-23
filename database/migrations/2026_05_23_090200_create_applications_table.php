<?php

use App\Enums\ApplicationStage;
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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_openings')->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained('users')->cascadeOnDelete();
            $table->enum('stage', array_column(ApplicationStage::cases(), 'value'))
                ->default(ApplicationStage::Applied->value);
            $table->unsignedTinyInteger('match_score')->default(0);
            $table->json('screening_snapshot')->nullable();
            $table->text('recruiter_notes')->nullable();
            $table->text('cover_note')->nullable();
            $table->string('resume_path')->nullable();
            $table->timestamps();

            $table->unique(['job_id', 'candidate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
