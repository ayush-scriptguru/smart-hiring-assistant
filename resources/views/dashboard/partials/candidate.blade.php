<div class="min-w-full grid lg:grid-cols-12 gap-0 min-h-[calc(100vh-64px)]">
    
    {{-- Sidebar Navigation --}}
    <div class="lg:col-span-3 bg-slate-900 text-white p-6 lg:p-8 shadow-xl mt-2">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Candidate Workspace</p>
        <h1 class="text-base font-extrabold text-white mt-1">Career Dashboard</h1>
        
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
            <a href="{{ route('dashboard', ['tab' => 'jobs']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'jobs' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Available Roles
            </a>
            <a href="{{ route('dashboard', ['tab' => 'applying']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'applying' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                My Applications
            </a>
            <a href="{{ route('dashboard', ['tab' => 'recruiters']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'recruiters' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Recruiter Network
            </a>
            <a href="{{ route('dashboard', ['tab' => 'profile']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition {{ $filters['tab'] === 'profile' ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile Settings
            </a>
        </nav>
    </div>

    {{-- Main Content Area --}}
    <div class="lg:col-span-9 p-6 lg:p-8 space-y-6 bg-slate-50/30">

        @if ($filters['tab'] === 'jobs')
            <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-3xs">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-3">
                    <input type="hidden" name="tab" value="jobs">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search by title, skills, or department..." class="w-full pl-10 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">Filter Roles</button>
                </form>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                @forelse ($openJobs as $job)
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl shadow-3xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider">{{ $job->department }}</span>
                                <span class="text-[10px] font-bold text-slate-400">{{ $job->work_mode }}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $job->title }}</h3>
                            <p class="text-[11px] text-slate-500 mt-1 line-clamp-2 font-medium">{{ $job->summary }}</p>
                            
                            <div class="mt-4 flex flex-wrap gap-1">
                                @foreach(explode(',', $job->skills) as $skill)
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold rounded-md">{{ trim($skill) }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-50">
                            @if($applications->where('job_id', $job->id)->first())
                                <button disabled class="w-full py-2 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">Already Applied</button>
                            @else
                                <form action="{{ route('applications.store', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div class="relative">
                                        <input type="file" name="resume_pdf" required accept=".pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" onchange="this.nextElementSibling.innerText = this.files[0].name">
                                        <div class="w-full py-2 border border-dashed border-slate-300 rounded-xl text-center text-[10px] font-bold text-slate-500 bg-slate-50 px-2 truncate">
                                            Click to attach Resume (PDF)
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition">Apply Now</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 bg-white border border-slate-200 rounded-2xl text-center">
                        <p class="text-xs font-medium text-slate-400">No open positions available at the moment.</p>
                    </div>
                @endforelse
            </div>

        @elseif ($filters['tab'] === 'applying')
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-900">Application Tracking</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($applications as $application)
                        <div class="p-5 hover:bg-slate-50/50 transition flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">{{ $application->job->title }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $application->job->recruiter->company ?? 'Direct Hire' }} &bull; {{ $application->stage->label() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    ATS: {{ $application->match_score }}% Match
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 font-bold">Applied {{ $application->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400 text-xs font-medium">You haven't applied to any jobs yet.</div>
                    @endforelse
                </div>
            </div>

        @elseif ($filters['tab'] === 'recruiters')
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs">
                <div class="mb-6">
                    <h2 class="text-sm font-bold text-slate-900">Recruiter Network</h2>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Browse and connect with recruiters from top organizations.</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-4">
                    @foreach($recruiters ?? [] as $recruiter)
                         <div class="p-4 border border-slate-100 rounded-2xl bg-slate-50/30">
                             <div class="flex items-center gap-3 mb-3">
                                 <div class="h-8 w-8 bg-white rounded-lg border border-slate-100 flex items-center justify-center font-black text-indigo-600 text-xs shadow-sm">
                                     {{ substr($recruiter->company ?? 'C', 0, 1) }}
                                 </div>
                                 <div>
                                     <p class="text-[11px] font-bold text-slate-900">{{ $recruiter->company ?? 'Confidential' }}</p>
                                     <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">{{ $recruiter->name }}</p>
                                 </div>
                             </div>
                             <a href="#" class="block w-full py-1.5 text-center bg-white border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-50 transition">View Company</a>
                         </div>
                    @endforeach
                </div>
                @if(!isset($recruiters) || count($recruiters) === 0)
                    <div class="p-12 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-xs font-medium text-slate-400">Discover and connect with top recruiters.</p>
                    </div>
                @endif
            </div>

        @elseif ($filters['tab'] === 'profile')
            <div class="min-w-full bg-white border border-slate-200 rounded-2xl p-6 shadow-2xs max-w-2xl">
                <div class="mb-8 flex flex-col sm:flex-row items-center gap-6 p-6 bg-slate-50/50 border border-slate-100 rounded-2xl">
                    <div class="relative group h-24 w-24">
                        <img src="{{ Auth::user()->profile_image_path ? asset('storage/' . Auth::user()->profile_image_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="h-full w-full rounded-full object-cover border-4 border-white shadow-sm">
                        <label for="profile_image_input" class="absolute inset-0 flex items-center justify-center bg-black/40 text-white rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                        <form action="{{ route('profile.image.update') }}" method="POST" enctype="multipart/form-data" id="profile_image_form">
                            @csrf
                            <input type="file" id="profile_image_input" name="profile_image" class="hidden" onchange="document.getElementById('profile_image_form').submit()">
                        </form>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-base font-bold text-slate-900">{{ Auth::user()->name }}</h3>
                        <p class="text-xs text-slate-500 font-medium">{{ Auth::user()->email }}</p>
                        <button type="button" onclick="document.getElementById('profile_image_input').click()" class="mt-3 text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:text-indigo-700">Update Profile Picture</button>
                    </div>
                </div>

                <div class="min-w-full border-t border-slate-100 pt-8">
                    <h2 class="text-sm font-bold text-slate-900 mb-6">Professional Profile Details</h2>
                    <form action="{{ route('candidate.profile.update') }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')
                        
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Professional Headline</label>
                                <input name="headline" type="text" value="{{ auth()->user()->headline }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Years of Experience</label>
                                <input name="years_of_experience" type="number" value="{{ auth()->user()->years_of_experience }}" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Skills (Comma separated)</label>
                            <textarea name="skills" rows="2" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none font-medium resize-none">{{ auth()->user()->skills }}</textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-slate-400 uppercase">Resume Summary / Bio</label>
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