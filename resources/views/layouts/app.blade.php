<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        {{ isset($title) ? $title . ' | Smart Hiring Assistant' : 'Smart Hiring Assistant' }}
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#fafafa] text-slate-900 antialiased overflow-x-hidden selection:bg-indigo-500/10">

    <!-- Background Grid Pattern -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_bottom,rgba(0,0,0,0.015)_1px,transparent_1px),linear-gradient(to_right,rgba(0,0,0,0.015)_1px,transparent_1px)] bg-[size:40px_40px]"></div>
    </div>

    <div class="toast-stack" aria-live="polite" aria-atomic="true">
        @if (session('status'))
            <div class="toast-card toast-card-success" data-toast role="status">
                <div class="toast-icon toast-icon-success" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                        <path d="M7 12.5 10.2 15.7 17 9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="toast-title">Success</p>
                    <p class="toast-message">{{ session('status') }}</p>
                </div>
                <button type="button" class="toast-close" data-toast-close aria-label="Dismiss notification">
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4">
                        <path d="M6 6 18 18M18 6 6 18" stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
                    </svg>
                </button>
                <span class="toast-progress toast-progress-success"></span>
            </div>
        @endif

        @if ($errors->any())
            <div class="toast-card toast-card-error" data-toast role="alert">
                <div class="toast-icon toast-icon-error" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                        <path d="M12 8v5m0 3.5h.01M10.3 3.9 2.6 17.2A1.8 1.8 0 0 0 4.2 20h15.6a1.8 1.8 0 0 0 1.6-2.8L13.7 3.9a1.9 1.9 0 0 0-3.4 0Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="toast-title">Please check your input</p>
                    <ul class="toast-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="toast-close" data-toast-close aria-label="Dismiss notification">
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4">
                        <path d="M6 6 18 18M18 6 6 18" stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
                    </svg>
                </button>
                <span class="toast-progress toast-progress-error"></span>
            </div>
        @endif
    </div>

    <div class="relative min-h-screen flex flex-col justify-between">

        <!-- ================= NAVBAR ================= -->
        <header class="border-b border-slate-200/80 bg-white/80 backdrop-blur-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 h-24 flex items-center justify-between">

                <!-- Made Header Logo Bigger & Kept Original Layout Structures -->
                <a href="{{ route('home') }}" class="flex items-center gap-4 group">
                    <div class="h-14 w-14 rounded-2xl bg-slate-900 flex items-center justify-center p-1 transition-transform group-hover:scale-[1.02] shadow-sm">
                        <img src="{{ asset('logo/logo-sq-trans.png') }}"
                             alt="logo"
                             class="h-full w-full object-contain">
                    </div>

                    <div>
                        <h1 class="text-xl font-bold tracking-tight text-slate-900">
                            Smart Hiring Assistant
                        </h1>
                        <p class="text-xs font-semibold text-slate-400 mt-0.5 tracking-wide uppercase">
                            AI Powered Recruitment Platform
                        </p>
                    </div>
                </a>

                <!-- Nav Controls -->
                <div class="flex items-center gap-3">
                    @auth
                        <img src="{{ asset('storage/' . Auth::user()->profile_image_path) }}" alt="{{ Auth::user()->name }}" class="h-12 w-12 rounded-full object-cover border border-slate-300 shadow-sm">

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                    class="text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 px-3 py-1.5 rounded-lg transition border border-slate-200 shadow-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-1.5 transition">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl transition shadow-sm">
                            Get Started
                        </a>
                    @endauth
                </div>

            </div>
        </header>

        <!-- ================= MAIN CONTENT CONTAINER ================= -->
        <main class="min-w-full mx-auto flex-grow">

            <!-- Main Work Area Canvas Box -->
            <div class="">
                @yield('content')
            </div>

        </main>

        <!-- ================= CLEAN MINIMALIST FOOTER ================= -->
        <footer class="border-t border-slate-200 bg-white">
            <div class="max-w-7xl mx-auto px-6 py-12">
                
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    
                    <!-- Left: Brand Block with Original Text Details Below Logo -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-4">
                            <div class="h-14 w-14 rounded-2xl bg-slate-900 flex items-center justify-center p-1 shadow-sm">
                                <img src="{{ asset('logo/logo-sq-trans.png') }}" class="h-full w-full object-contain">
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Smart Hiring Assistant</h2>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">AI Powered Recruitment Platform</p>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 max-w-md leading-relaxed font-medium">
                            Simplifying modern recruitment workflows with intelligent automation and streamlined candidate management.
                        </p>
                    </div>

                    <!-- Right: Copyright, Framework details, and Creator Credits -->
                    <div class="text-left md:text-right space-y-1">
                        <p class="text-xs font-semibold text-slate-900">
                            &copy; {{ date('Y') }} Smart Hiring Assistant. All rights reserved.
                        </p>
                        <p class="text-[11px] font-medium text-slate-400">
                            Built with Laravel & Tailwind CSS
                        </p>
                        <p class="text-[11px] font-bold text-indigo-600 pt-1">
                            Designed and Developed by Ayush Sharma
                        </p>
                    </div>

                </div>

            </div>
        </footer>

    </div>

</body>
</html>
