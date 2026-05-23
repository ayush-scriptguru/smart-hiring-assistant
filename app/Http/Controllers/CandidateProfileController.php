<?php

namespace App\Http\Controllers;

use App\Support\CandidateScreeningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidateProfileController extends Controller
{
    public function __construct(
        protected CandidateScreeningService $screeningService,
    ) {
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'headline' => ['nullable', 'string', 'max:255'],
            'skills' => ['nullable', 'string', 'max:1500'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:40'],
            'resume_summary' => ['nullable', 'string', 'max:2500'],
        ]);

        $user = $request->user();
        $user->update($validated);

        $user->applications()
            ->with(['job', 'candidate'])
            ->get()
            ->each(fn ($application) => $this->screeningService->refreshApplication($application));

        return back()->with('status', 'Candidate profile updated and application scores refreshed.');
    }
}
