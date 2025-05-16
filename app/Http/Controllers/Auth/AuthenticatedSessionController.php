<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->email;
        $password = $request->password;

        $admin = Admin::where('email', $email)->first();
        if ($admin) {
            if (Hash::check($password, $admin->password)) {
                Auth::guard('admin')->login($admin);
                return redirect()->route('admin.dashboard');
            }
            return back()->withErrors(['password' => 'Password Kamu salah']);
        }

        $mentor = Mentor::where('email', $email)->first();
        if ($mentor) {
            if (Hash::check($password, $mentor->password)) {
                Auth::guard('mentor')->login($mentor);
                return redirect()->route('mentor.dashboard');
            }
            return back()->withErrors(['password' => 'Password Kamu salah']);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {
                auth()->guard('web')->login($user);
                return redirect()->route('dashboard');
            }
            return back()->withErrors(['password' => 'Ups, Ada yang Salah Nih...']);
        }

        return back()->withErrors([
            'email' => 'Ups, Email yang Dimasukin Salah',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
