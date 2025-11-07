<x-mail::message>
    # Order Confirmation 🎉

    Assalamu Alaikum {{ $order->user->name }},

    Thank you for placing your order with **{{ config('app.name') }}**!

    Your order has been successfully placed and is now being processed.

    ---

    ### 🧾 Order Summary
    **Order Number:** {{ $order->order_number }}
    **Total Amount:** ${{ number_format($order->total_amount, 2) }}
    **Payment Method:** {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
    **Order Status:** {{ ucfirst($order->order_status) }}
    **Payment Status:** {{ ucfirst($order->payment_status) }}

    ---

    ### 🚚 Shipping Address
    {{ $order->shipping_address ?? 'N/A' }}

    ---

    <x-mail::button :url="url('/orders/' . $order->id)">
        View Your Order
    </x-mail::button>

    If you have any questions, feel free to reply to this email — we’re happy to help!

    Thanks again for choosing **{{ config('app.name') }}**.
    May Allah bless your purchase and bring you happiness.

    JazakAllah Khair,
    **{{ config('app.name') }} Team**

</x-mail::message>
