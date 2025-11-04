@php
    // --- STATIC DUMMY DATA FOR FRONT-END PRESENTATION ---
    // In a real Laravel application, this data would be passed from a controller.

    // 1. Static Categories (for the dropdown menu)
    $all_categories = [
        ['id' => 10, 'category_name' => 'Electronics'],
        ['id' => 11, 'category_name' => 'Appliances'],
        ['id' => 12, 'category_name' => 'Digital Cameras'],
        ['id' => 13, 'category_name' => 'Cell Phones & Tablets'],
        ['id' => 14, 'category_name' => 'Smart Home'],
        ['id' => 15, 'category_name' => 'Audio & Video'],
        ['id' => 16, 'category_name' => 'Gaming Consoles'],
    ];

    // 2. Static Cart Items (for the dropdown mini-cart)
    // NOTE: In a static page, we simulate 2 items in the cart
    $cart_items = [
        [
            'product_id' => 101,
            'product_name' => 'High-Resolution Monitor',
            'price' => 350.0,
            'quantity' => 1,
            'image' => 'products/product-1.jpg', // Dummy image path
        ],
        [
            'product_id' => 205,
            'product_name' => 'Wireless Mechanical Keyboard',
            'price' => 120.0,
            'quantity' => 2,
            'image' => 'products/product-2.jpg',
        ],
    ];

    // 3. Static Cart Totals and User Status
    $cart_total = array_sum(
        array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart_items),
    );
    $cart_count = array_sum(array_column($cart_items, 'quantity')); // Total quantity of items

    // Simulation for user status
    $user_is_logged_in = true; // Set to false to see the Sign in/Sign up link
    $user_designation = 'user'; // 'user' or 'admin' or null
    $user_name = 'John Doe';
@endphp

@vite(['resources/css/app.css', 'resources/js/app.js'])



