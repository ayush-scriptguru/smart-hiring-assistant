@extends('layouts.app')

@section('content')
    <section class="grid gap-8 lg:grid-cols-12 items-start">
        
        <!-- ================= LEFT COLUMN: ROLE INFO ================= -->
        <div class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-[0_1px_3px_rgba(0,0,0,0.05)]">
                <p class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold tracking-wide uppercase">
                    Quick Setup
                </p>
                
                <h1 class="mt-4 text-2xl font-extrabold tracking-tight text-slate-900 leading-snug">
                    Create a role-aware account for the demo.
                </h1>
                
                <p class="mt-3 text-xs font-medium leading-relaxed text-slate-500">
                    Recruiters get the hiring command center. Candidates get a lightweight profile, open roles, and application tracking. Admin exists for broader system oversight.
                </p>
            </div>

            <!-- Role Cards (Vertical Tiles) -->
            <div class="space-y-3">
                <div class="p-4 bg-white border border-slate-200 rounded-xl relative pl-5 border-l-2 border-l-indigo-500 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-900">Recruiter Persona</h3>
                    <p class="mt-1 text-xs font-medium text-slate-500 leading-relaxed">Create corporate roles, shortlist global candidates, manage pipelines, and schedule structured loops.</p>
                </div>

                <div class="p-4 bg-white border border-slate-200 rounded-xl relative pl-5 border-l-2 border-l-slate-400 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-900">Candidate Persona</h3>
                    <p class="mt-1 text-xs font-medium text-slate-500 leading-relaxed">Share system skills, publish comprehensive resume parameters, apply to positions, and monitor status.</p>
                </div>
            </div>
        </div>

        <!-- ================= RIGHT COLUMN: REGISTRATION WIZARD ================= -->
        <div class="lg:col-span-8 bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-[0_1px_3px_rgba(0,0,0,0.05)]">
            <div>
                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Registration Portal</p>
                <h2 class="mt-1 text-xl font-extrabold text-slate-900">Start with the essentials</h2>
            </div>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-5 sm:grid-cols-2">
                @csrf
                
                <!-- Full Name -->
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="name" class="text-xs font-bold text-slate-700">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium" required>
                </div>

                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-slate-700">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium" required>
                </div>

                <!-- Role Selection -->
                <div class="space-y-1.5">
                    <label for="role" class="text-xs font-bold text-slate-700">Account Role Mapping</label>
                    <div class="relative">
                        <select id="role" name="role" 
                                class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium appearance-none cursor-pointer" required>
                            <option value="">Select an application role</option>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" @selected(old('role') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <!-- Headline -->
                <div class="space-y-1.5">
                    <label for="headline" class="text-xs font-bold text-slate-700">Professional Headline</label>
                    <input id="headline" name="headline" type="text" value="{{ old('headline') }}" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium placeholder-slate-400" placeholder="e.g., Talent Partner or Full-stack Developer">
                </div>

                <!-- Company -->
                <div class="space-y-1.5">
                    <label for="company" class="text-xs font-bold text-slate-700">Organization / Company</label>
                    <input id="company" name="company" type="text" value="{{ old('company') }}" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium placeholder-slate-400" placeholder="Required for recruiters">
                </div>

                <!-- Experience -->
                <div class="space-y-1.5 sm:col-span-2 md:col-span-1">
                    <label for="years_of_experience" class="text-xs font-bold text-slate-700">Years of Experience</label>
                    <input id="years_of_experience" name="years_of_experience" type="number" min="0" max="40" value="{{ old('years_of_experience') }}" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium">
                </div>

                <!-- Space filler for structural grid alignment on large screens -->
                <div class="hidden md:block"></div>

                <!-- Password -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-slate-700">Security Password</label>
                    <input id="password" name="password" type="password" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium placeholder-slate-400" placeholder="••••••••" required>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-xs font-bold text-slate-700">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" 
                           class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium placeholder-slate-400" placeholder="••••••••" required>
                </div>

                <!-- Skills -->
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="skills" class="text-xs font-bold text-slate-700">Skills & Focus Keywords</label>
                    <textarea id="skills" name="skills" rows="2" 
                              class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium placeholder-slate-400 resize-none" placeholder="Laravel, Sourcing, Communication, MySQL">{{ old('skills') }}</textarea>
                </div>

                <!-- Resume Summary -->
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="resume_summary" class="text-xs font-bold text-slate-700">Profile / Resume Summary</label>
                    <textarea id="resume_summary" name="resume_summary" rows="4" 
                              class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition text-slate-900 font-medium placeholder-slate-400" placeholder="Share the strongest highlights you want the system core heuristics to screen against.">{{ old('resume_summary') }}</textarea>
                </div>

                <!-- Profile Image Uplodader Container -->
                <div class="space-y-2 sm:col-span-2 bg-slate-50/50 border border-dashed border-slate-200 rounded-2xl p-4 transition hover:bg-slate-50">
                    <label for="profile_image" class="text-xs font-bold text-slate-700 block cursor-pointer">Profile Avatar Image</label>
                    <input id="profile_image" name="profile_image" type="file" accept=".jpg,.jpeg,.png,.webp" 
                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-slate-800 file:transition file:cursor-pointer cursor-pointer pt-1">
                    <p class="text-[11px] font-medium text-slate-400">Optional profile attachment. JPG, PNG, or WEBP formats accepted up to 2 MB capacity.</p>
                </div>

                <!-- Submit Button -->
                <div class="sm:col-span-2 pt-2">
                    <button type="submit" 
                            class="w-full px-4 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm tracking-wide">
                        Create Account
                    </button>
                </div>
            </form>

            <!-- Bottom redirect text link -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-xs font-medium text-slate-500">
                    Already registered? 
                    <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 transition">
                        Log in to workspace
                    </a>
                </p>
            </div>
        </div>
    </section>
@endsection