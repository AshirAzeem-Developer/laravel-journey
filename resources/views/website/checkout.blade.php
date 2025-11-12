@php
    // --- Data passed directly from the Route/Controller ---
    // $cartItems (Collection of App\Models\Cart with product relation)
    // $subtotal (Calculated total from Cart items)

    // --- Default/Helper Variables ---
    $error = $error ?? '';
    $session_error = Session::get('order_error');

    // Defaulting shipping cost for display/initial calculation (adjust as needed)
    $shipping_cost = 10.0;
    $final_total = $subtotal + $shipping_cost;

    // Helper function equivalent for formatting price in Blade
    $format_price_rs = fn($price) => number_format((float) $price, 2, '.', ',');

    // Mock user data for form autofill (Replace with actual Auth::user() data if available)
    $user = Auth::user();
    $user_data = [
        'firstname' => $user->name ?? '',
        'lastname' => '', // Assuming last name might be separate
        'email' => $user->email ?? '',
        // Use old input if validation failed, otherwise use user defaults
        'address1' => old('address1', $user->address_line_1 ?? ''),
        'address2' => old('address2', $user->address_line_2 ?? ''),
        'city' => old('city', $user->city ?? ''),
        'state' => old('state', $user->state ?? ''),
        'postcode' => old('postcode', $user->postcode ?? ''),
        'phone_number' => old('phone_number', $user->phone ?? ''),
    ];

    // Initial full address string creation (used for hidden inputs on load)
    $full_address_string = implode(
        ', ',
        array_filter([
            $user_data['address1'],
            $user_data['address2'],
            $user_data['city'],
            $user_data['state'],
            $user_data['postcode'],
            'Pakistan', // Assuming fixed country
        ]),
    );

