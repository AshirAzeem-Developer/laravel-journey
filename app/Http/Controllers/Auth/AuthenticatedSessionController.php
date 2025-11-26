<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
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
    public function store(LoginRequest $request): RedirectResponse | JsonResponse
    {

        try {


            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();
            $userDesignation = trim(strtolower($user->designation));
            $isAdminLoginAttempt = $request->has('is_admin_login');

            // 1. Logic for Admin Login Attempt (from Admin Dashboard Login form)
            if ($isAdminLoginAttempt) {
                if ($userDesignation == 'admin') {
                    // Success: Admin logged in via Admin Form
                    return redirect()->intended(route('adminDashboard'));
                } else {
                    // Failure: Regular user tried to log in via Admin Form
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // Redirect back to admin login with a custom error
                    return  redirect()->back()->with(['error' => 'These credentials do not match our records.']);
                }
            } else {

                if ($userDesignation == 'admin') {
                    // Failure: Admin tried to log in via Website Form
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // Redirect back to website login with a custom error
                    return redirect()->back()->with([
                        'error' => 'Access denied. Administrators must use the dedicated admin login portal.',
                    ]);
                } else {
                    // Success: Regular user logged in via Website Form
                    return redirect()->intended(route('website.home'));
                }
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation exceptions (e.g., incorrect credentials)

            return redirect()->back()->with(['error' => 'These credentials do not match our records.']);
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
