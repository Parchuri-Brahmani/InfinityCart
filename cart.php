<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Delete Logic
if(isset($_GET['remove'])){
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id' AND user_email = '$user'");
    header("Location: cart.php");
}

$query = "SELECT cart.id as cart_id, products.name, products.price, products.image, cart.quantity 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_email = '$user'";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .cart-item {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .cart-img {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border: 1px solid #f9f9f9;
        }
        .cart-info { flex: 1; }
        .cart-info h3 { margin: 0 0 10px 0; font-size: 18px; }
        .cart-actions {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        .action-link {
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            color: #888;
            transition: 0.3s;
        }
        .action-link:hover { color: #1ab394; }
        .remove-link:hover { color: #e74c3c; }
        .cart-summary {
            background: #fdfdfd;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
            border-top: 2px solid #1ab394;
        }
    </style>
</head>
<body>

<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <h2 style="margin-bottom: 30px;">Shopping Cart (<?php echo mysqli_num_rows($result); ?> Items)</h2>

    <?php if(mysqli_num_rows($result) > 0): ?>
        <div class="cart-list">
            <?php while($row = mysqli_fetch_assoc($result)): 
                $img_path = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : "images/" . $row['image'];
            ?>
                <div class="cart-item">
                    <img src="<?php echo $img_path; ?>" class="cart-img" onerror="this.src='https://via.placeholder.com/150'">
                    
                    <div class="cart-info">
                        <h3><?php echo $row['name']; ?></h3>
                        <p style="color: #1ab394; font-weight: 700; font-size: 18px;">₹<?php echo number_format($row['price'], 2); ?></p>
                        <p style="font-size: 14px; color: #666;">Qty: <?php echo $row['quantity']; ?></p>
                        
                        <div class="cart-actions">
                            <a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="action-link remove-link" onclick="return confirm('Are you sure?')">
                                <i class="fa fa-trash"></i> REMOVE
                            </a>
                            <a href="#" class="action-link">
                                <i class="fa fa-heart"></i> MOVE TO WISHLIST
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="cart-summary">
            <a href="index.php" style="margin-right: 20px; color: #666; text-decoration: none;">Continue Shopping</a>
            <a href="checkout.php">
                <button class="btn-dark" style="background: #1ab394; color: white; border: none; padding: 15px 40px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                    PROCEED TO CHECKOUT <i class="fa fa-chevron-right"></i>
                </button>
            </a>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <p>Your cart is empty.</p>
            <a href="index.php" style="color: #1ab394; font-weight: 600;">Start Shopping</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>