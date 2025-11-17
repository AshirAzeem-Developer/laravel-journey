<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f7;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            width: 100%;
            background: #f4f4f7;
            padding: 20px 0;
        }

        .email-content {
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        .table-box {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-box th {
            background: #0d6efd;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        .table-box td {
            padding: 10px;
            border-bottom: 1px solid #e5e5e5;
            font-size: 14px;
        }

        .button {
            display: inline-block;
            background: #0d6efd;
            color: #fff !important;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>

<body>

    <div class="email-wrapper">
        <div class="email-content">

            <h2>Order Confirmation 🎉</h2>

            <p>
                Assalamu Alaikum <strong>{!! $order->user->name !!}</strong>,
                <br><br>
                Thank you for placing your order with <strong>{!! config('app.name') !!}</strong>!
                <br>Your order has been successfully placed and is now being processed.
            </p>

            <!-- ORDER SUMMARY TABLE -->
            <h3>🧾 Order Summary</h3>
            <table class="table-box">
                <tr>
                    <th>Detail</th>
                    <th>Information</th>
                </tr>
                <tr>
                    <td>Order Number</td>
                    <td>{!! $order->order_number !!}</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td>${!! number_format($order->total_amount, 2) !!}</td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>{!! ucfirst(str_replace('_', ' ', $order->payment_method)) !!}</td>
                </tr>
                <tr>
                    <td>Order Status</td>
                    <td>{!! ucfirst($order->order_status) !!}</td>
                </tr>
                <tr>
                    <td>Payment Status</td>
                    <td>{!! ucfirst($order->payment_status) !!}</td>
                </tr>
            </table>

            <!-- SHIPPING ADDRESS -->
            <h3 style="margin-top: 25px;">🚚 Shipping Address</h3>
            <table class="table-box">
                <tr>
                    <th>Address</th>
                </tr>
                <tr>
                    <td>{!! $order->shipping_address ?? 'N/A' !!}</td>
                </tr>
            </table>

            <!-- BUTTON -->
            <a href="{{ url('/orders/' . $order->id) }}" class="button">View Your Order</a>

            <!-- FOOTER -->
            <div class="footer">
                <p>
                    If you have any questions, feel free to reply to this email — we’re happy to help!
                    <br><br>
                    Thanks again for choosing <strong>{!! config('app.name') !!}</strong>.
                    <br>
                    May Allah bless your purchase and bring you happiness.
                    <br><br>
                    JazakAllah Khair,<br>
                    <strong>{!! config('app.name') !!} Team</strong>
                </p>
            </div>

        </div>
    </div>

</body>

</html>
