<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kahrido.pk - eCommerce </title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - eCommerce">
    <meta name="author" content="p-themes">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storeAssets/images/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('storeAssets/images/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('storeAssets/images/icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('storeAssets/images/icons/site.html') }}">
    <link rel="shortcut icon" href="{{ asset('storeAssets/images/icons/favicon.ico') }}">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="{{ asset('storeAssets/images/icons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="page-wrapper">

        @include('website.layouts.header')
        @include('website.components.toast')

        <main class="main">
            <div class="page-header text-center"
                style="background-image: url('{{ asset('storeAssets/images/page-header-bg.jpg') }}')">
                <div class="container">
                    <h1 class="page-title">Shopping Cart<span>Shop</span></h1>
                </div>
            </div>
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                    </ol>
                </div>
            </nav>
            <div class="page-content">
                <div class="cart">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-9">
                                <table class="table table-cart table-mobile">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($cartItems as $item)
                                            @php
                                                // Access related Product safely
                                                $product = $item->product ?? null;

                                                // Product details
                                                $product_name = $product['product_name'] ?? 'Missing Product';
                                                $price = $product['price'] ?? 0;
                                                $subtotal = $price * ($item['quantity'] ?? 1);

                                                // Handle image decoding
                                                $attachments = $product['attachments'] ?? null;

                                                if (is_string($attachments)) {
                                                    $attachments = json_decode($attachments, true);
                                                }

                                                // Get first image if available
                                                $imagePath =
                                                    !empty($attachments) && isset($attachments[0])
                                                        ? asset('storage/' . $attachments[0])
                                                        : asset('storeAssets/images/placeholder.jpg');
                                            @endphp

                                            <tr>
                                                <td class="product-col">
                                                    <div class="product">
                                                        <figure class="product-media">
                                                            <a href="#">
                                                                <img src="{{ $imagePath }}"
                                                                    alt="{{ $product_name }}"
                                                                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px;">
                                                            </a>
                                                        </figure>
                                                        <h3 class="product-title">{{ $product_name }}</h3>
                                                    </div>
                                                </td>

                                                <td class="price-col">$ {{ number_format($price) }}</td>

                                                <td class="quantity-col py-4">
                                                    <div class="flex items-center space-x-1">
                                                        {{-- Decrease Quantity --}}
                                                        <form
                                                            action="{{ route('cart.update', ['cartId' => $item->id]) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="action" value="decrement">
                                                            <button type="submit"
                                                                class="px-3 py-2 text-sm font-medium text-white bg-red-500 rounded-l-lg hover:bg-red-600 transition disabled:opacity-50"
                                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                                title="Decrease Quantity">
                                                                <i class="fas fa-minus w-3 h-3"></i>
                                                            </button>
                                                        </form>

                                                        {{-- Current Quantity --}}
                                                        <div
                                                            class="px-3 py-2 text-center text-xl font-bold bg-gray-50 w-12">
                                                            {{ $item['quantity'] }}
                                                        </div>

                                                        {{-- Increase Quantity --}}
                                                        <form
                                                            action="{{ route('cart.update', ['cartId' => $item->id]) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="action" value="increment">
                                                            <button type="submit"
                                                                class="px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-r-lg hover:bg-green-600 transition"
                                                                title="Increase Quantity">
                                                                <i class="fas fa-plus w-3 h-3"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>

                                                <td class="total-col">$ {{ number_format($subtotal) }}</td>

                                                <td class="remove-col">
                                                    <form
                                                        action="{{ route('cart.destroy', ['cartId' => $item->id]) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-800 transition"
                                                            title="Remove Item">
                                                            <i class="fas fa-trash-alt w-4 h-4"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Your cart is empty.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>


                                </table>
                                <div class="cart-bottom">
                                    <div class="cart-discount">
                                        {{-- In Laravel, the form action would be a route for applying a coupon --}}
                                        <form action="#" method="POST">
                                            @csrf
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="coupon_code"
                                                    required placeholder="coupon code">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary-2" type="submit"><i
                                                            class="icon-long-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- The action would point to a Laravel route for updating the cart --}}
                                    <a href="#" class="btn btn-outline-dark-2"><span>UPDATE
                                            CART</span><i class="icon-refresh"></i></a>
                                </div>
                            </div>
                            <aside class="col-lg-3">
                                <div class="summary summary-cart">
                                    <h3 class="summary-title">Cart Total</h3>
                                    <table class="table table-summary">
                                        <tbody>
                                            @php
                                                // Calculate Subtotal dynamically from the passed $cartItems
                                                $cartSubtotal = 0;
                                                foreach ($cartItems as $item) {
                                                    $product = $item->product ?? null;
                                                    $price = $product['price'] ?? 0;
                                                    $cartSubtotal += $price * $item['quantity'];
                                                }
                                                // Initial Shipping Cost (default to Free Shipping: $0)
                                                $initialShipping = 0.0;
                                                $finalTotal = $cartSubtotal + $initialShipping;
                                            @endphp

                                            {{-- Subtotal Row --}}
                                            <tr class="summary-subtotal">
                                                <td>Subtotal:</td>
                                                <td>$ <span
                                                        id="cart-subtotal">{{ number_format($cartSubtotal, 2, '.', '') }}</span>
                                                </td>
                                            </tr>

                                            {{-- Shipping Options (Now with values and IDs) --}}
                                            <tr class="summary-shipping-row">
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="free-shipping" name="shipping"
                                                            class="custom-control-input shipping-radio"
                                                            data-cost="0.00" value="0.00" checked>
                                                        <label class="custom-control-label" for="free-shipping">Free
                                                            Shipping</label>
                                                    </div>
                                                </td>
                                                <td class="shipping-cost-display">$ 0.00</td>
                                            </tr>

                                            <tr class="summary-shipping-row">
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="standart-shipping" name="shipping"
                                                            class="custom-control-input shipping-radio"
                                                            data-cost="10.00" value="10.00">
                                                        <label class="custom-control-label"
                                                            for="standart-shipping">Standard:</label>
                                                    </div>
                                                </td>
                                                <td class="shipping-cost-display">$ 10.00</td>
                                            </tr>

                                            <tr class="summary-shipping-row">
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="express-shipping" name="shipping"
                                                            class="custom-control-input shipping-radio"
                                                            data-cost="20.00" value="20.00">
                                                        <label class="custom-control-label"
                                                            for="express-shipping">Express:</label>
                                                    </div>
                                                </td>
                                                <td class="shipping-cost-display">$ 20.00</td>
                                            </tr>

                                            <tr class="summary-shipping-estimate">
                                                <td>Estimate for Your Country<br> <a
                                                        href="{{ url('/dashboard') }}">Change address</a></td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            {{-- Final Total Row --}}
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                {{-- DYNAMICALLY UPDATED FINAL TOTAL --}}
                                                <td>$. <span
                                                        id="cart-final-total">{{ number_format($finalTotal, 2, '.', '') }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a href="{{ route('checkout') }}"
                                        class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO
                                        CHECKOUT</a>
                                </div>
                                <a href="{{ url('/') }}"
                                    class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i
                                        class="icon-refresh"></i></a>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('website.layouts.footer')
    </div><button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

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
                {{-- <div class="tab-pane fade" id="mobile-cats-tab" role="tabpanel" aria-labelledby="mobile-cats-link">
                    <nav class="mobile-cats-nav">
                        <ul class="mobile-cats-menu">
                            @foreach ($categories as $category)
                                <li><a
                                        href="category.html?id={{ $category['id'] }}">{{ $category['category_name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div> --}}
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

    <script src="{{ asset('storeAssets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/bootstrap-input-spinner.js') }}"></script>
    <script src="{{ asset('storeAssets/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingRadios = document.querySelectorAll('.shipping-radio');
            const subtotalElement = document.getElementById('cart-subtotal');
            const finalTotalElement = document.getElementById('cart-final-total');

            if (!subtotalElement || !finalTotalElement) {
                console.error('Cart total elements not found.');
                return;
            }

            // Function to calculate and update the total
            function updateCartTotal() {
                // Get subtotal (remove commas, parse as float)
                let subtotalText = subtotalElement.textContent.replace(/,/g, '');
                let subtotal = parseFloat(subtotalText);

                if (isNaN(subtotal)) {
                    console.error('Subtotal value is invalid.');
                    return;
                }

                // Find the currently selected shipping cost
                let selectedShippingCost = 0;
                shippingRadios.forEach(radio => {
                    if (radio.checked) {
                        // Get the cost from the data-cost attribute
                        selectedShippingCost = parseFloat(radio.getAttribute('data-cost'));
                    }
                });

                // Calculate new total
                let newTotal = subtotal + selectedShippingCost;

                // Update the final total display
                finalTotalElement.textContent = newTotal.toFixed(2);
            }

            // Add event listeners to all shipping radios
            shippingRadios.forEach(radio => {
                radio.addEventListener('change', updateCartTotal);
            });

            // Run once on load to ensure the initial total is correct (though it should be from the PHP)
            updateCartTotal();
        });
    </script>
</body>

</html>
