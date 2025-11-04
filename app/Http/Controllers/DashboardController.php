<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Method for the main dashboard screen (Summary)
    public function index(): View | RedirectResponse
    {
        $summaryData = [
            'totalUsers' => User::count(),
            'totalSessions' => DB::table('sessions')->count(),
            'totalJobs' => DB::table('jobs')->count(), // Pending jobs/queue data
            'lastLoggedInUser' => User::latest('updated_at')->first()->name ?? 'N/A', // Example of accessing user data
        ];

        $user = Auth::user();

        if ($user && $user->designation === 'admin') {
            return view('dashboard.main', [
                'summaryData' => $summaryData,
                'viewPartial' => 'summary_stats', // We'll create this partial
                'activeSection' => 'summary_stats',
            ]);
        }

        // If not admin, show a different view or data
        return redirect()->route('website.home')->with('error', 'Access denied.');
    }

    public function edit(): View
    {
        $tickets = User::all();
        $users = User::all();

        return view('dashboard.main', compact('users', 'tickets'));
    }

    // Method to show different sections (e.g., 'users', 'sessions')
    public function showSection(string $section): View
    {
        $data = [];
        $viewPartial = '';


        switch ($section) {
            case 'users':
                // Fetch all users
                $data['users'] = User::all();
                $viewPartial = 'users_table';
                break;
            case 'sessions':
                // Fetch active sessions data
                $data['sessions'] = DB::table('sessions')->orderBy('last_activity', 'desc')->limit(10)->get();
                $viewPartial = 'sessions_list';
                break;
            case 'jobs':
                // Fetch recent failed jobs
                $data['failedJobs'] = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->limit(10)->get();
                $viewPartial = 'failed_jobs_table';
                break;
            default:
                // Handle 404 for unknown sections
                abort(404);
        }

        return view('dashboard.main', [
            'contentData' => $data,
            'activeSection' => $section,
            'viewPartial' => $viewPartial,
        ]);
    }

    public function show(): View
    {
        // Logic to show profile
        return view('auth.login');
    }
}
