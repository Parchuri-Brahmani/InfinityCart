<?php
session_start();
include("php/db.php");
if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
$user_email = $_SESSION['user'];

// Card Fetching
$cards_res = mysqli_query($conn, "SELECT * FROM user_payments WHERE user_email = '$user_email'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Payments | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #fff; }
        .pay-container { max-width: 900px; margin: 0 auto; }
        .card-box { border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-top: 20px; display: flex; align-items: center; justify-content: space-between; }
        .card-info { display: flex; align-items: center; gap: 20px; }
        .card-icon { font-size: 30px; color: #232f3e; }
        .add-card-btn { background: #ffd814; border: 1px solid #fcd200; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; color: #000; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
<div class="pay-container">
    <p style="font-size: 14px;"><a href="account.php" style="color: #007185; text-decoration: none;">Your Account</a> › Your Payments</p>
    <h1>Your Wallet</h1>
    
    <h3>Cards & Accounts</h3>
    <?php if(mysqli_num_rows($cards_res) > 0): ?>
        <?php while($card = mysqli_fetch_assoc($cards_res)): ?>
            <div class="card-box">
                <div class="card-info">
                    <i class="fa fa-credit-card card-icon"></i>
                    <div>
                        <strong><?php echo $card['card_type']; ?> ending in <?php echo substr($card['card_number'], -4); ?></strong>
                        <p style="margin: 5px 0 0; color: #555; font-size: 14px;">Name on card: <?php echo $card['card_name']; ?></p>
                    </div>
                </div>
                <a href="#" style="color: #007185; text-decoration: none; font-size: 14px;">Remove</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: #555;">No saved cards found.</p>
    <?php endif; ?>

    <a href="#" class="add-card-btn">Add a payment method</a>
</div>
</body>
</html>