<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // $key =Str::lower($request->input('email')). '|' . $request->ip();
        // if (RateLimiter::tooManyAttempts($key, 5)){
        //     $seconds = RateLimiter::availableIn($key);
        //     return back()->withErrors([
        //         'email' => 'Terlalu banyak percobaan login dalam' .$seconds. 'detik'
        //     ]);
        // }
        // RateLimiter::hit($key, 60);

        $request->authenticate();
        // RateLimiter::clear($key);
        $request->session()->regenerate();

        LogActivity::log('login', 'User login', 'User', Auth::id());

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        LogActivity::log('logout', 'User logout', 'User', Auth::id());

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
