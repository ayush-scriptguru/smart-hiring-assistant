<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Support\CandidateScreeningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;

class ResumeScannerController extends Controller
{
    public function scan(Request $request, CandidateScreeningService $screeningService): RedirectResponse
    {
        $request->validate([
            'job_id' => ['required', 'exists:job_openings,id'],
            'resume_pdf' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $job = Job::findOrFail($request->input('job_id'));

        try {
            $text = Pdf::getText($request->file('resume_pdf')->path(), config('services.pdftotext.path'));
        } catch (\Exception $e) {
            return back()->withErrors(['resume_pdf' => 'Failed to parse PDF. Ensure pdftotext is installed: ' . $e->getMessage()]);
        }

        $dummyCandidate = new User([
            'name' => 'Quick Scan Candidate',
            'resume_summary' => $text,
            'skills' => '',
        ]);

        $result = $screeningService->evaluate($dummyCandidate, $job);

        return back()->with('status', "ATS Scan Complete! Match Score: {$result['score']} pts.");
    }
}