<?php
session_start();
include("php/db.php");

// 1. User login check
if(!isset($_SESSION['user'])){
    echo "<script>alert('Please login to view your wishlist!'); window.location.href='login.php';</script>";
    exit();
}

$user = $_SESSION['user'];

// 2. Remove from Wishlist logic
if(isset($_GET['remove'])){
    $prod_id = mysqli_real_escape_string($conn, $_GET['remove']);
    mysqli_query($conn, "DELETE FROM wishlist WHERE product_id = '$prod_id' AND user_email = '$user'");
    header("Location: wishlist.php");
}


// 3. Updated Query (Joining with products table)
// Query updated for better clarity
$query = "SELECT p.id, p.name, p.price, p.image 
          FROM wishlist w
          JOIN products p ON w.product_id = p.id 
          WHERE w.user_email = '$user'";
$result = mysqli_query($conn, $query);

// Error handling to prevent Fatal Error
if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .wishlist-hero { background: #f9f9f9; padding: 50px 0; text-align: center; border-bottom: 1px solid #eee; }
        .wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 25px; margin: 50px 0; }
        .wish-card { background: #fff; border: 1px solid #eee; padding: 20px; border-radius: 8px; text-align: center; position: relative; }
        .wish-card img { width: 100%; height: 200px; object-fit: contain; margin-bottom: 15px; }
        .remove-btn { position: absolute; top: 10px; right: 10px; color: #ddd; cursor: pointer; font-size: 20px; }
        .remove-btn:hover { color: #e74c3c; }
        .view-btn { display: block; background: #1ab394; color: #fff; padding: 12px; border-radius: 5px; text-decoration: none; font-weight: 600; margin-top: 10px; }
    </style>
</head>
<body>

<div class="wishlist-hero">
    <div class="container">
        <h1 style="font-size: 36px;"><i class="fa-regular fa-heart"></i> My Wishlist</h1>
        <p style="color: #888;">Items you've saved for later</p>
    </div>
</div>

<div class="container">
    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="wishlist-grid">
            <?php while($row = mysqli_fetch_assoc($result)): 
                $img_url = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : "images/" . $row['image'];
            ?>
                <div class="wish-card">
                    <a href="wishlist.php?remove=<?php echo $row['id']; ?>" class="remove-btn" onclick="return confirm('Remove this item?')">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </a>
                    <img src="<?php echo $img_url; ?>" onerror="this.src='https://via.placeholder.com/200'">
                    <h3 style="font-size: 16px; margin-bottom: 10px;"><?php echo $row['name']; ?></h3>
                    <p style="color: #1ab394; font-weight: 700; font-size: 18px;">₹<?php echo number_format($row['price'], 2); ?></p>
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="view-btn">VIEW PRODUCT</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 100px 0;">
            <i class="fa-regular fa-heart" style="font-size: 80px; color: #eee; margin-bottom: 20px;"></i>
            <h2 style="color: #bbb;">Your wishlist is empty</h2>
            <a href="index.php" style="color: #1ab394; text-decoration: none; font-weight: 700;">Continue Shopping</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>