<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{

    public function home()
    {
        // Fetch categories and eager-load their related products.
        // This is the optimized query: 2 database queries instead of N+1.
        $data['categories'] = Category::with('products')->get();

        // Pass the categories collection (which includes products) to the view.
        return view('website.index', compact('data'));
    }
}
