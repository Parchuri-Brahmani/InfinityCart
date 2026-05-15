<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
$user_email = $_SESSION['user'];

$user_q = mysqli_query($conn, "SELECT coins FROM users WHERE email = '$user_email'");
$user_data = mysqli_fetch_assoc($user_q);
$my_coins = $user_data['coins'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Rewards | Infinity Cart</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .reward-banner { background: linear-gradient(135px, #1ab394, #0d8a71); color: white; padding: 40px; border-radius: 12px; text-align: center; margin-top: 30px; }
        .coin-balance { font-size: 48px; font-weight: 800; margin: 10px 0; }
        .coupon-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; }
        .coupon-card { border: 2px dashed #ddd; padding: 20px; border-radius: 8px; text-align: center; background: #fff; }
    </style>
</head>
<body>
<div class="container">
    <a href="account.php" style="text-decoration:none; color:#1ab394;">← Back to Account</a>
    
    <div class="reward-banner">
        <h3>Total Infinity Coins</h3>
        <div class="coin-balance"><i class="fa fa-coins"></i> <?php echo $my_coins; ?></div>
        <p>1 Coin = ₹1.00 | Use these coins on your next purchase!</p>
    </div>

    <div class="coupon-grid">
        <div class="coupon-card">
            <h4 style="color:#1ab394;">NEWUSER50</h4>
            <p>Get ₹50 OFF on your first order</p>
            <button style="background:#222; color:#fff; border:none; padding:5px 15px; border-radius:4px;">Copy Code</button>
        </div>
        <div class="coupon-card">
            <h4 style="color:#1ab394;">FREE-SHIP</h4>
            <p>Free delivery on orders above ₹999</p>
            <button style="background:#222; color:#fff; border:none; padding:5px 15px; border-radius:4px;">Copy Code</button>
        </div>
    </div>
</div>
</body>
</html>