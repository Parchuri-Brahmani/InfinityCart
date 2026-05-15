<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
$user_email = $_SESSION['user'];

// Address Fetching
$address_q = mysqli_query($conn, "SELECT * FROM user_addresses WHERE user_email = '$user_email'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Addresses | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #fff; margin: 0; padding: 20px; }
        .addr-container { max-width: 1000px; margin: 0 auto; }
        .addr-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
        
        .add-new-box { 
            border: 2px dashed #bbb; border-radius: 8px; height: 250px; 
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            cursor: pointer; color: #777; text-decoration: none;
        }
        
        .address-card { border: 1px solid #ddd; border-radius: 8px; padding: 20px; height: 210px; position: relative; }
        .address-card h4 { margin: 0 0 10px 0; font-size: 16px; }
        .address-card p { font-size: 14px; margin: 3px 0; color: #333; }
        
        .card-actions { position: absolute; bottom: 15px; left: 20px; font-size: 14px; }
        .card-actions a { color: #007185; text-decoration: none; margin-right: 15px; }
    </style>
</head>
<body>

<div class="addr-container">
    <p style="font-size: 14px;"><a href="account.php" style="color: #007185; text-decoration: none;">Your Account</a> › Your Addresses</p>
    <h1>Your Addresses</h1>

    <div class="addr-grid">
        <a href="add_address.php" class="add-new-box">
            <i class="fa fa-plus" style="font-size: 40px; margin-bottom: 10px;"></i>
            <h3>Add Address</h3>
        </a>

        <?php while($addr = mysqli_fetch_assoc($address_q)): ?>
            <div class="address-card">
                <?php if($addr['is_default']) echo '<p style="font-size: 12px; color: #777;">Default: <img src="https://m.media-amazon.com/images/G/01/x-locale/common/orange_logo_sml._CB485933393_.gif" style="vertical-align: middle;"></p>'; ?>
                <h4><?php echo htmlspecialchars($addr['full_name']); ?></h4>
                <p><?php echo htmlspecialchars($addr['house_no']); ?>, <?php echo htmlspecialchars($addr['area']); ?></p>
                <p><?php echo htmlspecialchars($addr['city']); ?>, <?php echo htmlspecialchars($addr['state']); ?> - <?php echo htmlspecialchars($addr['pincode']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($addr['mobile']); ?></p>
                
                <div class="card-actions">
                    <a href="#">Edit</a> | <a href="#">Remove</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>