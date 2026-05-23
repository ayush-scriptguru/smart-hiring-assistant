@extends('layouts.app')

@section('content')
   <!-- ================= HERO SECTION ================= -->
    <section class="max-w-7xl mx-auto px-6 pt-16 pb-12 w-full">
        <div class="grid lg:grid-cols-12 gap-12 items-center">

            <!-- Left Column -->
            <div class="lg:col-span-7 space-y-5">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-700 text-[11px] font-bold tracking-wide uppercase">
                    <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                    Smart Hiring Workspace
                </div>

                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 leading-[1.1]">
                    Modern hiring for <br />
                    <span class="text-indigo-600">modern teams.</span>
                </h1>

                <p class="text-sm font-medium text-slate-500 leading-relaxed max-w-xl">
                    Streamline recruitment workflows, manage candidates, schedule interviews, and track hiring progress seamlessly from a single corporate workspace ecosystem.
                </p>

                <!-- Interactive CTAs -->
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="{{ route('register') }}"
                        class="px-5 py-2.5 rounded-lg bg-slate-900 hover:bg-slate-800 transition font-semibold text-xs text-white shadow-sm">
                        Start Hiring
                    </a>

                    <a href="#"
                        class="px-5 py-2.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition font-semibold text-xs shadow-sm">
                        Live Preview
                    </a>
                </div>

                <!-- Analytics -->
                <div class="flex flex-wrap gap-10 pt-6 border-t border-slate-200/60 mt-6">
                    <div>
                        <div class="text-xl font-bold text-slate-900 tracking-tight">10K+</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Applications</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-slate-900 tracking-tight">94%</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Match Accuracy</div>
                    </div>
                    <div>
                        <div class="text-xl font-bold text-slate-900 tracking-tight">3x</div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Faster Hiring</div>
                    </div>
                </div>
            </div>

            <!-- Right Column (Clean Dashboard Preview) -->
            <div class="lg:col-span-5 relative">
                <div class="relative bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-xs font-bold text-slate-900">Recruitment Dashboard</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Live hiring activity</p>
                        </div>
                        <div class="flex items-center gap-1.5 text-emerald-700 text-[10px] font-bold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Live
                        </div>
                    </div>

                    <!-- Info Rows -->
                    <div class="space-y-2.5">
                        <div class="bg-slate-50/50 border border-slate-100 rounded-lg p-3 flex items-center justify-between transition hover:bg-slate-50">
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Resume Screening</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">28 candidates pending review</p>
                            </div>
                            <div class="text-indigo-600 font-bold text-xs bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">82%</div>
                        </div>

                        <div class="bg-slate-50/50 border border-slate-100 rounded-lg p-3 flex items-center justify-between transition hover:bg-slate-50">
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Interview Scheduling</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">12 interviews today</p>
                            </div>
                            <div class="text-slate-600 font-bold text-[11px] bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Active</div>
                        </div>

                        <div class="bg-slate-50/50 border border-slate-100 rounded-lg p-3 flex items-center justify-between transition hover:bg-slate-50">
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">AI Match Analysis</h4>
                                <p class="text-[11px] text-slate-400 mt-0.5">Smart candidate scoring</p>
                            </div>
                            <div class="text-emerald-600 font-bold text-xs bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">94%</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <section class="grid gap-8 lg:grid-cols-12 items-start">
        
        <!-- ================= LEFT COLUMN: PRIMARY VALUE HERO ================= -->
        <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-[0_1px_3px_rgba(0,0,0,0.05)]">
            <p class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold tracking-wide uppercase">
                Smart Hiring Assistant
            </p>
            
            <h1 class="mt-5 text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                A compact hiring workspace for screening, scheduling, and keeping recruiters focused.
            </h1>
            
            <p class="mt-4 text-sm font-medium leading-relaxed text-slate-500 max-w-2xl">
                Built for a hackathon, this MVP keeps the scope small and the value obvious: role-based login, recruiter dashboard, candidate profiles, application scoring, and interview scheduling in one flow.
            </p>

            <!-- Call to Actions -->
            <div class="mt-8 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm">
                        Open Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm">
                        Start Building
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl transition border border-slate-200 shadow-sm">
                        Log In
                    </a>
                @endauth
            </div>

            <!-- Mini Horizontal Stats Grid -->
            <div class="mt-12 pt-8 border-t border-slate-100 grid gap-4 sm:grid-cols-3">
                <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-4 transition hover:bg-slate-50">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Resume Screening</span>
                    <span class="block mt-1.5 text-xs font-bold text-slate-800">Skill overlap & fit score</span>
                </div>
                <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-4 transition hover:bg-slate-50">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Candidate Analysis</span>
                    <span class="block mt-1.5 text-xs font-bold text-slate-800">Strengths, gaps, notes</span>
                </div>
                <div class="bg-slate-50/50 border border-slate-100 rounded-xl p-4 transition hover:bg-slate-50">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Interview Manager</span>
                    <span class="block mt-1.5 text-xs font-bold text-slate-800">Book and track interviews</span>
                </div>
            </div>
        </div>

        <!-- ================= RIGHT COLUMN: APP ARCHITECTURE ================= -->
        <div class="lg:col-span-5 grid gap-6">
            
            <!-- Context Box: Why it Works -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-[0_1px_3px_rgba(0,0,0,0.05)]">
                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Product Strategy</p>
                <h3 class="text-sm font-bold text-slate-900 mt-1">Why this MVP works</h3>
                
                <div class="mt-6 space-y-5">
                    <div class="relative pl-4 border-l-2 border-indigo-500/30 hover:border-indigo-500 transition-colors py-0.5">
                        <h4 class="text-xs font-bold text-slate-900">Recruiters stay in one place</h4>
                        <p class="mt-1 text-xs font-medium text-slate-500 leading-relaxed">Create roles, review applications, move pipeline stages, and schedule interviews without jumping contexts.</p>
                    </div>
                    
                    <div class="relative pl-4 border-l-2 border-indigo-500/30 hover:border-indigo-500 transition-colors py-0.5">
                        <h4 class="text-xs font-bold text-slate-900">Candidates feed the funnel</h4>
                        <p class="mt-1 text-xs font-medium text-slate-500 leading-relaxed">Registration includes seamless role selection, profile building, and live testing dataset applications.</p>
                    </div>
                    
                    <div class="relative pl-4 border-l-2 border-indigo-500/30 hover:border-indigo-500 transition-colors py-0.5">
                        <h4 class="text-xs font-bold text-slate-900">Automation stays believable</h4>
                        <p class="mt-1 text-xs font-medium text-slate-500 leading-relaxed">Instead of overpromising broken AI models, the framework handles logical deterministic fit scoring heuristics.</p>
                    </div>
                </div>
            </div>

            <!-- Context Box: Role Matrix -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-[0_1px_3px_rgba(0,0,0,0.05)]">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Access Control Matrix</p>
                <h3 class="text-sm font-bold text-slate-900 mt-1">Platform Ecosystem Roles</h3>
                
                <div class="mt-5 grid gap-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3.5 flex gap-3 items-start transition hover:bg-slate-50">
                        <div class="text-[11px] font-bold bg-slate-900 text-white px-2 py-0.5 rounded shadow-sm mt-0.5">01</div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">System Admin</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500 leading-normal">Sees an aggregate, cross-team view of open metrics, global parameters, and system interviews.</p>
                        </div>
                    </div>
                    
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3.5 flex gap-3 items-start transition hover:bg-slate-50">
                        <div class="text-[11px] font-bold bg-indigo-600 text-white px-2 py-0.5 rounded shadow-sm mt-0.5">02</div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Corporate Recruiter</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500 leading-normal">Owns direct pipeline control, candidate profiles updates, and algorithmic scheduling logic loops.</p>
                        </div>
                    </div>
                    
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-3.5 flex gap-3 items-start transition hover:bg-slate-50">
                        <div class="text-[11px] font-bold bg-white text-slate-600 border border-slate-200 px-2 py-0.5 rounded shadow-sm mt-0.5">03</div>
                        <div>
                            <p class="text-xs font-bold text-slate-900">Active Candidate</p>
                            <p class="mt-0.5 text-xs font-medium text-slate-500 leading-normal">Maintains a centralized secure profile wallet, processes applications, and monitors tracking states.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
@endsection