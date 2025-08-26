<?php
// Start the session only if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title>About Us - GetFed Healthy Food</title>
    <style>
        /* About Us Header Image Section */
        .about-header {
            background: linear-gradient(to bottom right, rgba(27, 19, 18, 0.7), rgba(51, 51, 51, 0.7)), 
                        url('https://img.freepik.com/free-photo/vegetables-with-salt-corn-cob_1220-688.jpg') no-repeat center center/cover;
            height: 400px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }

        .about-header-content h1 {
            font-size: 3.5rem;
            margin: 0;
            font-weight: 600;
        }

        .about-header-content p {
            font-size: 1.5rem;
            margin-top: 10px;
            font-weight: 300;
        }

        /* Media Queries for Mobile Responsiveness */
        @media screen and (max-width: 768px) {
            .about-header {
                height: 300px;
            }

            .about-header-content h1 {
                font-size: 2.5rem;
            }

            .about-header-content p {
                font-size: 1.2rem;
            }
        }

        /* About Us Main Content */
        .about-us {
            padding: 40px 0;
            background-color: #f9f9f9;
        }

        .about-us h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .about-us p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.8;
        }

        .about-us ul {
            list-style-type: none;
            padding-left: 0;
        }

        .about-us ul li {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #555;
        }

        .about-us ul li strong {
            color: #333;
        }

        /* Why Choose Us and Core Values Cards Section */
        .why-choose-us {
            background-color: #f8f8f8;
            padding: 30px 0;
        }

        .card-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 22%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 1rem;
        }

    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <!-- About Header Image Section -->
    <section class="about-header">
        <div class="about-header-content">
            <h1>About GetFed Healthy Food</h1>
            <p>Your destination for delicious, nutritious, and balanced meals delivered to your door.</p>
        </div>
    </section>

    <!-- About Us Main Content -->
    <section class="about-us">
        <div class="container">
            <h2>Our Story</h2>
            <p>GetFed Healthy Food was founded with the aim to provide delicious and nutritious food that supports your health goals. We believe that eating healthy shouldn't be a compromise between taste and nutrition. From fresh salads to wholesome meals, GetFed offers a variety of options to help you maintain a balanced diet while enjoying the flavors you love.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to make healthy eating easy, convenient, and enjoyable. We are committed to using only the freshest ingredients and ensuring that every meal we serve is packed with essential nutrients to fuel your day.</p>

            <h2>What Makes Us Different?</h2>
            <ul>
                <li><strong>Fresh Ingredients:</strong> We use only the finest ingredients in all our meals, ensuring freshness and taste.</li>
                <li><strong>Balanced Meals:</strong> Our meals are designed to give you the right balance of proteins, carbs, and healthy fats.</li>
                <li><strong>Convenience:</strong> With GetFed, eating healthy has never been more convenient with delivery right to your door.</li>
                <li><strong>Customization:</strong> We offer customizable meal plans to fit your specific dietary needs and preferences.</li>
            </ul>

            <h2>Our Values</h2>
            <p>At GetFed, we believe in promoting wellness, sustainability, and community. Our core values guide everything we do, from the meals we prepare to the service we provide.</p>
        </div>
    </section>

    <!-- Why Choose Us and Core Values Cards Section -->
    <section class="why-choose-us">
        <div class="container">
            <div class="card-container">
                <div class="card">
                    <h3>Fresh Ingredients</h3>
                    <p>We source only the highest quality ingredients for our meals, ensuring freshness in every bite.</p>
                </div>
                <div class="card">
                    <h3>Balanced Meals</h3>
                    <p>Every meal is carefully crafted to provide you with the perfect balance of nutrients for a healthy lifestyle.</p>
                </div>
                <div class="card">
                    <h3>Convenience</h3>
                    <p>Healthy eating has never been easier with our meal delivery service, bringing nutritious meals straight to your door.</p>
                </div>
                <div class="card">
                    <h3>Customization</h3>
                    <p>Choose from a variety of meal options and customize them to fit your dietary needs and preferences.</p>
                </div>
            </div>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
