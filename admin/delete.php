<?php
include('../config/db_connection.php');

// Check if the product ID is provided
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM food_products WHERE id = $productId";
    mysqli_query($conn, $deleteQuery);

    header('Location: food_product_dashboard.php');
    exit;
} 
?>
