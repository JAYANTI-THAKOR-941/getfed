<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

include('../config/db_connection.php');

// Check if order ID is provided in the URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Sanitize order ID to prevent SQL injection
    $order_id = mysqli_real_escape_string($conn, $order_id);

    // Delete the order from the database
    $query = "DELETE FROM orders WHERE id = '$order_id'";

    if (mysqli_query($conn, $query)) {
        // Redirect back to the orders page with a success message
        header('Location: orders_management.php?status=success');
        exit();
    } else {
        // If there was an error deleting the order
        echo "Error deleting order: " . mysqli_error($conn);
        exit();
    }
} else {
    // If no order ID is provided in the URL
    echo "Order ID is missing.";
    exit();
}
?>
