<?php
include('../config/db_connection.php');
require '../vendor/autoload.php'; // For PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "All fields are required!";
    } else {
        // Check if user already exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error_message = "This email is already registered!";
        } else {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Send OTP to email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // SMTP settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jyantithakor941@gmail.com'; 
                $mail->Password = 'jpybygdivzrhyiqr'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Email settings
                $mail->setFrom('jyantithakor941@gmail.com', 'GetFed Healthy Food');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP for Registration';
                
                // OTP HTML body
                $mail->Body = "
                    <!DOCTYPE html>
                    <html lang='en'>
                    <head>
                        <meta charset='UTF-8'>
                        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                        <title>OTP Verification</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f4f4f4;
                                padding: 20px;
                                margin: 0;
                            }
                            .email-container {
                                background-color: #fff;
                                padding: 30px;
                                max-width: 600px;
                                margin: 0 auto;
                                border-radius: 8px;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                text-align: center;
                                color: #333;
                            }
                            .otp-code {
                                font-size: 24px;
                                font-weight: bold;
                                color: #28a745;
                                text-align: center;
                                margin: 20px 0;
                            }
                            .content {
                                font-size: 16px;
                                line-height: 1.5;
                                color: #555;
                            }
                            .footer {
                                text-align: center;
                                margin-top: 30px;
                                font-size: 14px;
                                color: #777;
                            }
                            .footer a {
                                color: #007bff;
                                text-decoration: none;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <div class='header'>
                                <h2>GetFed - OTP Verification</h2>
                            </div>
                            <div class='content'>
                                <p>Hello <strong>$username</strong>,</p>
                                <p>Thank you for registering with GetFed. To complete your registration, please use the following OTP:</p>
                                <div class='otp-code'>$otp</div>
                                <p>If you did not request this OTP, please ignore this email.</p>
                            </div>
                            <div class='footer'>
                                <p>Thank you for choosing GetFed!</p>
                                <p>For support, <a href='mailto:support@getfed.com'>contact us</a>.</p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";

                $mail->send();

                // Store OTP and user data in session
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);
                $_SESSION['otp'] = $otp;

                // Redirect to OTP verification page
                header('Location: otp_verification.php');
                exit;
            } catch (Exception $e) {
                $error_message = "Error sending OTP: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>Register - GetFed</title>
</head>
<body>
    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- Registration Form Section -->
    <section class="auth-section">
        <div class="auth-container">
            <!-- Right side form -->
            <div class="auth-form">
                <h2>Register</h2>

                <!-- Display error message if any -->
                <?php if (isset($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="register" class="btn">Register</button>
                    </div>
                    <p>Already have an account? <a href="login.php">Login Here</a></p>
                </form>
            </div>

            <!-- Left side image -->
            <div class="auth-image">
                <img src="https://indiater.com/wp-content/uploads/2019/11/food-banner-design-template-free-psd-download.jpg" alt="GetFed" class="image">
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include('../includes/footer.php'); ?>
</body>
</html>