@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kharido.pk | Checkout</title>
    {{-- Include CSRF token for AJAX and form submissions --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('storeAssets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('storeAssets/css/style.css') }}" />
    <style>
        /* Simple Tailwind CSS styles for the Toast, required since full Tailwind is not assumed here */
        #toast {
            transition: all 0.5s ease-in-out;
            opacity: 1;
            transform: translateX(0);
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(5px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .text-rose-500 {
            color: #f43f5e;
        }

        .bg-rose-500\/90 {
            background-color: rgba(244, 63, 94, 0.9);
        }

        .border-rose-400 {
            border-color: #fb7185;
        }

        .text-white {
            color: #fff;
        }

        .backdrop-blur-lg {
            backdrop-filter: blur(8px);
        }

        .rounded-xl {
            border-radius: 0.75rem;
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .border {
            border-width: 1px;
        }

        .fixed {
            position: fixed;
        }

        .top-5 {
            top: 1.25rem;
        }

        .right-5 {
            right: 1.25rem;
        }

        .z-\[9999\] {
            z-index: 9999;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .px-5 {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .w-12 {
            width: 3rem;
        }

        .h-12 {
            height: 3rem;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .tracking-wide {
            letter-spacing: 0.025em;
        }

        /* Icon colors for toast (simplified) */
        #toast svg {
            stroke: white;
        }
    </style>
</head>

<body>
    {{-- Displaying session error flash message --}}
    @if ($session_error)
        <div class="alert alert-danger text-center" role="alert">
            {{ $session_error }}
            {{ Session::forget('order_error') }}
        </div>
    @endif

    {{-- The include below is assumed to render the toast structure that JS populates --}}
    {{-- @include('website.components.toast') --}}

    @if ($errors->any())
        <div class="alert alert-warning text-center" role="alert">
            Please correct the errors in the form below.
        </div>
    @endif

    <div class="page-wrapper">
        @include('website.layouts.header')

        <main class="main">
            <div class="page-content">
                <div class="checkout">
                    <div class="container">
                        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-9">
                                    <h2 class="checkout-title">Billing Details</h2>
                                    <div class="row">
                                        {{-- First Name --}}
                                        <div class="col-sm-6">
                                            <label>First Name *</label>
                                            <input type="text"
                                                class=" text-2xl text-black form-control individual-address-field @error('firstname') is-invalid @enderror"
                                                name="firstname" value="{{ old('firstname', $user_data['firstname']) }}"
                                                required />
                                            @error('firstname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- Last Name --}}
                                        <div class="col-sm-6">
                                            <label>Last Name *</label>
                                            <input type="text"
                                                class="text-2xl text-black form-control individual-address-field @error('lastname') is-invalid @enderror"
                                                name="lastname" value="{{ old('lastname', $user_data['lastname']) }}"
                                                required />
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <label>Company Name (Optional)</label>
                                    <input type="text"
                                        class="text-2xl text-black form-control individual-address-field" name="company"
                                        value="{{ old('company') }}" />

                                    <label>Country *</label>
                                    <input type="text"
                                        class="text-2xl text-black form-control individual-address-field @error('country') is-invalid @enderror"
                                        name="country" value="Pakistan" required />
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label>Street address *</label>
                                    {{-- Address Line 1 --}}
                                    <input type="text"
                                        class="text-2xl text-black form-control address-field individual-address-field @error('address1') is-invalid @enderror"
                                        name="address1" placeholder="House number and Street name"
                                        value="{{ $user_data['address1'] }}" required />
                                    @error('address1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Address Line 2 --}}
                                    <label>Street address 2 (Optional)</label>
                                    <input type="text"
                                        class="text-2xl text-black form-control address-field individual-address-field"
                                        name="address2" placeholder="Appartments, suite, unit etc ..."
                                        value="{{ $user_data['address2'] }}" />

                                    <div class="row">
                                        {{-- City --}}
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text"
                                                class="text-2xl text-black form-control address-field individual-address-field @error('city') is-invalid @enderror"
                                                name="city" value="{{ $user_data['city'] }}" required />
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- State --}}
                                        <div class="col-sm-6">
                                            <label>State / County *</label>
                                            <input type="text"
                                                class="text-2xl text-black form-control address-field individual-address-field @error('state') is-invalid @enderror"
                                                name="state" value="{{ $user_data['state'] }}" required />
                                            @error('state')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        {{-- Postcode --}}
                                        <div class="col-sm-6">
                                            <label>Postcode / ZIP *</label>
                                            <input type="text"
                                                class="text-2xl text-black form-control address-field individual-address-field @error('postcode') is-invalid @enderror"
                                                name="postcode" value="{{ $user_data['postcode'] }}" required />
                                            @error('postcode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- Phone --}}
                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="tel"
                                                class="text-2xl text-black form-control individual-address-field @error('phone_number') is-invalid @enderror"
                                                name="phone_number" value="{{ $user_data['phone_number'] }}"
                                                required />
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <label>Email address *</label>
                                    <input type="email"
                                        class="text-2xl text-black form-control individual-address-field" name="email"
                                        value="{{ $user_data['email'] }}" required readonly />


                                    {{-- HIDDEN INPUTS (These must remain enabled and pass the final single string) --}}
                                    <input type="hidden" name="shipping_address" id="shipping_address_string_input"
                                        value="{{ $full_address_string }}">
                                    <input type="hidden" name="billing_address" id="billing_address_string_input"
                                        value="{{ $full_address_string }}">

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="checkout-diff-address" />
                                        <label class="custom-control-label" for="checkout-diff-address">Ship to a
                                            different address?</label>
                                    </div>
                                    <label>Order notes (optional)</label>
                                    <textarea class="text-2xl text-black form-control" cols="30" rows="4" name="order_notes"
                                        placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order_notes') }}</textarea>
                                </div>

                                <aside class="col-lg-3">
                                    <div class="summary">
                                        <h3 class="summary-title">Your Order</h3>
                                        <table class="table table-summary">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- ⚠️ Loop through actual $cartItems data ⚠️ --}}
                                                @forelse ($cartItems as $item)
                                                    <tr class="summary-product">
                                                        <td>
                                                            <a href="#">
                                                                {{ $item->product->product_name ?? 'Unknown Product' }}
                                                                (x{{ $item->quantity }})
                                                            </a>
                                                        </td>
                                                        <td>Rs.
                                                            {{ $format_price_rs(($item->product->price ?? 0) * $item->quantity) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="summary-product">
                                                        <td colspan="2" class="text-center">No items in cart.</td>
                                                    </tr>
                                                @endforelse

                                                <tr class="summary-subtotal">
                                                    <td>Subtotal:</td>
                                                    <td>Rs. {{ $format_price_rs($subtotal) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Shipping:</td>
                                                    <td>Rs. {{ $format_price_rs($shipping_cost) }}</td>
                                                </tr>
                                                <tr class="summary-total">
                                                    <td>Total:</td>
                                                    <td>Rs. {{ $format_price_rs($final_total) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="accordion-summary" id="accordion-payment">
                                            {{-- COD Option --}}
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="cod_radio" name="payment_method"
                                                            value="cash_on_delivery"
                                                            class="custom-control-input payment-method-radio" checked
                                                            required>
                                                        <label class="custom-control-label" for="cod_radio">Cash on
                                                            Delivery</label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- PayPal Option --}}
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="paypal_radio" name="payment_method"
                                                            value="paypal"
                                                            class="custom-control-input payment-method-radio" required>
                                                        <label class="custom-control-label" for="paypal_radio">
                                                            PayPal
                                                            <img src="{{ asset('storeAssets/images/payments-summary.png') }}"
                                                                alt="PayPal" class="float-right"
                                                                style="height: 20px;">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Hidden inputs for server processing --}}
                                        <input type="hidden" name="total_amount"
                                            value="{{ number_format($final_total, 2, '.', '') }}">
                                        <input type="hidden" name="shipping_cost_amount"
                                            value="{{ number_format($shipping_cost, 2, '.', '') }}">

                                        <button type="submit" id="cod-submit-button"
                                            class="btn btn-outline-primary-2 btn-order btn-block">
                                            <span class="btn-text">Place Order</span>
                                            <span class="btn-hover-text">Proceed to Checkout</span>
                                        </button>

                                        <div id="paypal-button-container" style="margin-top: 15px; display: none;">
                                        </div>

                                    </div>
                                </aside>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        @include('website.layouts.footer')
    </div>

    <script src="{{ asset('storeAssets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('storeAssets/js/main.js') }}"></script>

    {{-- PayPal SDK using Blade interpolation for the public client ID --}}
    <script src="https://www.paypal.com/sdk/js?client-id={{ env('PAYPAL_CLIENT_ID_PUBLIC') }}&currency=USD"></script>

    <script>
        $(document).ready(function() {
            const FINAL_TOTAL = {{ number_format($final_total, 2, '.', '') }};
            const COD_BUTTON = $('#cod-submit-button');
            const PAYPAL_CONTAINER = $('#paypal-button-container');
            const CHECKOUT_FORM = $('#checkout-form');

            // Toast Helper - Pure UI feedback (minimal JS)
            function showToast(message, type = 'info') {
                const existingToast = document.getElementById('toast');
                if (existingToast) existingToast.remove();

                const styles = {
                    success: {
                        bg: 'bg-green-500/90',
                        border: 'border-green-400',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    },
                    error: {
                        bg: 'bg-rose-500/90',
                        border: 'border-rose-400',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    },
                    info: {
                        bg: 'bg-blue-500/90',
                        border: 'border-blue-400',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20h.01"/></svg>'
                    }
                };
                const style = styles[type] || styles.info;

                document.body.insertAdjacentHTML('beforeend',
                    `<div id="toast" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-white backdrop-blur-lg animate-fadeIn ${style.bg} ${style.border}">
                    ${style.icon}
                    <span class="text-xl font-medium tracking-wide">${message}</span>
                </div>`
                );

                const toast = document.getElementById('toast');
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(5px)';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }

            // Address string builder - Only for UI convenience, validation happens server-side
            function updateAddressStrings() {
                const address = [
                    $('input[name="address1"]').val(),
                    $('input[name="address2"]').val(),
                    $('input[name="city"]').val(),
                    $('input[name="state"]').val(),
                    $('input[name="postcode"]').val(),
                    $('input[name="country"]').val()
                ].filter(Boolean).join(', ');

                $('#shipping_address_string_input').val(address);
                $('#billing_address_string_input').val(address);
            }

            // Initialize - All fields enabled for server validation
            $('.individual-address-field').prop('disabled', false);
            $('.address-field').on('change keyup', updateAddressStrings);
            updateAddressStrings();

            // COD Form Submission - Direct server-side processing
            CHECKOUT_FORM.on('submit', function(e) {
                if ($('input[name="payment_method"]:checked').val() === 'cash_on_delivery') {
                    updateAddressStrings();
                    return true; // Server handles everything
                }
                e.preventDefault(); // Block form submit for PayPal
                return false;
            });

            // Payment method toggle - Pure UI
            $('.payment-method-radio').on('change', function() {
                const method = $(this).val();
                if (method === 'paypal') {
                    COD_BUTTON.hide();
                    PAYPAL_CONTAINER.show();
                    CHECKOUT_FORM.prop('action', '#');
                } else {
                    COD_BUTTON.show();
                    PAYPAL_CONTAINER.hide();
                    CHECKOUT_FORM.prop('action', '{{ route('checkout.store') }}');
                }
            });
            $('.payment-method-radio:checked').trigger('change');

            // PayPal Integration - Minimal client logic, maximum server validation
            paypal.Buttons({
                // Step 1: Create PayPal order (client-side only for PayPal SDK)
                createOrder: function(data, actions) {
                    // Basic HTML5 validation before opening PayPal popup
                    if (!CHECKOUT_FORM[0].checkValidity()) {
                        showToast('Please fill out all required fields.', 'error');
                        CHECKOUT_FORM[0].reportValidity();
                        return Promise.reject(new Error('Form validation failed'));
                    }

                    updateAddressStrings();

                    // Create PayPal order (required by PayPal SDK)
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: FINAL_TOTAL.toFixed(2),
                                currency_code: 'USD'
                            },
                            description: 'Kharido.pk Order'
                        }]
                    });
                },

                // Step 2: Payment approved - Capture and send to server
                onApprove: function(data, actions) {
                    console.log('✅ PayPal payment approved. Order ID:', data.orderID);

                    // Capture payment (required by PayPal SDK)
                    return actions.order.capture().then(function(details) {
                        console.log('✅ Payment captured:', details.status);
                        showToast('Payment successful! Creating your order...', 'success');

                        // Send everything to server for validation and order creation
                        processServerPayment(data.orderID, details);
                    });
                },

                // Step 3: Payment cancelled
                onCancel: function(data) {
                    console.log('❌ PayPal payment cancelled by user');
                    showToast('Payment was cancelled.', 'info');
                },

                // Step 4: PayPal SDK error
                onError: function(err) {
                    console.error('❌ PayPal SDK Error:', err);
                    showToast('PayPal error occurred. Please try again.', 'error');
                }
            }).render('#paypal-button-container');

            /**
             * SERVER-SIDE PROCESSING
             * This function only sends data to server - all validation,
             * order creation, and business logic happens on the server
             */
            function processServerPayment(paypalOrderID, details) {
                console.log('📤 Sending order to server for processing...');

                updateAddressStrings(); // Final sync

                // Collect all form data
                const formData = CHECKOUT_FORM.serializeArray();
                formData.push({
                    name: 'paypal_order_id',
                    value: paypalOrderID
                }, {
                    name: 'payment_method',
                    value: 'paypal'
                });

                // Disable button to prevent double submission
                PAYPAL_CONTAINER.find('button').prop('disabled', true);
                showToast('Processing your order...', 'info');

                // Send to server - Server does ALL the heavy lifting
                $.ajax({
                    url: '{{ route('checkout.paypal.store') }}',
                    method: 'POST',
                    data: $.param(formData),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    timeout: 30000, // 30 second timeout

                    // Success: Server validated everything and created order
                    success: function(response) {
                        console.log('✅ Server response:', response);

                        if (response.success === true && response.redirect_url) {
                            showToast('Order placed successfully! Redirecting...', 'success');

                            // Small delay for user to see success message
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 1000);
                        } else {
                            // Unexpected response format
                            console.error('❌ Unexpected server response:', response);
                            showToast('Order processing error. Please contact support.', 'error');
                            PAYPAL_CONTAINER.find('button').prop('disabled', false);
                        }
                    },

                    // Error: Server validation failed or system error
                    error: function(xhr, status, error) {
                        console.error('❌ Server Error:', {
                            status: xhr.status,
                            statusText: status,
                            error: error,
                            response: xhr.responseText
                        });

                        PAYPAL_CONTAINER.find('button').prop('disabled', false);

                        let message = 'Order processing failed. Please try again.';

                        // Parse server error message
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);

                            if (xhr.status === 422 && errorResponse.messages) {
                                // Validation errors from server
                                const errors = Object.values(errorResponse.messages).flat();
                                message = 'Validation error: ' + errors.join('; ');
                            } else if (errorResponse.error) {
                                message = errorResponse.error;
                            }

                            // Log detailed error for debugging
                            if (errorResponse.details) {
                                console.error('Server error details:', errorResponse.details);
                            }
                        } catch (e) {
                            console.error('Failed to parse error response:', e);
                        }

                        // Network/timeout errors
                        if (xhr.status === 0) {
                            message = 'Network error. Please check your connection.';
                        } else if (status === 'timeout') {
                            message = 'Request timeout. Please try again.';
                        }

                        showToast(message, 'error');
                    }
                });
            }
        });
    </script>
</body>

</html>
