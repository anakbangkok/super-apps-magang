<?php

namespace App\Http\Controllers\Mentor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginMentorRequest; 
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('mentor.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginMentorRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('mentor.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('mentor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/mentor/login');
    }
}
