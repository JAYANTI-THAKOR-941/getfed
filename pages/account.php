<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User ID not found in session.");
}

require_once('../config/db_connection.php'); 

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Fetch all user orders for showing after clicking "Show All Orders" button
$all_orders_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$all_order_result = $conn->query($all_orders_sql);
if ($conn->error) {
    die("Query Error: " . $conn->error);
}
$all_orders = $all_order_result->fetch_all(MYSQLI_ASSOC);

// Handle order cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $cancel_order_id = $_POST['cancel_order_id'];
    $cancel_sql = "UPDATE orders SET order_status = 'Cancelled' WHERE id = $cancel_order_id AND user_id = $user_id";

    if ($conn->query($cancel_sql)) {
        header("Location: account.php");
        exit();
    } else {
        $error = "Failed to cancel the order.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - GetFed Healthy Food</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .heading {
            text-align: center;
            margin: 30px 0;
        }

        .button, .close-button {
            background-color: #2ecc71;
            color: #fff;
            padding: 12px 50px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            border: 0;
            margin: 2% 0;
        }

        /* Profile Section */
        .profile-info {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .profile-info img,
        .username-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .username-circle {
            background-color: #2ecc71;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 60px;
        }

        .profile-info h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .profile-info p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        /* Orders Section */
        .orders-info {
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .order-item {
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        .order-item div {
            flex: 1;
        }

        .order-item p {
            margin: 5px 0;
            color: #555;
        }

        .order-item p strong {
            color: #2ecc71;
        }

        .cancel-button {
            background-color: #e74c3c;
            color: #fff;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            border: none;
            cursor: pointer;
            margin: 2% 0;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
        }

        .orders-table th,
        .orders-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .orders-table th {
            background-color: #f4f4f4;
        }

        /* Close Button */
        .close-button {
            background-color: #f39c12;
            color: #fff;
            padding: 10px 40px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .order-item {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="container">
        <h1 class="heading">My Account - GetFed Healthy Food</h1>

        <!-- Profile Section -->
        <div class="profile-info">
            <?php if ($user['profile_image']): ?>
                <img src="/getfed/uploads/<?php echo basename($user['profile_image']); ?>" alt="Profile Image">
            <?php else: ?>
                <div class="username-circle"><?php echo strtoupper($user['username'][0]); ?></div>
            <?php endif; ?>
            <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

            <?php if ($user['phone'] && $user['address'] && $user['date_of_birth']): ?>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['date_of_birth']); ?></p>
                <a href="/getfed/pages/update-profile.php" class="button">Update Profile</a>
            <?php else: ?>
                <p><a href="/getfed/pages/complete-profile.php" class="button">Complete Your Profile</a></p>
            <?php endif; ?>
        </div>

        <!-- Orders Section -->
        <div class="orders-info">
            <h3>My Orders</h3>
            <?php if (count($all_orders) > 0): ?>
                <table class="orders-table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Total Amount</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_orders as $order): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td><?php echo $order['order_status']; ?></td>
                                <td>₹<?php echo $order['total_amount']; ?></td>
                                <td><?php echo $order['payment_status']; ?></td>
                                <td><?php echo $order['payment_method']; ?></td>
                                <td>
                                    <?php if ($order['order_status'] !== 'Cancelled'): ?>
                                        <form method="POST">
                                            <input type="hidden" name="cancel_order_id" value="<?php echo $order['id']; ?>">
                                            <button type="submit" class="cancel-button">
                                                Cancel Order
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span>Cancelled</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No orders found.</p>
            <?php endif; ?>
        </div>

    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>
