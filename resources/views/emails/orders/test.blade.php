<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #333333;
            margin: 0;
        }
        .order-details {
            border: 1px solid #dddddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-details h2 {
            font-size: 18px;
            margin: 0;
            margin-bottom: 10px;
            color: #333333;
        }
        .order-details p {
            margin: 0;
            margin-bottom: 10px;
            color: #555555;
        }
        .order-details .order-item {
            margin-bottom: 10px;
        }
        .order-details .order-item .item-name {
            font-weight: bold;
        }
        .order-details .order-item .item-price {
            color: #888888;
        }
        .footer {
            text-align: center;
            color: #888888;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmation</h1>
        </div>
        <div class="order-details">
            <h2>Order Summary</h2>
            <p><strong>Order ID:</strong> #123456789</p>
            <p><strong>Date:</strong> May 22, 2023</p>
            <p><strong>Shipping Address:</strong> John Doe, 123 Main Street, City, Country</p>
            <div class="order-item">
                <p class="item-name">Product 1</p>
                <p class="item-price">$10</p>
            </div>
            <div class="order-item">
                <p class="item-name">Product 2</p>
                <p class="item-price">$15</p>
            </div>
            <div class="order-item">
                <p class="item-name">Product 3</p>
                <p class="item-price">$5</p>
            </div>
            <p><strong>Total Amount:</strong> $30</p>
        </div>
        <div class="footer">
            <p>This is an automated email, please do not reply.</p>
        </div>
    </div>
</body>
</html>
