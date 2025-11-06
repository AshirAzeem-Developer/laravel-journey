@php
    // --- STATIC CATEGORIES (Keep these if you are passing them this way) ---
    $all_categories = [
        ['id' => 10, 'category_name' => 'Electronics'],
        ['id' => 11, 'category_name' => 'Appliances'],
        ['id' => 12, 'category_name' => 'Digital Cameras'],
        ['id' => 13, 'category_name' => 'Cell Phones & Tablets'],
        ['id' => 14, 'category_name' => 'Smart Home'],
        ['id' => 15, 'category_name' => 'Audio & Video'],
        ['id' => 16, 'category_name' => 'Gaming Consoles'],
    ];

    // --- REMOVED STATIC CART DATA ---
    // The data below is now dynamically passed via the View Composer:
    // $actualCartItems, $actualCartCount, $actualCartTotal

@endphp

@vite(['resources/css/app.css', 'resources/js/app.js'])

<header class="header header-intro-clearance header-4 border-b border-gray-200">
    {{-- Top Header Section --}}
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
                        <ul>
                            {{-- Other static links... --}}
                            @guest
                                <li><a href="#signin-modal" data-toggle="modal"
                                        class="text-xl font-medium hover:text-red-500">Sign in / Sign up</a></li>
                            @endguest

                            @auth
                                <li class="relative">
                                    <div class="header-dropdown">
                                        <a href="#">Hello, {{ Auth::user()->name }}</a>
                                        <div class="header-menu">
                                            <ul>
                                                <li><a href="#">Orders</a></li>
                                                <li><a href="#">Profile</a></li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <x-dropdown-link :href="route('logout')"
                                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                                        class="flex items-center text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                        <span class="material-symbols-rounded text-lg mr-3">logout</span>
                                                    </x-dropdown-link>
                                                </form>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Middle Header Section --}}
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

            <div class="header-right flex items-center space-x-6">
                {{-- Wishlist (Static) --}}
                {{-- <div class="wishlist hidden md:block">
                    <a href="wishlist.html" title="Wishlist">
                        <div class="icon">
                            <i class="icon-heart-o"></i>
                            <span class="wishlist-count badge">3</span>
                        </div>
                        <p>Wishlist</p>
                    </a>
                </div> --}}

                {{-- Cart Dropdown (NOW USES ACTUAL CART DATA) --}}
                <div class="dropdown cart-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static">
                        <div class="icon">
                            <i class="icon-shopping-cart"></i>
                            {{-- DYNAMIC CART COUNT --}}
                            <span class="cart-count">{{ $actualCartCount ?? 0 }}</span>
                        </div>
                        <p>Cart</p>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-cart-products">
                            {{-- DYNAMIC CART ITEMS LOOP --}}
                            @if (!empty($actualCartItems) && $actualCartItems->count() > 0)
                                @foreach ($actualCartItems as $item)
                                    <div class="product">
                                        <div class="product-cart-details">
                                            <h4 class="product-title">
                                                <a href="product.php?id={{ $item->product->id }}">
                                                    {{ htmlspecialchars($item->product->product_name ?? 'N/A') }}
                                                </a>
                                            </h4>
                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">{{ $item->quantity }}</span>
                                                x ${{ number_format($item->product->price ?? 0, 2) }}
                                            </span>
                                        </div>
                                        <figure class="product-image-container">
                                            <a href="product.php?id={{ $item->product->id }}" class="product-image">
                                                {{-- Assuming the Product model stores image attachment --}}
                                                @if (!empty($item->product->attachments[0]))
                                                    <img src="{{ asset('storage/' . $item->product->attachments[0]) }}"
                                                        alt="product image">
                                                @else
                                                    <img src="https://via.placeholder.com/60" alt="product image">
                                                @endif
                                            </a>
                                        </figure>
                                        {{-- ⚠️ THE REMOVAL FORM ⚠️ --}}
                                        <form action="{{ route('cart.destroy', ['cartId' => $item->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE') {{-- Method spoofing for DELETE request --}}

                                            <button type="submit" class="btn-remove" title="Remove Product"
                                                style="border: none; background: none; cursor: pointer; padding: 0;">
                                                <i class="icon-close"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center p-3 text-gray-500">Your cart is empty</p>
                            @endif
                        </div>

                        {{-- DYNAMIC CART TOTAL --}}
                        @if (!empty($actualCartItems) && $actualCartItems->count() > 0)
                            <div class="dropdown-cart-total">
                                <span>Total</span>
                                <span class="cart-total-price">${{ number_format($actualCartTotal ?? 0, 2) }}</span>
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

    {{-- Bottom Header Section (Categories & Clearance) --}}
    <div class="header-bottom sticky-header bg-white shadow-sm border-t border-gray-100">
        <div class="container mx-auto px-4 flex justify-between items-center h-12">
            <div class="header-left">
                {{-- Category Dropdown (Static Categories) --}}
                <div class="dropdown category-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static" title="Browse Categories">
                        Browse Categories <i class="icon-angle-down"></i>
                    </a>

                    <div class="dropdown-menu">
                        <nav class="side-nav">
                            <ul class="menu-vertical sf-arrows">
                                <li class="item-lead"><a href="#">Daily offers</a></li>
                                <li class="item-lead"><a href="#">Gift Ideas</a></li>

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

            {{-- Clearance Message --}}
            <div class="header-right text-sm font-medium flex items-center space-x-2">
                <i class="la la-lightbulb-o text-yellow-500 text-lg"></i>
                <p>Clearance<span class="highlight text-red-600 font-bold">&nbsp;Up to 30% Off</span></p>
            </div>
        </div>
    </div>
</header>
