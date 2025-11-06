@php
    // In a real Laravel application, all variables would be passed from the Controller.
    // Example: return view('order_confirmation', compact('message', 'order_id'));

    // We assume these variables are available in the Blade scope.
    $message = $success ?? 'Thank you for your order!';
    $order_id = $order_id ?? 'N/A';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kharido.pk - Order Confirmation</title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Kharido.pk - eCommerce">
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
    <link rel="stylesheet"
        href="{{ asset('storeAssets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/plugins/owl-carousel/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/plugins/magnific-popup/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/plugins/jquery.countdown.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/skins/skin-demo-4.css') }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/demos/demo-4.css') }}">
</head>

<body>
    <div class="page-wrapper">
        {{-- Replaced PHP include with Blade include syntax --}}
        @include('website.layouts.header')

        <main class="main">
            <div class="page-content">
                <div class="container text-center py-5">
                    <h1>🎉 Order Placed!</h1>
                    {{-- Use Blade echo syntax for variables --}}
                    <p class="lead text-success">{{ $message }}</p>
                    <p>Your order reference number is: <strong>#{{ $orderNumber }}</strong></p>
                    <p>You will receive an email confirmation shortly.</p>

                    {{-- Use Laravel URL/Route helpers for links --}}
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            </div>
        </main>

        {{-- Replaced PHP include with Blade include syntax --}}
        @include('website.layouts.footer')
    </div>

    <script src="{{ asset('storeAssets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/bootstrap-input-spinner.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/main.js') }}"></script>
    <script src="{{ asset('storeAssets/js/demos/demo-4.js') }}"></script>
</body>

</html>
