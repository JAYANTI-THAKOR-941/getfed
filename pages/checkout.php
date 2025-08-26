<?php
session_start();
include('../config/db_connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Include the PHPMailer autoload file

// Check if the user is logged in and has a valid session
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (empty($_SESSION['cart'])) {
    echo "<p class='empty-cart-message'>Your cart is empty! Please add items to the cart before checking out.</p>";
    exit;
}
$keyId = 'rzp_test_MCCHlSWeh3mRj4';
$keySecret = 'hTSHYNC8Cm084lPqg9AdejH7';

$cartTotal = 0;
foreach ($_SESSION['cart'] as $product) {
    if (isset($product['price'], $product['quantity'])) {
        $cartTotal += $product['price'] * $product['quantity'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['email'], $_POST['contact'], $_POST['address'], $_POST['payment-method'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $paymentMethod = $_POST['payment-method'];

        // Get user ID from session
        $userId = $_SESSION['user_id'];

        // Insert order data into the database, including the user_id
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, contact, address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssd", $userId, $name, $email, $contact, $address, $paymentMethod, $cartTotal);

        if ($stmt->execute()) {
            $orderId = $stmt->insert_id;

            $_SESSION['order_id'] = $orderId;
            $_SESSION['payment_method'] = $paymentMethod;
            

            // Create the HTML email content
            $emailContent = "
            <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .order-details { border-collapse: collapse; width: 100%; margin-top: 20px; }
                        .order-details th, .order-details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        .order-details th { background-color: #f2f2f2; }
                        .total-price { font-size: 18px; color: #e91e63; font-weight: bold; margin-top: 10px; }
                        .header { background-color: #ff5722; color: white; padding: 15px; text-align: center; }
                        .footer { background-color: #f9f9f9; color: #333; text-align: center; padding: 10px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h1>Order Confirmation - GetFed Healthy Food</h1>
                    </div>
                    <h2>Hello $name,</h2>
                    <p>Thank you for your order! Below are the details of your order:</p>
                    <table class='order-details'>
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price (₹)</th>
                                <th>Quantity</th>
                                <th>Total (₹)</th>
                            </tr>
                        </thead>
                        <tbody>";

            foreach ($_SESSION['cart'] as $product) {
                $emailContent .= "
                    <tr>
                        <td>" . htmlspecialchars($product['name']) . "</td>
                        <td>" . number_format($product['price'], 2) . "</td>
                        <td>" . $product['quantity'] . "</td>
                        <td><strong>₹" . number_format($product['price'] * $product['quantity'], 2) . "</strong></td>
                    </tr>";
            }

            $emailContent .= "
                        </tbody>
                    </table>
                    <p class='total-price'>Total: ₹" . number_format($cartTotal, 2) . "</p>
                    <p>Shipping Address: $address</p>
                    <p>Payment Method: $paymentMethod</p>
                    <div class='footer'>
                        <p>Thank you for choosing GetFed Healthy Food!</p>
                    </div>
                </body>
            </html>";

            // Send the email using PHPMailer
            $mail = new PHPMailer(true); // Passing `true` enables exceptions
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';  // Set the SMTP server to use
                $mail->SMTPAuth = true;
                $mail->Username = 'jyantithakor941@gmail.com';  // SMTP username
                $mail->Password = 'jpybygdivzrhyiqr';  // SMTP password (app-specific password if using Gmail)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('jyantithakor941@gmail.com', 'GetFed');
                $mail->addAddress($email);  // Add the recipient's email address

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Order Confirmation - GetFed Healthy Food';
                $mail->Body = $emailContent;

                $mail->send();
                // If payment is COD, redirect to success page
                if ($paymentMethod == 'cod') {
                    header('Location: success.php');
                    exit;
                }

                // Otherwise, continue to Razorpay payment process
                header('Location: success.php');
                exit;
            } catch (Exception $e) {
                echo "Error sending email: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Clear the cart session
}
?>



<?php

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user details from the database based on user_id from session
$userId = $_SESSION['user_id'];
$query = "SELECT username, email, phone, address FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - GetFed Healthy Food</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <style>
        .checkout-container {
            width: 80%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .heading-primary {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .cart-summary h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .cart-summary .total-price {
            font-size: 20px;
            font-weight: bold;
            color: #e91e63;
            margin-top: 10px;
        }

        .form-section {
            margin-top: 30px;
        }

        .form-section h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }

        .label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .input-field,
        .textarea-field,
        .select-field {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .textarea-field {
            height: 120px;
        }

        .payment-option {
            margin-bottom: 20px;
        }

        .process-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #ff5722;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .process-btn:hover {
            background-color: #e64a19;
        }

        .empty-cart-message {
            text-align: center;
            font-size: 18px;
            color: #e91e63;
        }

        @media (max-width: 768px) {
            .checkout-container {
                width: 95%;
                padding: 15px;
            }

            .heading-primary {
                font-size: 24px;
            }

            .cart-summary h2,
            .form-section h2 {
                font-size: 20px;
            }

            .input-field,
            .textarea-field,
            .select-field {
                font-size: 14px;
            }

            .process-btn {
                font-size: 16px;
            }
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .cart-table th,
        .cart-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #ff5722;
            color: white;
        }

        .cart-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .cart-table tr:hover {
            background-color: #f1f1f1;
        }

        .total-price {
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #e91e63;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <div class="checkout-container">
        <h1 class="heading-primary">Checkout</h1>
        <div class="cart-summary">
            <h2>Your Cart</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price (₹)</th>
                        <th>Quantity</th>
                        <th>Total (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td><?= number_format($product['price'], 2); ?></td>
                            <td><?= $product['quantity']; ?></td>
                            <td><strong>₹<?= number_format($product['price'] * $product['quantity'], 2); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3 class="total-price">Total: ₹<?= number_format($cartTotal, 2); ?></h3>
        </div>

        <div class="form-section">
            <h2>Enter Your Details</h2>
            <form method="POST" id="checkout-form">
                <label for="name" class="label">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['username']); ?>"
                    required class="input-field">

                <label for="email" class="label">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                    required class="input-field">

                <label for="contact" class="label">Contact Number:</label>
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['phone']); ?>"
                    required class="input-field">

                <label for="address" class="label">Shipping Address:</label>
                <textarea id="address" name="address" required
                    class="textarea-field"><?php echo htmlspecialchars($user['address']); ?></textarea>

                <div class="payment-option">
                    <label for="payment-method" class="label">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required class="select-field">
                        <option value="online">Online Payment</option>
                        <option value="cod">Cash on Delivery</option>
                    </select>
                </div>

                <button type="submit" class="process-btn">Proceed to Payment</button>
            </form>
        </div>

        <script>
            document.getElementById('checkout-form').onsubmit = function (event) {
                event.preventDefault();

                const paymentMethod = document.getElementById('payment-method').value;
                if (paymentMethod === 'online') {
                    const options = {
                        key: "<?= $keyId; ?>",
                        amount: "<?= $cartTotal * 100; ?>",
                        currency: "INR",
                        name: "GetFed Healthy Food",
                        description: "Checkout Payment",
                        handler: function (response) {
                            alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
                            document.getElementById('checkout-form').submit();
                        },
                        prefill: {
                            name: document.getElementById('name').value,
                            email: document.getElementById('email').value,
                            contact: document.getElementById('contact').value,
                        },
                        notes: {
                            address: document.getElementById('address').value
                        },
                        theme: {
                            color: "#ff5722"
                        }
                    };

                    const razorpay = new Razorpay(options);
                    razorpay.open();
                } else {
                    // If cash on delivery, directly submit the form
                    document.getElementById('checkout-form').submit();
                }
            };
        </script>
        <?php include('../includes/footer.php'); ?>

</body>

</html>