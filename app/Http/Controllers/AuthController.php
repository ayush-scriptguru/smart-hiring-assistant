<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route('dashboard'))
            ->with('status', 'Welcome back to Smart Hiring Assistant.');
    }

    public function showRegister(): View
    {
        return view('auth.register', [
            'roles' => UserRole::registrationOptions(),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', Rule::in(array_keys(UserRole::registrationOptions()))],
            'headline' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255', 'required_if:role,'.UserRole::Recruiter->value],
            'skills' => ['nullable', 'string', 'max:1500'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:40'],
            'resume_summary' => ['nullable', 'string', 'max:2500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image_path'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        $user = User::query()->create(Arr::except($validated, ['profile_image']));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Your account is ready. Start exploring the hiring workspace.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'You have been logged out.');
    }
}
