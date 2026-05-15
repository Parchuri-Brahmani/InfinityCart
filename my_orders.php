<?php
session_start();
include("php/db.php");

// 1. User login ayyaro ledho check cheyadam
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// 2. Orders fetch cheyadam - Ikada "id DESC" use chesthunnam so kotha orders paina vasthayi
$query = "SELECT * FROM orders WHERE user_email = '$user' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; }
        .orders-container { max-width: 900px; margin: 40px auto; padding: 20px; }
        .order-card { 
            background: white; 
            border-radius: 12px; 
            padding: 25px; 
            margin-bottom: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #eee;
        }
        .order-details h3 { margin: 0; color: #1ab394; font-size: 18px; }
        .order-details p { margin: 5px 0; color: #666; font-size: 14px; }
        .status-badge { 
            display: inline-block; 
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase;
            background: #e8f5f3; 
            color: #1ab394; 
        }
        .order-price { font-size: 22px; font-weight: 700; color: #222; }
        .btn-view { 
            text-decoration: none; 
            color: #1ab394; 
            border: 1px solid #1ab394; 
            padding: 8px 16px; 
            border-radius: 6px; 
            font-weight: 600; 
            transition: 0.3s;
        }
        .btn-view:hover { background: #1ab394; color: white; }
    </style>
</head>
<body>

<div class="orders-container">
    <h1 style="margin-bottom: 30px;"><i class="fa-solid fa-box"></i> My Orders</h1>

    <?php 
    // Important Fix: SQL query error unte 'result' boolean false avthundhi, adhi check chesthunnam
    if($result && mysqli_num_rows($result) > 0): 
        while($row = mysqli_fetch_assoc($result)): 
    ?>
        <div class="order-card">
            <div class="order-details">
                <h3>Order #ORD-<?php echo $row['id']; ?></h3>
<p>
    <i class="fa-regular fa-calendar"></i> 
    <?php 
        // Okavela 'order_date' lekapothe 'created_at' check chesthundhi
        $raw_date = $row['order_date'] ?? $row['created_at'] ?? date('Y-m-d H:i:s');
        echo date('d M Y', strtotime($raw_date)); 
    ?>
</p>                <div class="status-badge"><?php echo htmlspecialchars($row['status'] ?? 'Processing'); ?></div>
            </div>

            <div style="text-align: right;">
                <div class="order-price">₹<?php echo number_format($row['total_amount'], 2); ?></div>
                <div style="margin-top: 15px;">
                    <a href="invoice.php?order_id=<?php echo $row['id']; ?>" class="btn-view">
                        <i class="fa-solid fa-file-invoice"></i> Invoice
                    </a>
                </div>
            </div>
        </div>
    <?php 
        endwhile; 
    else: 
    ?>
        <div style="text-align: center; padding: 60px; background: white; border-radius: 12px; border: 1px dashed #ccc;">
            <i class="fa-solid fa-basket-shopping" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
            <h3>No orders found!</h3>
            <p>Ippudappude edhaina item shop chey.</p>
            <a href="shop.php" style="color: #1ab394; font-weight: 700; text-decoration: none;">Browse Products</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>