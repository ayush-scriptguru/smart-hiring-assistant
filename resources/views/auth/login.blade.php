@extends('layouts.app')

@section('content')
    <!-- Split Screen Grid Wrapper -->
    <div class="mt-16 grid min-h-[500px] lg:grid-cols-12 gap-0 overflow-hidden bg-white border border-slate-200 rounded-3xl shadow-sm">
        <!-- ================= LEFT PANEL: BRAND COMPONENT ================= -->
        <div class="hidden lg:flex lg:col-span-5 bg-slate-900 p-12 flex-col justify-between relative overflow-hidden">
            <!-- Subtle architectural background graphic -->
            <div class="absolute inset-0 opacity-[0.03] pointer-events-none">
                <div class="absolute inset-0 bg-[linear-gradient(to_bottom,#ffffff_1px,transparent_1px),linear-gradient(to_right,#ffffff_1px,transparent_1px)] bg-[size:30px_30px]"></div>
            </div>

            <!-- Top Brand Placement -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="h-8 w-8 rounded-lg bg-white p-2 flex items-center justify-center">
                    <img src="{{ asset('logo/logo-sq-trans.png') }}" alt="logo" class="h-full w-full object-contain">
                </div>
                <span class="text-sm font-bold tracking-tight text-white">Smart Hiring</span>
            </div>

            <!-- Focal Context Block -->
            <div class="relative z-10">
                <h2 class="text-2xl font-extrabold tracking-tight text-white leading-snug">
                    Run your hiring pipeline from a single workspace.
                </h2>
                <p class="mt-2 text-xs font-medium text-slate-400 leading-relaxed">
                    Screen candidate profiles, view deterministic skill match summaries, and organize structured calendars securely.
                </p>
            </div>

            <!-- Footer Details -->
            <div class="relative z-10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Recruiter Portal v2.0</span>
            </div>
        </div>

        <!-- ================= RIGHT PANEL: LOGIN FORM ================= -->
        <div class="lg:col-span-7 flex items-center justify-center p-6 sm:p-12 md:p-16 bg-white">
            <div class="w-full max-w-sm space-y-6">
                
                <div>
                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest">Welcome Back</p>
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">Sign in to account</h2>
                </div>

                <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Email Form Input -->
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-slate-700">Email Address</label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}" 
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition placeholder-slate-400 text-slate-900 font-medium" 
                               placeholder="name@company.com"
                               required 
                               autofocus>
                    </div>

                    <!-- Password Form Input -->
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-bold text-slate-700">Password</label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               class="w-full px-3.5 py-2.5 text-sm bg-slate-50/60 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:bg-white transition placeholder-slate-400 text-slate-900 font-medium" 
                               placeholder="••••••••"
                               required>
                    </div>

                    <!-- Remember Option Controls -->
                    <div class="flex items-center pt-1">
                        <label class="inline-flex items-center gap-2.5 text-xs font-semibold text-slate-600 cursor-pointer select-none">
                            <input type="checkbox" 
                                   name="remember" 
                                   value="1" 
                                   class="h-4 w-4 rounded-md border-slate-200 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0 bg-slate-50">
                            Keep me signed in
                        </label>
                    </div>

                    <!-- Action CTA Button -->
                    <button type="submit" 
                            class="w-full mt-2 px-4 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl transition shadow-sm tracking-wide">
                        Sign In
                    </button>
                </form>

                <!-- Footer Redirection Link -->
                <div class="pt-4 border-t border-slate-100 text-center">
                    <p class="text-xs font-medium text-slate-500">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 transition">
                            Create one here
                        </a>
                    </p>
                </div>

            </div>
        </div>
    
    </div>
@endsection