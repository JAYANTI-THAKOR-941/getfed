<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

// Fetch summary data for the report
$report_query = "SELECT 
                    COUNT(id) AS total_orders,
                    SUM(total_amount) AS total_sales,
                    SUM(CASE WHEN order_status = 'processing' THEN 1 ELSE 0 END) AS processing_orders,
                    SUM(CASE WHEN order_status = 'shipped' THEN 1 ELSE 0 END) AS shipped_orders,
                    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) AS delivered_orders,
                    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled_orders
                 FROM orders";
$report_result = mysqli_query($conn, $report_query);

// Check for errors
if (!$report_result) {
    echo "Error fetching report data: " . mysqli_error($conn);
    exit();
}

$report_data = mysqli_fetch_assoc($report_result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Admin Dashboard - Orders and Reports</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            width: 90%;
            margin: 50px auto;
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h1 {
            text-align: center;
            color: #333;
        }

        .report-section {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .report-section h2 {
            text-align: center;
            color: #333;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .report-table th,
        .report-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .report-table th {
            background-color: #f04e31;
            color: white;
        }

        .back-btn {
            padding: 8px 15px;
            margin-top: 20px;
            background-color: #234a21;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #1c3c18;
        }

        .chart-container {
            width: 100%;
            max-width: 500px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <main class="dashboard-container">
        <h1>Order Summary Report</h1>
        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>

        <!-- Report Section -->
        <div class="report-section">
            <h2>Order Summary Report</h2>
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Total Orders</th>
                        <th>Total Sales (₹)</th>
                        <th>Processing Orders</th>
                        <th>Shipped Orders</th>
                        <th>Delivered Orders</th>
                        <th>Cancelled Orders</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $report_data['total_orders']; ?></td>
                        <td><?php echo number_format($report_data['total_sales'], 2); ?></td>
                        <td><?php echo $report_data['processing_orders']; ?></td>
                        <td><?php echo $report_data['shipped_orders']; ?></td>
                        <td><?php echo $report_data['delivered_orders']; ?></td>
                        <td><?php echo $report_data['cancelled_orders']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="orderStatusChart"></canvas>
        </div>
    </main>

    <?php include('../includes/footer.php'); ?>

    <script>
        // Data for the order status chart
        const orderStatusData = {
            labels: ['Processing', 'Shipped', 'Delivered', 'Cancelled'],
            datasets: [{
                label: 'Order Status Distribution',
                data: [
                    <?php echo $report_data['processing_orders']; ?>, 
                    <?php echo $report_data['shipped_orders']; ?>, 
                    <?php echo $report_data['delivered_orders']; ?>, 
                    <?php echo $report_data['cancelled_orders']; ?>
                ],
                backgroundColor: ['#f04e31', '#28a745', '#007bff', '#dc3545'],
                borderColor: ['#f04e31', '#28a745', '#007bff', '#dc3545'],
                borderWidth: 1
            }]
        };

        // Configuring the chart
        const config = {
            type: 'pie',
            data: orderStatusData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' orders';
                            }
                        }
                    }
                }
            }
        };

        // Rendering the chart
        const ctx = document.getElementById('orderStatusChart').getContext('2d');
        new Chart(ctx, config);
    </script>
</body>
</html>
