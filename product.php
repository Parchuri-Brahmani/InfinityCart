<?php
session_start();
include("php/db.php");

// 1. URL nundi Product ID ni teesukovadam
if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);

    if(!$product) {
        die("<div style='text-align:center; padding:50px;'><h1>Product not found!</h1><a href='index.php'>Go Home</a></div>");
    }
} else {
    header("Location: index.php");
    exit();
}

// Image handling logic
$image_data = $product['image'];
$final_image = (filter_var($image_data, FILTER_VALIDATE_URL)) ? $image_data : "images/" . $image_data;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fff; margin: 0; padding: 0; }
        .product-details-container { display: flex; gap: 50px; padding: 40px 20px; max-width: 1200px; margin: 0 auto; }
        
        .product-left { flex: 1.2; border: 1px solid #eee; padding: 20px; background: #fff; border-radius: 8px; text-align: center; }
        .product-left img { width: 100%; max-height: 500px; object-fit: contain; }
        
        .product-right { flex: 1; }
        .p-category { color: #1ab394; text-transform: uppercase; font-size: 13px; font-weight: 700; letter-spacing: 1px; }
        .p-title { font-size: 30px; font-weight: 700; margin: 10px 0; color: #111; }
        .p-price { font-size: 26px; color: #B12704; font-weight: 700; margin: 15px 0; }
        .p-desc { color: #444; line-height: 1.6; margin-bottom: 25px; font-size: 15px; }
        
        .qty-box { display: flex; gap: 15px; align-items: center; margin-bottom: 25px; }
        .qty-box input { width: 60px; padding: 8px; text-align: center; border: 1px solid #ddd; border-radius: 4px; }
        
        .add-cart-btn { background: #1ab394; color: white; border: none; padding: 12px 30px; font-size: 15px; font-weight: 600; cursor: pointer; border-radius: 5px; transition: 0.3s; }
        .add-cart-btn:hover { background: #148e75; }
        
        .btn-wish { padding: 11px 20px; border: 1px solid #1ab394; color: #1ab394; background: transparent; border-radius: 5px; cursor: pointer; font-weight: 600; transition: 0.2s; }
        .btn-wish:hover { background: #f0fdfa; }

        /* Review Styles */
        .review-card { background: #fdfdfd; padding: 15px; border-radius: 8px; border: 1px solid #f1f1f1; margin-bottom: 15px; }
        .stars i { font-size: 12px; margin-right: 2px; }
    </style>
</head>
<body>

<div class="product-details-container">
    <div class="product-left">
        <img src="<?php echo $final_image; ?>" 
             onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600'" 
             alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>

    <div class="product-right">
        <span class="p-category"><?php echo htmlspecialchars($product['category']); ?></span>
        <h1 class="p-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        
        <div class="rating" style="color: #ffb400; margin-bottom: 15px;">
            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa-regular fa-star"></i>
            <span style="color:#888; font-size:13px; margin-left:10px;">(Verified Purchase)</span>
        </div>

        <div class="p-price">₹<?php echo number_format($product['price'], 2); ?></div>
        
        <div class="p-desc">
            <?php echo !empty($product['description']) ? htmlspecialchars($product['description']) : "This is a premium quality product from our latest collection. Crafted with excellence to provide you the best experience."; ?>
        </div>

        <div class="action-btns">
            <form action="add_to_cart.php" method="POST" style="display: inline-block;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div class="qty-box">
                    <label style="font-weight: 600;">Qty:</label>
                    <input type="number" name="qty" value="1" min="1">
                </div>
                <button type="submit" class="add-cart-btn">
                    <i class="fa fa-cart-plus"></i> ADD TO CART
                </button>
            </form>

            <form action="add_to_wishlist.php" method="POST" style="display: inline-block; margin-left: 10px;">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit" class="btn-wish">
                    <i class="fa-regular fa-heart"></i> WISHLIST
                </button>
            </form>
        </div>

        <div style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 15px;">
            <p style="font-size:13px; color:#666;"><strong>SKU:</strong> INF-<?php echo $product['id']; ?></p>
            <p style="font-size:13px; color:#666;"><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
        </div>

        <div class="reviews-section" style="margin-top: 40px; border-top: 2px solid #f4f4f4; padding-top: 20px;">
            <h3 style="font-size: 20px; margin-bottom: 20px;">Customer Reviews</h3>
            
            <?php
            $p_id = (int)$_GET['id'];
            // Safe Query
            $rev_q = mysqli_query($conn, "SELECT * FROM reviews WHERE product_id = $p_id ORDER BY created_at DESC");
            
            // FIX: Argument #1 ($result) check to avoid Fatal Error
            if($rev_q && mysqli_num_rows($rev_q) > 0):
                while($rev = mysqli_fetch_assoc($rev_q)):
            ?>
                <div class="review-card">
                    <div class="stars" style="color: #f1c40f; margin-bottom: 5px;">
                        <?php for($i=1; $i<=5; $i++) {
                            echo ($i <= $rev['rating']) ? '<i class="fa fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                        } ?>
                    </div>
                    <strong style="font-size: 14px; color: #333;"><?php echo htmlspecialchars(explode('@', $rev['user_email'])[0]); ?></strong>
                    <p style="font-size: 14px; color: #555; margin: 8px 0;"><?php echo htmlspecialchars($rev['comment']); ?></p>
                    <small style="color: #bbb;"><?php echo date('d M Y', strtotime($rev['created_at'])); ?></small>
                </div>
            <?php endwhile; else: echo "<p style='color:#888;'>No reviews yet. Be the first to share your experience!</p>"; endif; ?>

            <?php if(isset($_SESSION['user'])): ?>
            <div class="add-review" style="background: #fdfdfd; padding: 20px; border: 1px dashed #ddd; border-radius: 8px; margin-top: 25px;">
                <h4 style="margin-top:0;">Write a Review</h4>
                <form action="php/add_review.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $p_id; ?>">
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 14px;">Your Rating: </label>
                        <select name="rating" required style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Very Bad</option>
                        </select>
                    </div>
                    <textarea name="comment" placeholder="Tell others what you think about this product..." required style="width: 100%; height: 70px; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; box-sizing: border-box;"></textarea>
                    <button type="submit" style="background: #222; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 14px;">Submit Review</button>
                </form>
            </div>
            <?php else: ?>
                <p style="font-size: 13px; color: #666; background: #f9f9f9; padding: 10px; border-radius: 4px;">Please <a href="login.php" style="color: #1ab394; font-weight: 600;">Login</a> to write a review.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>