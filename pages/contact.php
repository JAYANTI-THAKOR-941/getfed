<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$errorMessage = '';
$successMessage = '';
$name = '';
$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate user inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jyantithakor941@gmail.com';
            $mail->Password = 'jpybygdivzrhyiqr';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('jyantithakor941@gmail.com');

            // HTML Email Content
            $htmlContent = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <style>
                    body, h1, p {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, sans-serif;
                    }
                    body {
                        background-color: #f4f4f4;
                        color: #555;
                        padding: 20px;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    }
                    .email-header {
                        background-color: #2ecc71;
                        color: white;
                        padding: 10px;
                        text-align: center;
                        border-radius: 8px;
                    }
                    .email-header h1 {
                        font-size: 24px;
                        margin: 0;
                    }
                    .email-body {
                        margin-top: 20px;
                        font-size: 16px;
                        line-height: 1.5;
                    }
                    .email-body p {
                        margin-bottom: 15px;
                    }
                    .email-footer {
                        margin-top: 30px;
                        text-align: center;
                        font-size: 14px;
                        color: #888;
                    }
                    .email-footer p {
                        margin-bottom: 5px;
                    }
                    .footer-link {
                        color: #2ecc71;
                        text-decoration: none;
                    }
                    .footer-link:hover {
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="email-header">
                        <h1>Contact Us Form Submission</h1>
                    </div>
                    <div class="email-body">
                        <p><strong>Name:</strong> ' . $name . '</p>
                        <p><strong>Email:</strong> ' . $email . '</p>
                        <p><strong>Message:</strong></p>
                        <p>' . nl2br($message) . '</p>
                    </div>
                    <div class="email-footer">
                        <p>Thank you for reaching out to us! We will get back to you shortly.</p>
                        <p>If you have any urgent questions, please contact us directly at <a href="mailto:info@getfet.com" class="footer-link">info@getfet.com</a></p>
                    </div>
                </div>
            </body>
            </html>';

            // Set email format to HTML
            $mail->isHTML(true);
            $mail->Subject = 'Contact Us Form Submission';
            $mail->Body = $htmlContent;

            // Send email
            $mail->send();
            $successMessage = "Thank you for your message! We will get back to you shortly.";

            // Clear the form after successful submission
            $name = '';
            $email = '';
            $message = '';
        } catch (Exception $e) {
            $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
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
    <title>Contact Us - GetFet</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Contact Header Section */
        .contact-header {
            background: linear-gradient(to bottom right, rgba(27, 19, 18, 0.8), rgba(16, 14, 14, 0.7)), 
                        url('https://t3.ftcdn.net/jpg/07/66/81/70/360_F_766817088_OwKIXeOtVyToYpvRfCyr6xPfyL9ffIvg.jpg') no-repeat center center/cover;
            color: white;
            text-align: center;
            padding: 100px 20px;
        }

        .contact-header h1 {
            font-size: 36px;
            margin: 0;
        }

        .contact-header p {
            font-size: 18px;
            margin-top: 10px;
        }

        /* Contact Us Section */
        .contact-us {
            padding: 50px 20px;
        }

        .contact-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
        }

        /* Contact Info */
        .contact-info {
            width: 100%;
            max-width: 400px;
        }

        .contact-info h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
        }

        .contact-info p {
            font-size: 16px;
            color: #555;
            margin-bottom: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .contact-item img {
            width: 24px;
            margin-right: 10px;
        }

        .contact-item p {
            font-size: 16px;
            color: #555;
        }

        /* Contact Form */
        .contact-form {
            flex: 1;
            max-width: 600px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-form h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .contact-form .input-group {
            margin-bottom: 20px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .contact-form textarea {
            resize: vertical;
        }

        .contact-form button {
            background-color: #2ecc71;
            color: white;
            font-size: 18px;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .contact-form button:hover {
            background-color: #27ae60;
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
            }

            .contact-info {
                max-width: 100%;
            }

            .contact-form {
                max-width: 100%;
            }
        }
    </style>
    <script>
        <?php if ($errorMessage) { ?>
            alert("Error: <?php echo $errorMessage; ?>");
        <?php } ?>
        <?php if ($successMessage) { ?>
            alert("Success: <?php echo $successMessage; ?>");
        <?php } ?>
    </script>
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <!-- Contact Header Section with Linear Gradient -->
    <section class="contact-header">
        <h1>Get in Touch with GetFet</h1>
        <p>We are here to assist you with anything you need!</p>
    </section>

    <!-- Contact Us Section -->
    <section class="contact-us">
        <div class="contact-container">
            <!-- Contact Information -->
            <div class="contact-info">
                <h2>Contact Information</h2>
                <p>We are always happy to hear from you! Reach out to us through any of the following means:</p>

                <div class="contact-item">
                    <img src="https://cdn-icons-png.freepik.com/512/2997/2997583.png" alt="Location Icon" />
                    <p>123 GetFet Street, City, State 12345</p>
                </div>
                <div class="contact-item">
                    <img src="https://cdn-icons-png.freepik.com/512/5610/5610987.png" alt="Phone Icon" />
                    <p>+1 (234) 567-890</p>
                </div>
                <div class="contact-item">
                    <img src="https://cdn-icons-png.freepik.com/512/724/724662.png" alt="Email Icon" />
                    <p>info@getfet.com</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="contact.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="name" placeholder="Your Name" value="<?php echo $name; ?>" required />
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Your Email" value="<?php echo $email; ?>"
                            required />
                    </div>
                    <div class="input-group">
                        <textarea name="message" placeholder="Your Message" rows="6"
                            required><?php echo $message; ?></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>

</html>