<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\JsonResponse;
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
                $data['users'] = User::orderBy('id', 'asc')->paginate(10);
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


    // function for orders page
    public function getOrders(Request $request): View
    {
        // Start the base query
        $query = DB::table('tbl_orders')
            ->leftJoin('users', 'tbl_orders.user_id', '=', 'users.id')
            ->select('tbl_orders.*', 'users.name as user_name');

        // --- Filtering Logic ---
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tbl_orders.order_number', 'LIKE', "%{$search}%")
                    ->orWhere('tbl_orders.transaction_id', 'LIKE', "%{$search}%")
                    ->orWhere('users.name', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('tbl_orders.payment_status', $request->payment_status);
        }

        if ($request->filled('order_status')) {
            $query->where('tbl_orders.order_status', $request->order_status);
        }
        // --- End Filtering Logic ---

        // 1. Get the TOTAL filtered dataset for statistics (before pagination)
        $allFilteredOrders = (clone $query)->get();

        // 2. Calculate the correct statistics
        $totalOrdersCount = $allFilteredOrders->count();
        $pendingOrdersCount = $allFilteredOrders->where('payment_status', 'pending')->count();
        $deliveredOrdersCount = $allFilteredOrders->where('order_status', 'delivered')->count();
        $totalRevenue = $allFilteredOrders->sum('total_amount');

        // 3. Apply Pagination to the main query
        $orders = $query->latest('tbl_orders.created_at')->paginate(10)->withQueryString();

        // 4. Pass the calculated statistics to the view
        return view('orders.index', [
            'orders' => $orders,
            'activeSection' => 'orders',
            // Statistics variables:
            'totalOrdersCount' => $totalOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,
            'deliveredOrdersCount' => $deliveredOrdersCount,
            'totalRevenue' => $totalRevenue,
            // Request variables for filters/search:
            'search' => $request->search ?? '',
            'payment_status' => $request->payment_status ?? '',
            'order_status' => $request->order_status ?? '',
        ]);
    }
    public function getOrderDetails(Order $order): JsonResponse
    {
        // Load user relationship
        $order->load('user');
        return response()->json($order);
    }


    public function getCategories(): View
    {
        $categories = DB::table('tbl_categories')->paginate(10);

        return view('categories.index', [
            'categories' => $categories,
        ]);
    }
}
