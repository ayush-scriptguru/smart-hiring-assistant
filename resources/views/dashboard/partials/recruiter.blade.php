<div class="min-w-full grid lg:grid-cols-12 gap-0 min-h-[calc(100vh-64px)]">
    
    {{-- Sidebar Navigation --}}
    <div class="lg:col-span-3 bg-slate-900 text-white p-6 lg:p-8 shadow-xl">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Recruiter Workspace</p>
        <h1 class="text-base font-extrabold text-white mt-1">Hiring Dashboard</h1>
        
        <div class="mt-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase">Profile Strength</span>
                <span class="text-[10px] font-bold text-indigo-400">{{ $profileCompletion }}%</span>
            </div>
            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-indigo-500 transition-all duration-500" style="width: {{ $profileCompletion }}%"></div>
            </div>
        </div>

        <nav class="mt-8 space-y-1">
            <a href="{{ route('dashboard', ['tab' => 'pipeline']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'pipeline' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                ATS Resume Tracking
            </a>
            <a href="{{ route('dashboard', ['tab' => 'jobs']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'jobs' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                My Job Postings
            </a>
            <a href="{{ route('dashboard', ['tab' => 'create-job']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'create-job' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Job Post
            </a>
            {{-- <a href="{{ route('dashboard', ['tab' => 'candidates']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'candidates' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-4 14V5m0 14a2 2 0 01-2-2V7a2 2 0 012-2m0 14H7a2 2 0 01-2-2V7a2 2 0 012-2h4m0 14v-2m0-4h.01M7 16h.01"/></svg>
                Candidate Pool
            </a> --}}
            <a href="{{ route('dashboard', ['tab' => 'interviews']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'interviews' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Interview Schedule
            </a>
            <a href="{{ route('dashboard', ['tab' => 'profile']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'profile' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile Settings
            </a>
        </nav>
    </div>

    {{-- Main Content Area --}}
    <div class="lg:col-span-9 p-6 lg:p-8 space-y-6 bg-slate-50/30">

        @if ($filters['tab'] === 'pipeline')
            <div class="space-y-6">
                <div class="flex items-center justify-between bg-white p-5 border border-slate-200 rounded-2xl shadow-3xs">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-tight">ATS Resume Tracking & Pipeline</h2>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-full uppercase tracking-widest">{{ count($applications ?? []) }} Active Applications</span>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                    <div class="divide-y divide-slate-100">
                        @forelse ($applications ?? [] as $application)
                            <div class="p-6 hover:bg-slate-50/50 transition duration-300">
                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                                    <div class="flex items-start gap-5">
                                        <div class="h-12 w-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600 flex-shrink-0 shadow-inner">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-extrabold text-slate-900">{{ $application->candidate->name }}</h3>
                                            <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wide">Role: <span class="text-indigo-600">{{ $application->job->title }}</span></p>
                                            <div class="mt-3 flex items-center gap-4">
                                                <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="text-[10px] font-black text-slate-400 hover:text-indigo-600 transition flex items-center gap-1.5 uppercase tracking-widest">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    View Resume
                                                </a>
                                                <span class="h-1 w-1 bg-slate-300 rounded-full"></span>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">Applied {{ $application->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 lg:gap-10">
                                        <div class="text-center px-5 py-2.5 bg-indigo-600 rounded-2xl shadow-indigo-100 shadow-lg">
                                            <p class="text-[9px] font-black text-indigo-100 uppercase tracking-widest mb-0.5">Match Score</p>
                                            <p class="text-xl font-black text-white leading-none">{{ $application->match_score }}%</p>
                                        </div>

                                        <form action="{{ route('applications.update', $application) }}" method="POST" class="flex flex-col gap-1.5">
                                            @csrf
                                            @method('PATCH')
                                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Update Pipeline Stage</label>
                                            <select name="stage" onchange="this.form.submit()" class="text-[11px] font-black bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition uppercase tracking-tight">
                                                @foreach(\App\Enums\ApplicationStage::cases() as $stage)
                                                    <option value="{{ $stage->value }}" {{ $application->stage === $stage ? 'selected' : '' }}>
                                                        {{ $stage->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-20 text-center">
                                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200">
                                    <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No active applications in your pipeline</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @elseif ($filters['tab'] === 'jobs')
            <div class="space-y-6">
                <div class="flex items-center justify-between bg-white p-5 border border-slate-200 rounded-2xl shadow-3xs">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Active Job Postings</h2>
                    <a href="{{ route('dashboard', ['tab' => 'create-job']) }}" class="px-5 py-2.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 transition shadow-lg shadow-slate-200">Post New Role</a>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    @forelse ($jobs ?? [] as $job)
                        <div class="bg-white border border-slate-200 p-6 rounded-2xl shadow-3xs hover:shadow-md transition duration-300 flex flex-col justify-between group">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-[9px] font-black uppercase tracking-widest">{{ $job->department }}</span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $job->status->value }}</span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 group-hover:text-indigo-600 transition">{{ $job->title }}</h3>
                                <p class="text-[11px] text-slate-500 mt-1.5 font-bold uppercase tracking-tight">{{ $job->work_mode }} &bull; {{ $job->location ?? 'Remote' }}</p>
                                
                                <div class="mt-5 flex flex-wrap gap-1.5">
                                    @foreach(explode(',', $job->skills) as $skill)
                                        <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[9px] font-extrabold rounded-lg border border-slate-100 uppercase tracking-tighter">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mt-8 pt-5 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $job->applications_count ?? 0 }} Applicants</span>
                                </div>
                                {{-- <a href="#" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-800 flex items-center gap-1">
                                    Manage Role
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a> --}}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-20 bg-white border border-slate-200 rounded-2xl text-center border-dashed">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">You haven't posted any positions yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @elseif ($filters['tab'] === 'create-job')
            <div class="min-w-full bg-white border border-slate-200 rounded-2xl p-8 shadow-2xs max-w-3xl">
                <div class="mb-8">
                    <h2 class="text-base font-extrabold text-slate-900 uppercase tracking-tight">Post a New Career Opportunity</h2>
                    <p class="text-xs text-slate-500 mt-1 font-bold">Provide the following details to broadcast this role to the candidate pool.</p>
                </div>

                <form action="{{ route('jobs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Job Title / Designation
                            </label>

                            <input
                                name="title"
                                type="text"
                                required
                                placeholder="e.g. Lead Frontend Architect"
                                class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300"
                            >
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Target Department
                            </label>

                            <input
                                name="department"
                                type="text"
                                required
                                placeholder="e.g. Design & UX"
                                class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300"
                            >
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Job Location
                            </label>

                            <input
                                name="location"
                                type="text"
                                required
                                placeholder="e.g. Remote / Bangalore / Delhi"
                                class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300"
                            >
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Work Mode
                            </label>

                            <select
                                name="work_mode"
                                required
                                class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold"
                            >
                                <option value="">Select Work Mode</option>
                                <option value="remote">Remote</option>
                                <option value="hybrid">Hybrid</option>
                                <option value="onsite">Onsite</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Minimum Experience
                            </label>

                            <input
                                name="min_experience_years"
                                type="number"
                                min="0"
                                required
                                placeholder="e.g. 3"
                                class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300"
                            >
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                Open Positions
                            </label>

                            <input
                                name="openings"
                                type="number"
                                min="1"
                                required
                                placeholder="e.g. 5"
                                class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300"
                            >
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Required Skills & Expertise (Comma separated)
                        </label>

                        <input
                            name="skills"
                            type="text"
                            required
                            placeholder="e.g. React, TypeScript, Laravel, Docker"
                            class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300"
                        >
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Position Summary
                        </label>

                        <textarea
                            name="summary"
                            rows="5"
                            required
                            placeholder="Describe responsibilities, expectations, team culture, and requirements..."
                            class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold placeholder:text-slate-300 resize-none"
                        ></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Job Status
                        </label>

                    <div class="pt-4">
                        <button
                            type="submit"
                            class="w-full py-4 bg-indigo-600 text-white font-black text-xs rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 uppercase tracking-widest"
                        >
                            Broadcast Job Posting
                        </button>
                    </div>
                </form>

            </div>
        @elseif ($filters['tab'] === 'candidates')
            {{-- Existing candidates content --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs">
                <h2 class="text-sm font-bold text-slate-900 mb-6">Candidate Pool</h2>
                <p class="text-xs text-slate-500">This is where the recruiter's candidate pool would be displayed.</p>
                {{-- Placeholder for candidate pool --}}
                <div class="p-12 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200 mt-4">
                    <p class="text-xs font-medium text-slate-400">Your candidate pool will appear here.</p>
                </div>
            </div>
@elseif ($filters['tab'] === 'interviews')

<div class="space-y-6">

    <div class="flex items-center justify-between bg-white p-5 border border-slate-200 rounded-2xl shadow-3xs">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-tight">
            Interview Schedule
        </h2>

        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black rounded-full uppercase tracking-widest">
            {{ count($upcomingInterviews ?? []) }} Scheduled
        </span>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">

        @forelse ($upcomingInterviews ?? [] as $interview)

            <div class="p-6 border-b border-slate-100 last:border-b-0">

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">

                    <div>

                        <h3 class="text-sm font-extrabold text-slate-900">
                            {{ $interview->application->candidate->name }}
                        </h3>

                        <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wide mt-1">
                            {{ $interview->application->job->title }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-2">

                            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                {{ $interview->type ?? 'Technical Interview' }}
                            </span>

                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                {{ $interview->status->value }}
                            </span>

                        </div>

                    </div>

                    <div class="text-right">

                        <p class="text-xs font-black text-slate-900">
                            {{ $interview->scheduled_at?->format('d M Y') }}
                        </p>

                        <p class="text-[11px] text-slate-500 font-bold mt-1">
                            {{ $interview->scheduled_at?->format('h:i A') }}
                        </p>

                        @if($interview->meeting_link)
                            <a
                                href="{{ $interview->meeting_link }}"
                                target="_blank"
                                class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 transition"
                            >
                                Join Meeting
                            </a>
                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="p-20 text-center">

                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-slate-200">
                    <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>

                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                    No interviews scheduled yet
                </p>

            </div>

        @endforelse

    </div>

</div>
        @elseif ($filters['tab'] === 'profile')
            <div class="min-w-full bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs max-w-2xl">
                <div class="mb-8 flex flex-col sm:flex-row items-center gap-6 p-6 bg-slate-50/50 border border-slate-100 rounded-2xl">
                    <div class="relative group h-24 w-24">
                        <img src="{{ Auth::user()->profile_image_path ? asset('storage/' . Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="h-full w-full rounded-full object-cover border-4 border-white shadow-sm">
                        <label for="profile_image_input_recruiter" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                        <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" id="profile_image_form_recruiter">
                            @csrf
                            <input type="file" id="profile_image_input_recruiter" name="profile_image" class="hidden" onchange="document.getElementById('profile_image_form_recruiter').submit()">
                        </form>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-base font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                        <p class="text-xs text-slate-500 font-medium">{{ Auth::user()->email }}</p>
                        <button type="button" onclick="document.getElementById('profile_image_input_recruiter').click()" class="mt-3 text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">Update Profile Picture</button>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-8">
                    <h2 class="text-sm font-bold text-slate-900 mb-6">Professional Profile Details</h2>
                    <form action="{{ route('recruiter.profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Professional Headline</label>
                                <input name="headline" type="text" value="{{ auth()->user()->headline }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Company</label>
                                <input name="company" type="text" value="{{ auth()->user()->company }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Skills (Comma separated)</label>
                                <textarea name="skills" rows="2" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium resize-none">{{ auth()->user()->skills }}</textarea>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Years of Experience</label>
                                <input name="years_of_experience" type="number" value="{{ auth()->user()->years_of_experience }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Bio / Summary</label>
                            <textarea name="resume_summary" rows="5" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium resize-none">{{ auth()->user()->resume_summary }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                 @if($profileCompletion < 100)
                                    <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                    <span class="text-[10px] font-bold text-amber-600 uppercase">Profile Incomplete</span>
                                 @else
                                    <svg class="h-4 w-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    <span class="text-[10px] font-bold text-emerald-600 uppercase">Profile Complete</span>
                                 @endif
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold text-xs rounded-xl hover:bg-indigo-700 transition shadow-sm">Save Profile Updates</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>