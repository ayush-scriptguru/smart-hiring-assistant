<?php

use App\Enums\UserRole;
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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', array_column(UserRole::cases(), 'value'))
                ->default(UserRole::Candidate->value)
                ->after('email');
            $table->string('headline')->nullable()->after('name');
            $table->string('company')->nullable()->after('headline');
            $table->text('skills')->nullable()->after('company');
            $table->unsignedTinyInteger('years_of_experience')->nullable()->after('skills');
            $table->text('resume_summary')->nullable()->after('years_of_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'headline',
                'company',
                'skills',
                'years_of_experience',
                'resume_summary',
            ]);
        });
    }
};
