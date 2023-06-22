<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .customer-details,
        .company-details {
            display: table-cell;
            vertical-align: top;
        }

        .customer-details table,
        .company-details table {
            width: 100%;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .details {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table th,
        .summary-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .summary-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .transaction-details {
            text-align: right;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="customer-details">
            <table>
                <tr>
                    <td class="title">Customer Details</td>
                </tr>
                <tr>
                    <td class="details">
                        <table>
                            <tr>
                                <td>Name:</td>
                                <td>John Doe</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td>john@example.com</td>
                            </tr>
                            <tr>
                                <td>Phone:</td>
                                <td>123-456-7890</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="company-details">
            <table>
                <tr>
                    <td class="title">Company Details</td>
                </tr>
                <tr>
                    <td class="details">
                        <table>
                            <tr>
                                <td>Company:</td>
                                <td>ABC Corporation</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>123 Main Street, City</td>
                            </tr>
                            <tr>
                                <td>Country:</td>
                                <td>Country</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="transaction-details">
        <strong>Date of Transaction:</strong> May 22, 2023
    </div>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Product 1</td>
                <td>2</td>
                <td>$10</td>
                <td>$20</td>
            </tr>
            <tr>
                <td>Product 2</td>
                <td>3</td>
                <td>$15</td>
                <td>$45</td>
            </tr>
            <tr>
                <td>Product 3</td>
                <td>1</td>
                <td>$5</td>
                <td>$5</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="total-spent">Total Spent:</td>
                <td class="total-spent">$70</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
