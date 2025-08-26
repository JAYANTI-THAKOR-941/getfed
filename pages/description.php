<?php 
include('../config/db_connection.php');
session_start();

// Get product ID from query parameter  
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$productQuery = "SELECT * FROM food_products WHERE id = $productId";
$productResult = mysqli_query($conn, $productQuery);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    echo "<p>Product not found!</p>";
    exit;
}

// Handle Review Submission
if (isset($_POST['submit_review'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $rating = (int)$_POST['rating'];
    $reviewText = mysqli_real_escape_string($conn, $_POST['review_text']);

    // Insert review into database
    $insertReviewQuery = "INSERT INTO reviews (product_id, username, rating, review_text) 
                          VALUES ($productId, '$username', $rating, '$reviewText')";
    
    if (mysqli_query($conn, $insertReviewQuery)) {
        // Optionally, you can add a success message here
    } else {
        echo "<p>Error submitting review: " . mysqli_error($conn) . "</p>";
    }
}

// Fetch product reviews
$reviewQuery = "SELECT * FROM reviews WHERE product_id = $productId";
$reviewResult = mysqli_query($conn, $reviewQuery);

if (!$reviewResult) {
    die("Error fetching reviews: " . mysqli_error($conn));
}

$reviews = mysqli_fetch_all($reviewResult, MYSQLI_ASSOC);

// Function to display stars for rating
function displayStars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $stars .= '★'; // filled star
        } else {
            $stars .= '☆'; // empty star
        }
    }
    return $stars;
}

// Handle the Add to Cart logic
if (isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Initialize cart session if not already done
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Add the product to the cart
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'image_url' => $product['image_url']
        ];
    } else {
        $_SESSION['cart'][$productId]['quantity'] += $quantity;
    }

    // Redirect to the cart page
    header("Location: cart.php");
    exit;
}

// Split benefits into an array
$benefits = explode(',', $product['benefits']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <title><?php echo $product['name']; ?> - Healthy Food</title>
    <style>
        /* Product Page Container */
        .product-page {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Header Section */
        .product-header {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Product Image */
        .product-images {
            flex: 1;
            max-width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product-images img {
            width: 300px;
            border-radius: 8px;
            cursor: pointer;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-images img:hover {
            transform: scale(1.05);
        }

        /* Product Details */
        .product-details {
            flex: 1;
            max-width: 800px;
            text-align: left;
        }

        .product-details h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-details .price {
            font-size: 26px;
            color: #2ecc71;
            font-weight: bold;
            margin: 10px 0;
        }

        .product-description {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            line-height: 1.6;
            color: #555;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Benefits List */
        .benefits {
            margin-top: 20px;
            border-radius: 8px;
            
        }

        .benefits li {
            font-size: 16px;
            margin-bottom: 10px;
            text-transform: capitalize;
            margin-left: 4%;
            font-weight: bold;
        }

        /* Buttons */
        .product-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .product-actions button {
            padding: 12px 25px;
            background-color: #2ecc71;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 200px;
        }

        .product-actions button:hover {
            background-color: #2ecc71;
        }

        /* Reviews Section */
        .reviews {
            margin-top: 40px;
        }

        .reviews h2 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .review {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .review .reviewer {
            font-weight: bold;
        }

        .review .review-text {
            margin-top: 5px;
            font-style: italic;
        }

        /* Add Review Form */
        .add-review {
            margin-top: 40px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
        }

        .add-review h3 {
            font-size: 22px;
            margin-bottom: 15px;
        }

        .add-review label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .add-review input,
        .add-review select,
        .add-review textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-review textarea {
            resize: vertical;
        }

        .add-review .btn-review {
            padding: 12px 25px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .add-review .btn-review:hover {
            background-color: #2ecc71;
        }

        /* Star Rating */
        .reviewer span {
            font-size: 20px;
            color: #f04e31;
        }
    </style>
</head>
<body>
    <?php include('../includes/header.php'); ?>

    <div class="product-page">
        <div class="product-header">
            <div class="product-images">
                <img src="../assets/images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
            </div>
            <div class="product-details">
                <h1><?php echo $product['name']; ?></h1>
                <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
                <p><?php echo $product['description']; ?></p>

                <!-- Benefits List -->
                <h1>Benefits</h1>
                <ul class="benefits">
                    <?php foreach ($benefits as $benefit): ?>
                        <li><?php echo htmlspecialchars(trim($benefit)); ?></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Add to Cart Form -->
                <div class="product-actions">
                    <form action="description.php?id=<?php echo $productId; ?>" method="POST">
                        <button type="submit" name="add_to_cart" class="btn-cart">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Reviews -->
        <div class="reviews">
            <h2>Customer Reviews</h2>
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review">
                        <p class="reviewer"><?php echo $review['username']; ?> <span>- <?php echo displayStars($review['rating']); ?></span></p>
                        <p class="review-text"><?php echo $review['review_text']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews yet. Be the first to review!</p>
            <?php endif; ?>
        </div>

        <!-- Add Review Form -->
        <div class="add-review">
            <h3>Add Your Review</h3>
            <form action="description.php?id=<?php echo $productId; ?>" method="POST">
                <label for="username">Your Name:</label>
                <input type="text" name="username" id="username" required>

                <label for="rating">Rating:</label>
                <select name="rating" id="rating" required>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>

                <label for="review_text">Your Review:</label>
                <textarea name="review_text" id="review_text" rows="4" required></textarea>

                <button type="submit" name="submit_review" class="btn-review">Submit Review</button>
            </form>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
