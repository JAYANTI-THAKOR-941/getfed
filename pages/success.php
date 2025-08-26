<?php
session_start();

if (isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - GetFed Healthy Food</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .success-container {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .success-icon {
            font-size: 50px;
            color: #28a745;
        }

        h1 {
            color: #333;
            font-size: 24px;
        }

        p {
            color: #555;
            font-size: 16px;
        }

        .home-btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .home-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Payment Successful!</h1>
        <p>Your payment has been processed successfully. An invoice has been sent to your email.</p>
        <a href="/getfed/index.php" class="home-btn">Go to Home</a>
    </div>

</body>
</html>
