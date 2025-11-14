<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 650px;
            background: white;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .status-box {
            background: #f7f9fc;
            padding: 15px;
            border-left: 4px solid #4a90e2;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            color: #666;
            margin-top: 30px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="email-container">

        <h2>Order Status Update</h2>

        <p>Hello <strong>{{ $order->user->name ?? 'Customer' }}</strong>,</p>

        <p>
            We are happy to inform you that your order
            <strong>#{{ $order->order_number }}</strong>
            has been updated!
        </p>

        <div class="status-box">
            <strong>Current Status:</strong> {{ ucfirst($order->order_status) }} <br><br>

            @if ($order->order_status === 'shipped')
                Your order has been shipped and is on its way!
            @elseif ($order->order_status === 'delivered')
                Your order has been delivered successfully!
            @elseif ($order->order_status === 'cancelled')
                Your order has been cancelled.
            @endif
        </div>

        <h3>Order Details Summary:</h3>
        <p>
            <strong>Order Number:</strong> #{{ $order->order_number }} <br>
            <strong>New Status:</strong> {{ ucfirst($order->order_status) }} <br>
            <strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}
        </p>

        <p class="footer">
            If you have any questions, reply to this email.<br><br>
            Thanks,<br>
            {{ config('app.name') }} Team
        </p>

    </div>

</body>

</html>
