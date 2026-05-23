<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecruiterProfileController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'headline' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:50'],
            'resume_summary' => ['nullable', 'string', 'max:2500'], // Used as a general bio for recruiters
        ]);

        $user->update($validated);

        return back()->with('status', 'Recruiter profile updated successfully.');
    }
}