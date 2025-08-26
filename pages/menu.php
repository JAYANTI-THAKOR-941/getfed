<?php
session_start();

// Database connection
include('../config/db_connection.php');

// Get search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch unique categories (Only one entry per category)
$categoryQuery = "SELECT DISTINCT category, image_url FROM food_products WHERE availability = 1";
$categoryResult = mysqli_query($conn, $categoryQuery);

// Base query for food products
$query = "SELECT p.id, p.name, p.description, p.price, p.image_url, p.category 
          FROM food_products p 
          WHERE p.availability = 1";

// Apply search filter
if ($searchQuery) {
    $query .= " AND (p.name LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%' 
                 OR p.description LIKE '%" . mysqli_real_escape_string($conn, $searchQuery) . "%')";
}

// Apply category filter
if ($categoryFilter) {
    $query .= " AND p.category = '" . mysqli_real_escape_string($conn, $categoryFilter) . "'";
}

// Fetch filtered products
$result = mysqli_query($conn, $query);

// Store categories in an array to prevent duplicates
$categories = [];
while ($category = mysqli_fetch_assoc($categoryResult)) {
    $categories[$category['category']] = $category['image_url'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products - Food Items</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 40px;
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }

        .menu-header {
            background: linear-gradient(to bottom right, rgba(18, 16, 15, 0.8), rgba(10, 8, 8, 0.7)), 
            url('https://t4.ftcdn.net/jpg/02/61/88/57/360_F_261885799_wChAE2Eseb3sGtTNcz1nvi0V51p6mcMZ.jpg') no-repeat center center/cover;
            padding: 120px 0;
            text-align: center;
            color: #fff;
        }

        .menu-header h1 {
            margin: 0;
            font-size: 48px;
        }
        .menu-header p{
            font-size: 25px;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .search-box {
            width: 350px;
            display: flex;
            border-radius: 50px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        .search-box button {
            padding: 12px 15px;
            background-color: #2ecc71;
            border: none;
            cursor: pointer;
            color: #fff;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .search-box button:hover {
            background-color: #27ae60;
        }

        .category-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .category-item {
            text-align: center;
            margin: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .category-item:hover {
            transform: scale(1.1);
        }

        .category-item img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2ecc71;
        }

        .category-item p {
            margin-top: 8px;
            font-size: 14px;
            color: #333;
        }

        .product-container {
            display: flex;
            justify-content: center;
            margin: 40px 0;
            flex-wrap: wrap;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 40px;
            width: 80%;
        }

        .product-card {
            padding: 20px;
            width: 320px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        .product-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-card .details {
            padding: 2px 15px;
            text-align: center;
        }

        .product-card .details h3 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .product-card .details p {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .product-card .details .price {
            font-size: 16px;
            color: #2ecc71;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .product-card .details .btn {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .product-card .details .btn:hover {
            background-color: #27ae60;
        }

        /* Style for the switch buttons */
        .filter-buttons {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .filter-button {
            display: flex;
            align-items: center;
            margin: 0 15px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .filter-button:hover {
            transform: scale(1.1);
        }

        .filter-button img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2ecc71;
        }
        a{
            text-decoration: none;
        }
        .filter-button p {
            margin-left: 10px;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
<?php include('../includes/header.php'); ?>

    <div class="menu-header">
        <h1>Explore Our Food Products</h1>
        <p>Fresh, Healthy, and Full of Flavor</p>
    </div>

    <div class="search-bar">
        <form action="" method="GET" class="search-box">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search for food products..." />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Filter Buttons for "Switch to All Food Items" and "Clear Filter" -->
   

    <div class="category-container">
        <a href="?<?php echo $searchQuery ? 'search=' . urlencode($searchQuery) . '&' : '';  ?>" class="category-item">
            <img src="../assets/images/chana Salad.jpg" alt="All Food Items">
            <p>All</p>
        </a>
    
        <?php foreach ($categories as $category => $image_url): ?>
            <a href="?category=<?php echo urlencode($category); ?>" class="category-item">
                <img src="../assets/images/<?php echo $image_url; ?>" alt="<?php echo $category; ?>">
                <p><?php echo $category; ?></p>
            </a>
        <?php endforeach; ?>
        
    </div>

    <div class="product-container">
        <div class="product-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <img src="../assets/images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
                        <div class="details">
                            <h3><?php echo $product['name']; ?></h3>
                            <p><?php echo substr($product['description'], 0, 100); ?>...</p>
                            <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                            <a href="description.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center;">No products found.</p>
            <?php endif; ?>
        </div>
    </div>

<?php include('../includes/footer.php'); ?>
</body>
</html>
