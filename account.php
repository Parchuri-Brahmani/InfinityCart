<?php
session_start();
include("php/db.php");

// 1. Login Check - User login avvakapothe login page ki pampisthundhi
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user'];

// 2. Dynamic Cart Count Logic
$cart_count = 0;
$count_res = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_email = '$user_email'");
if($count_res) {
    $count_data = mysqli_fetch_assoc($count_res);
    $cart_count = ($count_data && $count_data['total']) ? $count_data['total'] : 0;
}

// 3. Get User Name from Email (Optional: Displaying only the part before @)
$display_name = explode('@', $user_email)[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fff; margin: 0; padding: 0; }
        
        /* Account Specific Styles */
        .account-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        .account-header { margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .account-header h1 { font-size: 28px; font-weight: 500; margin: 0; color: #111; }
        .account-header p { color: #555; margin: 5px 0 0 0; }
        
        .account-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 20px; 
        }
        
        @media (max-width: 992px) { .account-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .account-grid { grid-template-columns: 1fr; } }

        .account-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border: 1px solid #d5d9d9;
            border-radius: 8px;
            text-decoration: none;
            color: #111;
            transition: 0.2s ease;
            background: #fff;
        }
        
        .account-card:hover { background-color: #f7f7f7; border-color: #adb1b1; transform: translateY(-2px); }
        
        .card-icon { width: 50px; height: 50px; object-fit: contain; flex-shrink: 0; }
        
        .card-text h3 { font-size: 17px; font-weight: 600; margin: 0 0 5px 0; color: #111; }
        .card-text p { font-size: 13px; color: #565959; margin: 0; line-height: 1.4; }
        
        .sign-out-link { display: block; text-align: center; margin-top: 50px; color: #007185; text-decoration: none; font-weight: 700; font-size: 14px; padding-bottom: 50px; }
        .sign-out-link:hover { text-decoration: underline; color: #c45500; }

        /* Header Fixes */
        .header-main { border-bottom: 1px solid #ddd; background: #fff; }
        .count { position: absolute; top: -10px; right: -8px; background: #1ab394; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold; }
    </style>
</head>
<body>

<header class="header-main">
    <div class="container flex-between" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px;">
        <div class="logo">
            <a href="index.php" style="text-decoration:none; color:inherit; font-size: 24px; font-weight: 800;">Infinity<span>Cart.</span></a>
        </div>
        
        <div class="header-icons" style="display: flex; gap: 25px;">
            <a href="index.php" style="text-decoration:none; color: #333; text-align: center;">
                <div class="h-icon">
                    <i class="fa fa-house" style="font-size: 20px;"></i>
                    <span style="display: block; font-size: 12px; margin-top: 4px;">Home</span>
                </div>
            </a>
            <a href="cart.php" style="text-decoration:none; color: #333; text-align: center; position: relative;">
                <div class="h-icon">
                    <i class="fa fa-bag-shopping" style="font-size: 20px;"></i>
                    <?php if($cart_count > 0): ?>
                        <span class="count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                    <span style="display: block; font-size: 12px; margin-top: 4px;">Cart</span>
                </div>
            </a>
        </div>
    </div>
</header>

<div class="account-container">
    <div class="account-header">
        <h1>Your Account</h1>
        <p>Hello, <strong><?php echo htmlspecialchars($display_name); ?></strong> (<?php echo htmlspecialchars($user_email); ?>)</p>
    </div>
    
    <div class="account-grid">
        <a href="my_orders.php" class="account-card">
            <img src="https://m.media-amazon.com/images/G/01/x-locale/cs/help/images/gateway/self-service/order._CB660668735_.png" class="card-icon" alt="Orders">
            <div class="card-text">
                <h3>Your Orders</h3>
                <p>Track, return, or buy things again</p>
            </div>
        </a>

        <a href="edit_profile.php" class="account-card">
            <img src="https://m.media-amazon.com/images/G/01/x-locale/cs/help/images/gateway/self-service/security._CB659600413_.png" class="card-icon" alt="Security">
            <div class="card-text">
                <h3>Login & Security</h3>
                <p>Edit login, name, and mobile number</p>
            </div>
        </a>

        <a href="rewards.php" class="account-card">
            <img src="https://img.icons8.com/fluency/96/gift.png" class="card-icon" alt="Rewards">
            <div class="card-text">
                <h3>Your Rewards</h3>
                <p>View balance, coupons, and points</p>
            </div>
        </a>

        <a href="prime.php" class="account-card">
           <img src="https://img.icons8.com/fluency/96/crown.png" class="card-icon" alt="Prime">
            <div class="card-text">
                <h3>Prime</h3>
                <p>View benefits and settings</p>
            </div>
        </a>

        <a href="addresses.php" class="account-card">
            <img src="https://m.media-amazon.com/images/G/01/x-locale/cs/help/images/gateway/self-service/contact_us._CB659962323_.png" class="card-icon" alt="Address">
            <div class="card-text">
                <h3>Your Addresses</h3>
                <p>Edit addresses for orders</p>
            </div>
        </a>

        <a href="payment_settings.php" class="account-card">
            <img src="https://m.media-amazon.com/images/G/01/x-locale/cs/help/images/gateway/self-service/payment._CB660619053_.png" class="card-icon" alt="Payment">
            <div class="card-text">
                <h3>Payment Options</h3>
                <p>Edit or add payment methods</p>
            </div>
        </a>
    </div>

    <a href="logout.php" class="sign-out-link">Not <?php echo htmlspecialchars($display_name); ?>? Sign Out</a>
</div>

</body>
</html>