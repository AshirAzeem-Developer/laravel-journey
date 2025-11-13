<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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


        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (trim(strtolower($user->designation)) == 'admin') {
            return redirect()->intended(route('adminDashboard'));
        } else {
            // For regular users, redirect to the home page (or standard dashboard)
            return redirect()->intended(route('website.home'));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user_id = Auth::id();
        $user = DB::table('users')->where('id', $user_id)->first();
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();


        if ($user->designation == 'admin') {
            return redirect('/admin_dashboard/login');
        } else {

            return redirect('/');
        }
    }
    /**
     * Handle the user being authenticated.
     */
}
