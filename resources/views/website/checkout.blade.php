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
</head>

<body>
    {{-- Displaying session error flash message --}}
    @if ($session_error)
        <div class="alert alert-danger text-center" role="alert">
            {{ $session_error }}
            {{ Session::forget('order_error') }}
        </div>
    @endif

    {{-- Display general error/validation errors --}}
    @include('website.components.toast')

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
                                                class="form-control individual-address-field @error('firstname') is-invalid @enderror"
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
                                                class="form-control individual-address-field @error('lastname') is-invalid @enderror"
                                                name="lastname" value="{{ old('lastname', $user_data['lastname']) }}"
                                                required />
                                            @error('lastname')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <label>Company Name (Optional)</label>
                                    <input type="text" class="form-control individual-address-field" name="company"
                                        value="{{ old('company') }}" />

                                    <label>Country *</label>
                                    <input type="text"
                                        class="form-control individual-address-field @error('country') is-invalid @enderror"
                                        name="country" value="Pakistan" required />
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <label>Street address *</label>
                                    {{-- Address Line 1 --}}
                                    <input type="text"
                                        class="form-control address-field individual-address-field @error('address1') is-invalid @enderror"
                                        name="address1" placeholder="House number and Street name"
                                        value="{{ $user_data['address1'] }}" required />
                                    @error('address1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Address Line 2 --}}
                                    <input type="text" class="form-control address-field individual-address-field"
                                        name="address2" placeholder="Appartments, suite, unit etc ..."
                                        value="{{ $user_data['address2'] }}" />

                                    <div class="row">
                                        {{-- City --}}
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text"
                                                class="form-control address-field individual-address-field @error('city') is-invalid @enderror"
                                                name="city" value="{{ $user_data['city'] }}" required />
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- State --}}
                                        <div class="col-sm-6">
                                            <label>State / County *</label>
                                            <input type="text"
                                                class="form-control address-field individual-address-field @error('state') is-invalid @enderror"
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
                                                class="form-control address-field individual-address-field @error('postcode') is-invalid @enderror"
                                                name="postcode" value="{{ $user_data['postcode'] }}" required />
                                            @error('postcode')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        {{-- Phone --}}
                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="tel"
                                                class="form-control individual-address-field @error('phone_number') is-invalid @enderror"
                                                name="phone_number" value="{{ $user_data['phone_number'] }}"
                                                required />
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <label>Email address *</label>
                                    <input type="email" class="form-control individual-address-field" name="email"
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
                                    <textarea class="form-control" cols="30" rows="4" name="order_notes"
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
    <script
        src="https://www.paypal.com/sdk/js?client-id=AXbmCprgHhbefFilx3Oy-8KocEGMBhNqOj01iirOVY1hdbpNG9ZcGmmi_Cw7AmeKHl7yA6veLp26SCSF&currency=USD">
    </script>

    <script>
        $(document).ready(function() {
            const FINAL_TOTAL = {{ number_format($final_total, 2, '.', '') }};
            const COD_BUTTON = $('#cod-submit-button');
            const PAYPAL_CONTAINER = $('#paypal-button-container');

            // --- Dynamic Address Update Logic ---
            function updateAddressStrings() {
                var currentAddress = [
                    $('input[name="address1"]').val(),
                    $('input[name="address2"]').val(),
                    $('input[name="city"]').val(),
                    $('input[name="state"]').val(),
                    $('input[name="postcode"]').val(),
                    $('input[name="country"]').val()
                ].filter(Boolean).join(', ');

                $('#shipping_address_string_input').val(currentAddress);
                $('#billing_address_string_input').val(currentAddress);
            }

            // --- CRITICAL FIX: Clean Up Individual Address Fields before submission ---
            function cleanAddressFields() {
                // 1. Ensure the hidden fields are updated with the latest user input
                updateAddressStrings();

                // 2. Temporarily DISABLE all individual address fields.
                // This prevents them from being serialized and sent to the server.
                $('.individual-address-field').prop('disabled', true);
            }

            // --- CRITICAL FIX: Re-enable fields after potential error/redirect ---
            // If the user lands on this page via redirect (e.g., failed validation),
            // the fields might be disabled from a previous failed submission. We re-enable them on load.
            $('.individual-address-field').prop('disabled', false);

            // --- Event Listeners ---

            // 1. Bind the address update logic to typing/changing fields
            $('.address-field').on('change keyup', updateAddressStrings);

            // Run address update on load
            updateAddressStrings();

            // 2. Handle COD Submission (non-PayPal)
            $('#checkout-form').on('submit', function(e) {
                // Run the cleanup immediately before the form submits
                cleanAddressFields();

                // Since this is a standard form submit, we return true to proceed.
                return true;
            });

            // 3. Payment Method Toggle Logic
            $('.payment-method-radio').on('change', function() {
                // Before toggling visibility, ensure fields are enabled for editing if COD is selected
                $('.individual-address-field').prop('disabled', false);

                if ($(this).val() === 'paypal') {
                    COD_BUTTON.hide();
                    PAYPAL_CONTAINER.show();
                    $('#checkout-form').prop('action', '#');
                } else {
                    COD_BUTTON.show();
                    PAYPAL_CONTAINER.hide();
                    $('#checkout-form').prop('action', '{{ route('checkout.store') }}');
                }
            });
            $('.payment-method-radio:checked').trigger('change');


            // 4. PayPal Button Setup
            paypal.Buttons({
                createOrder: function(data, actions) {
                    if (!document.getElementById('checkout-form').checkValidity()) {
                        alert('Please fill out all required billing and shipping fields.');
                        document.getElementById('checkout-form').reportValidity();
                        return false;
                    }
                    // Ensure hidden address strings are current before creating PayPal order
                    updateAddressStrings();

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: FINAL_TOTAL
                            },
                            description: 'Kharido.pk Order'
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Submit form data to Laravel route for final processing
                        processServerPayment(data.orderID);
                    });
                },
                onCancel: function(data) {
                    alert('PayPal payment was cancelled.');
                },
                onError: function(err) {
                    console.error("PayPal Error:", err);
                    alert('An error occurred during the PayPal transaction. Please check the console.');
                }
            }).render('#paypal-button-container');

            // 5. Server-Side Finalization Function for PayPal (Manually submits form)
            function processServerPayment(paypalOrderID) {
                // CRITICAL: We must clean and disable fields here too, as PayPal approval bypasses the form's submit handler
                cleanAddressFields();

                var formData = $('#checkout-form').serializeArray();

                // Append custom payment fields
                formData.push({
                    name: 'paypal_order_id',
                    value: paypalOrderID
                });
                formData.push({
                    name: 'payment_method',
                    value: 'paypal'
                });

                let tempForm = $('<form>', {
                    action: '{{ route('checkout.store') }}',
                    method: 'POST'
                });

                $.each(formData, function(i, field) {
                    tempForm.append($('<input>', {
                        type: 'hidden',
                        name: field.name,
                        value: field.value
                    }));
                });

                $('body').append(tempForm);
                tempForm.submit();
            }
        });
    </script>
</body>

</html>
