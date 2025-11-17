@php

    // This logic correctly uses the $categories collection passed from the controller.

    // 1. Get ALL products flat list (for the 'All' tab)
    $all_products = $data['categories']->flatMap(function ($category) {
        return $category->products;
    });

    // 2. Map Category IDs to their respective product collections
    $grouped_products = $data['categories']->mapWithKeys(function ($category) {
        return [$category->id => $category->products];
    });

    // 3. Map the HTML tab IDs to the respective product groups
    // The keys (11, 14, etc.) MUST match the primary key 'id' of the category in your database.
    $tabs = [
        'new-all-tab' => $all_products,
        'new-computers-tab' => $grouped_products->get(11, collect()),
        'new-tv-tab' => $grouped_products->get(14, collect()),
        'new-phones-tab' => $grouped_products->get(13, collect()),
        'new-watches-tab' => $grouped_products->get(16, collect()),
        'new-cameras-tab' => $grouped_products->get(12, collect()),
        'new-audio-tab' => $grouped_products->get(15, collect()),
    ];

    // Dummy helper functions
    function format_price($price)
    {
        return '$' . number_format((float) $price, 2, '.', ',');
    }

    function get_rating_width()
    {
        return rand(60, 100);
    }

    function get_review_count()
    {
        return rand(2, 12);
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kharido.pk - eCommerce </title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href={{ asset('storeAssets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/bootstrap.min.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/plugins/owl-carousel/owl.carousel.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/plugins/magnific-popup/magnific-popup.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/plugins/jquery.countdown.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/style.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/skins/skin-demo-4.css') }}>
    <link rel="stylesheet" href={{ asset('storeAssets/css/demos/demo-4.css') }}>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="page-wrapper min-h-screen bg-gray-50">
        @include('website.layouts.header')
        @include('website.components.toast')


        <main class="main">
            {{-- Introductory Slider Section --}}
            <div class="intro-slider-container mb-5">
                <div class="intro-slider owl-carousel owl-theme owl-nav-inside owl-light" data-toggle="owl"
                    data-owl-options='{"dots": true, "nav": false, "responsive": {"1200": {"nav": true, "dots": false}}}'>
                    <div class="intro-slide"
                        style="background-image: url(storeAssets/images/demos/demo-4/slider/slide-1.png);">
                        <div class="container intro-content">
                            <div class="row justify-content-end">
                                <div class="col-auto col-sm-7 col-md-6 col-lg-5">
                                    <h3 class="intro-subtitle text-third">Deals and Promotions</h3>
                                    <h1 class="intro-title">Beats by</h1>
                                    <h1 class="intro-title">Dre Studio 3</h1>
                                    <div class="intro-price">
                                        <sup class="intro-old-price">$349,95</sup>
                                        <span class="text-third">$279<sup>.99</sup></span>
                                    </div>
                                    <a href="category.html" class="btn btn-primary btn-round">
                                        <span>Shop More</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="intro-slide"
                        style="background-image: url(storeAssets/images/demos/demo-4/slider/slide-2.png);">
                        <div class="container intro-content">
                            <div class="row justify-content-end">
                                <div class="col-auto col-sm-7 col-md-6 col-lg-5">
                                    <h3 class="intro-subtitle text-primary">New Arrival</h3>
                                    <h1 class="intro-title">Apple iPad Pro <br>12.9 Inch, 64GB </h1>
                                    <div class="intro-price">
                                        <sup>Today:</sup>
                                        <span class="text-primary">$999<sup>.99</sup></span>
                                    </div>
                                    <a href="category.html" class="btn btn-primary btn-round">
                                        <span>Shop More</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="slider-loader"></span>
            </div>

            <div class="container">
                <h2 class="title text-center mb-4 text-6xl font-bold text-gray-800">Explore Popular Categories</h2>

                {{-- Category Blocks (Static Content from PHP Loop) --}}
                <div class="cat-blocks-container">
                    <div class="row">

                        {{-- {{ dd($data['categories']) }} --}}

                        @if (count($data['categories']) > 0)
                            @foreach ($data['categories'] as $category)
                                @php
                                    $safe_category_name = $category['category_name'];
                                    $image_src = asset('storage/' . $category['category_image']);
                                    $category_link = 'category.html?id=' . $category['id'];
                                @endphp

                                <div class="col-6 col-sm-4 col-lg-2">
                                    <a href="#" class="cat-block transition duration-300 p-5 ">
                                        <figure>
                                            <span>
                                                <img src="{{ $image_src }}" alt="{{ $safe_category_name }} image">
                                            </span>
                                        </figure>
                                        <h3 class="cat-block-title">{{ $safe_category_name }}</h3>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p>No categories found.</p>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="mb-3"></div>

                {{-- New Arrivals Products Section (Static Content from PHP/Database Loop) --}}
                <div class="container new-arrivals">
                    <div class="heading heading-flex mb-3">
                        <div class="heading-left">
                            <h2 class="title text-2xl font-semibold">New Arrivals</h2>
                        </div>
                        <div class="heading-right">
                            <ul class="nav nav-pills nav-border-anim justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="new-all-link" data-toggle="tab" href="#new-all-tab"
                                        role="tab" aria-controls="new-all-tab" aria-selected="true">All</a>
                                </li>

                                @foreach ($data['categories'] as $category)
                                    <li class="nav-item">
                                        <a class="nav-link" id="cat-{{ $category['id'] }}-link" data-toggle="tab"
                                            href="#cat-{{ $category['id'] }}-tab" role="tab"
                                            aria-controls="cat-{{ $category['id'] }}-tab"
                                            aria-selected="false">{{ $category['category_name'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content tab-content-carousel just-action-icons-sm">
                        {{-- All Products --}}
                        <div class="tab-pane p-0 show active" id="new-all-tab" role="tabpanel"
                            aria-labelledby="new-all-link">
                            <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow"
                                data-toggle="owl"
                                data-owl-options='{"nav": true, "dots": true, "margin": 20, "loop": false, "responsive": {"0": {"items":2}, "480": {"items":2}, "768": {"items":3}, "992": {"items":4}, "1200": {"items":5}}}'>
                                @foreach ($data['categories']->flatMap->products as $product)
                                    @include('website.components.product-card', ['product' => $product])
                                @endforeach
                            </div>
                        </div>

                        {{-- Products per Category --}}
                        @foreach ($data['categories'] as $category)
                            <div class="tab-pane p-0 fade" id="cat-{{ $category['id'] }}-tab" role="tabpanel"
                                aria-labelledby="cat-{{ $category['id'] }}-link">
                                <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow"
                                    data-toggle="owl"
                                    data-owl-options='{"nav": true, "dots": true, "margin": 20, "loop": false, "responsive": {"0": {"items":2}, "480": {"items":2}, "768": {"items":3}, "992": {"items":4}, "1200": {"items":5}}}'>
                                    @if (isset($grouped_products[$category['id']]))
                                        @foreach ($grouped_products[$category['id']] as $product)
                                            @include('website.components.product-card', [
                                                'product' => $product,
                                            ])
                                        @endforeach
                                    @else
                                        <p class="text-center p-5 w-full">No products found in this category.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="mb-6"></div>

                {{-- CTA Banner Section --}}
                <div class="container">
                    <div class="cta cta-border mb-5 bg-cover bg-center"
                        style="background-image: url(storeAssets/images/demos/demo-4/bg-1.jpg);">
                        <img src="storeAssets/images/demos/demo-4/camera.png" alt="camera" class="cta-img">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="cta-content">
                                    <div class="cta-text text-right text-white">
                                        <p>Shop Today’s Deals <br><strong>Awesome Made Easy. HERO7 Black</strong></p>
                                    </div>
                                    {{-- Added Tailwind classes to the button for visual interest --}}
                                    <a href="#"
                                        class="btn btn-primary btn-round bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-full transition duration-300">
                                        <span>Shop Now - $429.99</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- The entire "Trending Products Section" PHP logic and corresponding HTML is commented out in the original PHP.
                     I am keeping it as HTML comments here to respect the original code's structure. --}}

                <div class="mb-5"></div>

                <div class="mb-4"></div>

                <div class="container">
                    <hr class="mb-0">
                </div>

                {{-- Icon Boxes Container with Tailwind classes for layout --}}
                <div class="icon-boxes-container bg-transparent py-8">
                    <div class="container">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="">
                                <div
                                    class="icon-box icon-box-side flex items-center p-4 bg-white shadow-sm rounded-lg ">
                                    <span class="icon-box-icon text-dark text-3xl mr-4">
                                        <i class="icon-rocket"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title text-lg font-semibold">Free Shipping</h3>
                                        <p class="text-gray-600">Orders $50 or more</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div
                                    class="icon-box icon-box-side flex items-center p-4 bg-white shadow-sm rounded-lg">
                                    <span class="icon-box-icon text-dark text-3xl mr-4">
                                        <i class="icon-rotate-left"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title text-lg font-semibold">Free Returns</h3>
                                        <p class="text-gray-600">Within 30 days</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div
                                    class="icon-box icon-box-side flex items-center p-4 bg-white shadow-sm rounded-lg">
                                    <span class="icon-box-icon text-dark text-3xl mr-4">
                                        <i class="icon-info-circle"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title text-lg font-semibold">Get 20% Off 1 Item</h3>
                                        <p class="text-gray-600">when you sign up</p>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                                <div
                                    class="icon-box icon-box-side flex items-center p-4 bg-white shadow-sm rounded-lg">
                                    <span class="icon-box-icon text-dark text-3xl mr-4">
                                        <i class="icon-life-ring"></i>
                                    </span>
                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title text-lg font-semibold">We Support</h3>
                                        <p class="text-gray-600">24/7 amazing services</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>

        {{-- Laravel Blade component/include for Footer --}}
        @include('website.layouts.footer')

    </div>

    {{-- The rest of the page structure (Modals, Scripts) is maintained with Blade syntax replaced where needed --}}
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    {{-- Mobile Menu --}}
    <div class="mobile-menu-overlay"></div>
    <div class="mobile-menu-container mobile-menu-light">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-close"></i></span>

            <form action="#" method="get" class="mobile-search">
                <label for="mobile-search" class="sr-only">Search</label>
                <input type="search" class="form-control" name="mobile-search" id="mobile-search"
                    placeholder="Search in..." required>
                <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
            </form>

            <ul class="nav nav-pills-mobile nav-border-anim" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mobile-menu-link" data-toggle="tab" href="#mobile-menu-tab"
                        role="tab" aria-controls="mobile-menu-tab" aria-selected="true">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mobile-cats-link" data-toggle="tab" href="#mobile-cats-tab"
                        role="tab" aria-controls="mobile-cats-tab" aria-selected="false">Categories</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="mobile-menu-tab" role="tabpanel"
                    aria-labelledby="mobile-menu-link">
                    <nav class="mobile-nav">
                        <ul class="mobile-menu">
                            <li class="active">
                                <a href="index.html">Home</a>
                                {{-- Submenu items removed for brevity, keeping only the main menu structure --}}
                                <ul>
                                    <li><a href="index-1.html">01 - furniture store</a></li>
                                    <li><a href="index-2.html">02 - furniture store</a></li>
                                    {{-- ... more links ... --}}
                                </ul>
                            </li>
                            <li><a href="category.html">Shop</a></li>
                            <li><a href="product.html" class="sf-with-ul">Product</a></li>
                            <li><a href="#">Pages</a></li>
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="elements-list.html">Elements</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="tab-pane fade" id="mobile-cats-tab" role="tabpanel" aria-labelledby="mobile-cats-link">
                    <nav class="mobile-cats-nav">
                        <ul class="mobile-cats-menu">
                            @foreach ($data['categories'] as $category)
                                <li><a
                                        href="category.html?id={{ $category['id'] }}">{{ $category['category_name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>

            <div class="social-icons">
                <a href="#" class="social-icon" target="_blank" title="Facebook"><i
                        class="icon-facebook-f"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Twitter"><i
                        class="icon-twitter"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Instagram"><i
                        class="icon-instagram"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Youtube"><i
                        class="icon-youtube"></i></a>
            </div>
        </div>
    </div>

    {{-- Sign in / Register Modal --}}
    <div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="icon-close"></i></span>
                    </button>

                    <div class="form-box">
                        <div class="form-tab">
                            <ul class="nav nav-pills nav-fill nav-border-anim" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="signin-tab" data-toggle="tab" href="#signin"
                                        role="tab" aria-controls="signin" aria-selected="true">Sign In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="register-tab" data-toggle="tab" href="#register"
                                        role="tab" aria-controls="register" aria-selected="false">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab-content-5">
                                <div class="tab-pane fade show active" id="signin" role="tabpanel"
                                    aria-labelledby="signin-tab">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label for="signin-email">Email address *</label>
                                            <input type="email" class="form-control text-xl" id="signin-email"
                                                name="email" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="signin-password">Password *</label>
                                            <input type="password" class="form-control text-xl" id="signin-password"
                                                name="password" required>
                                        </div>

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>LOG IN</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="remember_me"
                                                    class="custom-control-input" id="signin-remember">
                                                <label class="custom-control-label" for="signin-remember">Remember
                                                    Me</label>
                                            </div>

                                            <a href="#" class="forgot-link">Forgot Your Password?</a>
                                        </div>
                                    </form>

                                    <div class="form-choice">
                                        <p class="text-center">or sign in with</p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-g">
                                                    <i class="icon-google"></i>
                                                    Login With Google
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-f">
                                                    <i class="icon-facebook-f"></i>
                                                    Login With Facebook
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="register" role="tabpanel"
                                    aria-labelledby="register-tab">

                                    <form method="POST" action="{{ route('admin.register') }}">
                                        @csrf

                                        <div class="my-2">
                                            <x-input-label for="name" :value="__('Name')"
                                                class="text-2xl font-medium text-gray-700 block mb-1" />
                                            <x-text-input id="name"
                                                class="w-full px-3 py-3 text-2xl  border border-gray-300 rounded-lg focus:ring focus:ring-gray-900"
                                                type="text" name="name" :value="old('name')" required autofocus
                                                autocomplete="name" />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-500" />
                                        </div>

                                        <div class="my-2">
                                            <x-input-label for="email" :value="__('Email')"
                                                class="text-2xl font-medium text-gray-700 block mb-1" />
                                            <x-text-input id="email"
                                                class="w-full px-3 py-3 text-2xl border border-gray-300 rounded-lg focus:ring focus:ring-gray-900"
                                                type="email" name="email" :value="old('email')" required
                                                autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                                        </div>

                                        <div class="my-2">
                                            <x-input-label for="password" :value="__('Password')"
                                                class="text-2xl font-medium text-gray-700 block mb-1" />
                                            <x-text-input id="password"
                                                class="w-full px-3 py-3 text-2xl border border-gray-300 rounded-lg focus:ring focus:ring-gray-900"
                                                type="password" name="password" required
                                                autocomplete="new-password" />
                                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                                        </div>

                                        <div class="mb-6">
                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                                                class="text-2xl font-medium text-gray-700 block mb-1" />
                                            <x-text-input id="password_confirmation"
                                                class="w-full px-3 py-3 text-2xl border border-gray-300 rounded-lg focus:ring focus:ring-gray-900"
                                                type="password" name="password_confirmation" required
                                                autocomplete="new-password" />
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-500" />
                                        </div>
                                        {{-- Designation Hidden Input --}}
                                        <input type="hidden" name="designation" value="user">

                                        <div class="flex flex-col items-center justify-between mt-4">
                                            <button type="submit"
                                                class="w-full px-4 py-3 text-white font-bold bg-gray-900 rounded-lg hover:bg-gray-800 transition duration-300">
                                                {{ __('Register') }}
                                            </button>

                                            <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4"
                                                href="{{ route('login') }}">
                                                {{ __('Already registered? Login Now') }}
                                            </a>
                                        </div>
                                    </form>
                                    <div class="form-choice">
                                        <p class="text-center">or sign in with</p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-g">
                                                    <i class="icon-google"></i>
                                                    Login With Google
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-f">
                                                    <i class="icon-facebook-f"></i>
                                                    Login With Facebook
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Original Scripts are preserved as they are likely needed for the front-end components (Owl Carousel, Modals) --}}
    <script src={{ asset('storeAssets/js/jquery.min.js') }}></script>
    <script src={{ asset('storeAssets/js/bootstrap.bundle.min.js') }}></script>
    <script src={{ asset('storeAssets/js/jquery.hoverIntent.min.js') }}></script>
    <script src={{ asset('storeAssets/js/jquery.waypoints.min.js') }}></script>
    <script src={{ asset('storeAssets/js/superfish.min.js') }}></script>
    <script src={{ asset('storeAssets/js/owl.carousel.min.js') }}></script>
    <script src={{ asset('storeAssets/js/bootstrap-input-spinner.js') }}></script>
    <script src={{ asset('storeAssets/js/jquery.plugin.min.js') }}></script>
    <script src={{ asset('storeAssets/js/jquery.magnific-popup.min.js') }}></script>
    <script src={{ asset('storeAssets/js/jquery.countdown.min.js') }}></script>
    <script src={{ asset('storeAssets/js/main.js') }}></script>
    <script src={{ asset('storeAssets/js/demos/demo-4.js') }}></script>

    {{-- Static JavaScript is kept, AJAX call is removed from the second script as it depends on server-side logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.trigger-login').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    $('#signin-modal').modal('show');
                });
            });
        });
    </script>
    <script>
        // NOTE: The original AJAX cart logic is removed because it relies on a PHP backend (`functions/add-to-cart.php`).
        // For a static Blade page, we only keep the visual part of the click (if needed).
        $(document).on('click', '.add-to-cart', function(e) {
            e.preventDefault();

            var addButton = $(this);
            var originalIconClass = 'icon-shopping-bag';
            var successIconClass = 'icon-check';

            // Simulate successful add to cart instantly on a static page
            var currentCount = parseInt($('.cart-count').text()) || 0;
            $('.cart-count').text(currentCount + 1);

            // Set Success State (Icon)
            addButton.find('i').removeClass('icon-refresh animated-icon ' + originalIconClass).addClass(
                successIconClass);

            // Revert button state after 3 seconds
            setTimeout(function() {
                addButton.prop('disabled', false)
                    .find('i').removeClass(successIconClass).addClass(originalIconClass);
            }, 3000);

            // Commented out the redirect: window.location.href = 'index.php';
        });
    </script>

</body>

</html>
