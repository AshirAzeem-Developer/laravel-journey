<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Revenue By Month Report
     */
    public function revenueByMonth(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $revenueData = DB::table('tbl_orders')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('MONTHNAME(created_at) as month_name'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->whereYear('created_at', $year)
            ->where('payment_status', 'paid')
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->get();


        $availableYears = DB::table('tbl_orders')
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.reports.revenue-by-month', [
            'revenueData' => $revenueData,
            'selectedYear' => $year,
            'availableYears' => $availableYears,
            'activeSection' => 'reports'
        ]);
    }

    /**
     * Revenue By Year Report
     */
    public function revenueByYear()
    {
        $revenueData = DB::table('tbl_orders')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('AVG(total_amount) as avg_order_value')
            )
            ->where('payment_status', 'paid')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return view('dashboard.reports.revenue-by-year', [
            'revenueData' => $revenueData,
            'activeSection' => 'reports'
        ]);
    }

    /**
     * Revenue By Category Report
     */
    public function revenueByCategory(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $revenueData = DB::table('tbl_order_items')
            ->join('tbl_orders', 'tbl_order_items.order_id', '=', 'tbl_orders.id')
            ->join('tbl_products', 'tbl_order_items.product_id', '=', 'tbl_products.id')
            ->join('tbl_categories', 'tbl_products.category_id', '=', 'tbl_categories.id')
            ->select(
                'tbl_categories.id',
                'tbl_categories.category_name',
                DB::raw('SUM(tbl_order_items.subtotal) as total_revenue'),
                DB::raw('SUM(tbl_order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT tbl_orders.id) as total_orders')
            )
            ->where('tbl_orders.payment_status', 'paid')
            ->whereYear('tbl_orders.created_at', $year)
            ->groupBy('tbl_categories.id', 'tbl_categories.category_name')
            ->orderBy('total_revenue', 'desc')
            ->get();


        $availableYears = DB::table('tbl_orders')
            ->selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.reports.revenue-by-category', [
            'revenueData' => $revenueData,
            'selectedYear' => $year,
            'availableYears' => $availableYears,
            'activeSection' => 'reports'
        ]);
    }

    /**
     * Revenue By Category and Year Report
     */
    public function revenueByCategoryYear(Request $request)
    {
        $categoryId = $request->get('category_id', null);


        $categories = DB::table('tbl_categories')
            ->orderBy('category_name')
            ->get();

        $query = DB::table('tbl_order_items')
            ->join('tbl_orders', 'tbl_order_items.order_id', '=', 'tbl_orders.id')
            ->join('tbl_products', 'tbl_order_items.product_id', '=', 'tbl_products.id')
            ->join('tbl_categories', 'tbl_products.category_id', '=', 'tbl_categories.id')
            ->select(
                'tbl_categories.category_name',
                DB::raw('YEAR(tbl_orders.created_at) as year'),
                DB::raw('SUM(tbl_order_items.subtotal) as total_revenue'),
                DB::raw('SUM(tbl_order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT tbl_orders.id) as total_orders')
            )
            ->where('tbl_orders.payment_status', 'paid');

        if ($categoryId) {
            $query->where('tbl_categories.id', $categoryId);
        }

        $revenueData = $query
            ->groupBy('tbl_categories.category_name', 'year')
            ->orderBy('year', 'desc')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('dashboard.reports.revenue-by-category-year', [
            'revenueData' => $revenueData,
            'categories' => $categories,
            'selectedCategoryId' => $categoryId,
            'activeSection' => 'reports'
        ]);
    }
}