<header class="header header-intro-clearance header-4 border-b border-gray-200">
    {{-- Top Header Section with Tailwind classes --}}
    <div class="header-top bg-gray-100 py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="header-left">
                <a href="tel:#" class="text-xl text-gray-600 hover:text-blue-500 transition duration-300">
                    <i class="icon-phone mr-1"></i>Call: **+0123 456 789**
                </a>
            </div>

            <div class="header-right">
                <ul class="top-menu flex space-x-4">
                    <li class="relative">
                        <a href="#"
                            class="text-sm text-gray-600 hover:text-blue-500 transition duration-300">Links</a>
                        {{-- Dropdowns are kept simple, using existing HTML structure/classes --}}
                        <ul>
                            <li>
                                <div class="header-dropdown">
                                    <a href="#">USD</a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="#">Eur</a></li>
                                            <li><a href="#">Usd</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="header-dropdown">
                                    <a href="#">English</a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="#">English</a></li>
                                            <li><a href="#">French</a></li>
                                            <li><a href="#">Spanish</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>

                            {{-- Session Check Logic (Converted to Blade @if with static variables) --}}
                            {{-- @if (!$user_is_logged_in || $user_designation !== 'user') --}}
                            @if (true)
                                <li><a href="#signin-modal" data-toggle="modal"
                                        class="text-xl font-medium hover:text-red-500">Sign in / Sign up</a></li>
                            @endif

                            {{-- @if ($user_is_logged_in && ($user_designation === 'user' || $user_designation === 'admin')) --}}
                            @if (false)
                                <li class="relative">
                                    <div class="header-dropdown">
                                        <a href="#">Hello,
                                            {{ $user_designation === 'user' ? 'User' : 'Admin' }}</a>
                                        <div class="header-menu">
                                            <ul>
                                                {{-- Removed 'dashboard.php' link since it was commented --}}
                                                @if ($user_designation === 'user')
                                                    <li><a href="orders.php">Orders</a></li>
                                                    <li><a href="profile.php">Profile</a></li>
                                                    <li><a href="logout.php">Logout</a></li>
                                                @elseif ($user_designation === 'admin')
                                                    <li><a href="admin/dashboard.php">Admin Dashboard</a></li>
                                                    <li><a href="logout.php">Logout</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Middle Header Section with Tailwind classes --}}
    <div class="header-middle py-6">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="header-left flex items-center">
                <button class="mobile-menu-toggler d-lg-none">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="index.php" class="logo">
                    <img src={{ asset('storeAssets/images/demos/demo-4/logo.png') }} alt="Molla Logo" width="105"
                        height="25">
                </a>
            </div>

            <div class="header-center flex-grow mx-12">
                <div class="header-search header-search-extended header-search-visible d-none d-lg-block">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                    <form action="#" method="get">
                        <div
                            class="header-search-wrapper search-wrapper-wide flex items-center border rounded-full overflow-hidden">
                            <label for="q" class="sr-only">Search</label>
                            {{-- Added Tailwind classes to make the search button look better statically --}}
                            <button class="btn btn-primary bg-transparent p-2" type="submit">
                                <i class="icon-search text-lg text-blue-500"></i>
                            </button>
                            <input type="search" class="form-control flex-grow border-none focus:ring-0 px-3 py-2"
                                name="q" id="q" placeholder="Search product ..." required>
                        </div>
                    </form>
                </div>
            </div>

            <div class="header-right flex items-center space-x-6">
                {{-- Compare Dropdown (Static) --}}
                <div class="dropdown compare-dropdown hidden md:block">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static" title="Compare Products">
                        <div class="icon">
                            <i class="icon-random"></i>
                        </div>
                        <p>Compare</p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="compare-products">
                            <li class="compare-product">
                                <a href="#" class="btn-remove" title="Remove Product"><i
                                        class="icon-close"></i></a>
                                <h4 class="compare-product-title"><a href="product.html">Blue Night Dress</a></h4>
                            </li>
                            <li class="compare-product">
                                <a href="#" class="btn-remove" title="Remove Product"><i
                                        class="icon-close"></i></a>
                                <h4 class="compare-product-title"><a href="product.html">White Long Skirt</a></h4>
                            </li>
                        </ul>
                        <div class="compare-actions">
                            <a href="#" class="action-link">Clear All</a>
                            <a href="#" class="btn btn-outline-primary-2"><span>Compare</span><i
                                    class="icon-long-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                {{-- Wishlist (Static) --}}
                <div class="wishlist hidden md:block">
                    <a href="wishlist.html" title="Wishlist">
                        <div class="icon">
                            <i class="icon-heart-o"></i>
                            <span class="wishlist-count badge">3</span>
                        </div>
                        <p>Wishlist</p>
                    </a>
                </div>

                {{-- Cart Dropdown (Uses Static Cart Data) --}}
                <div class="dropdown cart-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" data-display="static">
                        <div class="icon">
                            <i class="icon-shopping-cart"></i>
                            {{-- Blade output for static count --}}
                            <span class="cart-count">5</span>
                        </div>
                        <p>Cart</p>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-cart-products">
                            {{-- Blade @if/@foreach structure to loop over static cart items --}}
                            @if (!empty($cart_items))
                                @foreach ($cart_items as $item)
                                    <div class="product">
                                        <div class="product-cart-details">
                                            <h4 class="product-title">
                                                <a href="product.php?id={{ $item['product_id'] }}">
                                                    {{ htmlspecialchars($item['product_name']) }}
                                                </a>
                                            </h4>
                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">{{ $item['quantity'] }}</span>
                                                x ${{ number_format($item['price'], 2) }}
                                            </span>
                                        </div>
                                        <figure class="product-image-container">
                                            <a href="product.php?id={{ $item['product_id'] }}" class="product-image">
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQCvHwovbRHB9NnFG6PaeXbFZMAczyZ6m9EHQ&s"
                                                    alt="product">
                                            </a>
                                        </figure>
                                        {{-- Note: The `cart.php?remove=` link is kept static for navigation only --}}
                                        <a href="cart.php?remove={{ $item['product_id'] }}" class="btn-remove"
                                            title="Remove Product">
                                            <i class="icon-close"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center p-3 text-gray-500">Your cart is empty</p>
                            @endif
                        </div>

                        {{-- Cart total and action buttons (Uses Static Cart Data) --}}
                        @if (!empty($cart_items))
                            <div class="dropdown-cart-total">
                                <span>Total</span>
                                <span class="cart-total-price">${{ number_format($cart_total, 2) }}</span>
                            </div>

                            <div class="dropdown-cart-action">
                                <a href="cart.php" class="btn btn-primary">View Cart</a>
                                <a href="checkout.php" class="btn btn-outline-primary-2">
                                    <span>Checkout</span><i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Header Section --}}
    <div class="header-bottom sticky-header bg-white shadow-sm border-t border-gray-100">
        <div class="container mx-auto px-4 flex justify-between items-center h-12">
            <div class="header-left">
                {{-- Category Dropdown (Uses Static Category Data) --}}
                <div class="dropdown category-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" data-display="static" title="Browse Categories">
                        Browse Categories <i class="icon-angle-down"></i>
                    </a>

                    <div class="dropdown-menu">
                        <nav class="side-nav">
                            <ul class="menu-vertical sf-arrows">
                                <li class="item-lead"><a href="#">Daily offers</a></li>
                                <li class="item-lead"><a href="#">Gift Ideas</a></li>

                                {{-- Blade loop for static categories --}}
                                @if (!empty($all_categories))
                                    @foreach ($all_categories as $category)
                                        @php $category_link = "category.html?id=" . $category['id']; @endphp
                                        <li>
                                            <a href="{{ $category_link }}">
                                                {{ htmlspecialchars($category['category_name']) }}
                                            </a>
                                        </li>
                                    @endforeach
                                @else
                                    <li><a href="#">No categories found</a></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            {{-- Clearance Message with Tailwind classes --}}
            <div class="header-right text-sm font-medium flex items-center space-x-2">
                <i class="la la-lightbulb-o text-yellow-500 text-lg"></i>
                <p>Clearance<span class="highlight text-red-600 font-bold">&nbsp;Up to 30% Off</span></p>
            </div>
        </div>
    </div>
</header>
