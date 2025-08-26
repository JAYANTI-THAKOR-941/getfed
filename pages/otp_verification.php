<?php
session_start();
include('../config/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    if ($entered_otp == $_SESSION['otp']) {
        $username = $_SESSION['username'];
        $email = $_SESSION['email'];
        $hashed_password = $_SESSION['password'];

        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Set user session as logged in
            $_SESSION['user_id'] = mysqli_insert_id($conn); 
            $_SESSION['username'] = $username;

            unset($_SESSION['otp']);
            unset($_SESSION['password']); 

            // Redirect to the home page
            header('Location: ../index.php');
            exit;
        } else {
            $error_message = "Error completing registration!";
        }
    } else {
        $error_message = "Invalid OTP! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>OTP Verification - GetFed</title>
</head>
<body>
    <!-- Include Header -->
    <?php include('../includes/header.php'); ?>

    <!-- OTP Verification Form Section -->
    <section class="auth-section">
        <div class="auth-container">

            <!-- Right side form -->
            <div class="auth-form">
                <h2>OTP Verification</h2>

                <!-- Display error message if any -->
                <?php if (isset($error_message)) : ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>  

                <form action="otp_verification.php" method="POST">
                    <div class="input-group">
                        <label for="otp">Enter OTP</label>
                        <input type="text" id="otp" name="otp" required>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="verify_otp" class="btn">Verify OTP</button>
                    </div>
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
