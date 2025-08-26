<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>Home - GetFed Healthy Food</title>
    <style>
        /* Hero Section Styles */
        .hero {
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom right, rgba(14, 14, 14, 0.8), rgba(11, 11, 11, 0.8)), url('https://content.app-sources.com/s/47840344112423227/uploads/Images/Untitled_design_2-3715088.png?format=webp') no-repeat center center/cover;
            color: #fff;
            text-align: center;
        }

        .hero-container h1 {
            font-size: 50px;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hero-container p {
            font-size: 18px;
            margin: 20px 0;
        }

        .btn-order-now {
            text-decoration: none;
            padding: 12px 25px;
            background-color: #2ecc71;
            color: #fff;
            font-size: 18px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-order-now:hover {
            background-color: #27ae60;
        }

        /* Card Style Section */
        .section-container {
            background-color: #fff;
            padding: 50px 0;
            text-align: center;
        }

        .section-container h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }

        .section-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #666;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .card {
            width: 250px;
            /* background: #f9f9f9; */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card strong {
            display: block;
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #27ae60;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-container">
                <h1>Welcome to GetFed Healthy Food</h1>
                <p>Enjoy nutritious and delicious meals delivered fresh to your door.</p>
                <a href="/getfed/pages/menu.php" class="btn-order-now">Order Now</a>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="section-container">
            <h2>Why Choose Us?</h2>
            <p>At GetFed, we prioritize your health and taste preferences by offering fresh, wholesome meals prepared with love.</p>
            <div class="card-container">
                <div class="card">
                    <strong>Fresh Ingredients</strong>
                    Only the best, locally sourced organic produce.
                </div>
                <div class="card">
                    <strong>Healthy & Tasty</strong>
                    Meals crafted to balance nutrition and flavor.
                </div>
                <div class="card">
                    <strong>Convenience</strong>
                    Fresh food delivered straight to your doorstep.
                </div>
                <div class="card">
                    <strong>Sustainable Practices</strong>
                    Eco-friendly packaging and responsible sourcing.
                </div>
            </div>
        </section>

        <!-- Core Values Section -->
        <section class="section-container">
            <h2>Our Core Values</h2>
            <p>At GetFed, our mission is to provide healthy food with integrity and care:</p>
            <div class="card-container">
                <div class="card">
                    <strong>Quality</strong>
                    We never compromise on freshness and nutrition.
                </div>
                <div class="card">
                    <strong>Innovation</strong>
                    Constantly improving our menu with exciting new recipes.
                </div>
                <div class="card">
                    <strong>Customer First</strong>
                    Your health and satisfaction are our top priorities.
                </div>
                <div class="card">
                    <strong>Sustainability</strong>
                    Committed to reducing waste and promoting a greener planet.
                </div>
            </div>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>
</body>
</html>
