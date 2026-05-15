<?php
session_start();
include("php/db.php");

// 1. Login Check
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// 2. Cart total calculation
$query = "SELECT products.price, cart.quantity 
          FROM cart 
          JOIN products ON cart.product_id = products.id 
          WHERE cart.user_email = '$user'";
$result = mysqli_query($conn, $query);

$total = 0;
while($row = mysqli_fetch_assoc($result)) {
    $total += ($row['price'] * $row['quantity']);
}

// Okavela cart empty unte shop page ki pampisthundhi
if($total == 0) {
    header("Location: shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .checkout-flex { display: flex; gap: 40px; margin-top: 40px; }
        .billing-details { flex: 2; background: #fff; padding: 30px; border: 1px solid #eee; border-radius: 8px; }
        .order-summary { flex: 1; background: #f9f9f9; padding: 30px; border-radius: 8px; height: fit-content; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; }
        .form-group input, .form-group textarea { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; outline: none; box-sizing: border-box;
        }
        
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .total-row { font-size: 20px; font-weight: 700; color: #1ab394; border: none; }
        
        .place-order-btn { 
            width: 100%; background: #222; color: white; border: none; padding: 15px; 
            font-weight: 700; border-radius: 5px; cursor: pointer; transition: 0.3s; margin-top: 20px;
        }
        .place-order-btn:hover { background: #1ab394; }
        .pay-method:hover { background: #f9f9f9; }
    </style>
</head>
<body>

<div class="container" style="margin-bottom: 100px; max-width: 1100px; margin-left: auto; margin-right: auto;">
    <h1 style="margin-top: 50px; text-align: center;">Checkout</h1>
    
    <div class="checkout-flex">
        <div class="billing-details">
            <h3 style="margin-bottom: 25px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">Billing & Shipping</h3>
            
            <form action="php/place_order.php" method="POST">
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label>Complete Address</label>
                    <textarea name="address" rows="3" required placeholder="House No, Street Name, Landmark"></textarea>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="form-group" style="flex: 1;">
                        <label>City</label>
                        <input type="text" name="city" required placeholder="Hyderabad">
                    </div>
                    <div class="form-group" style="flex: 1;">
                        <label>Pincode</label>
                        <input type="text" name="pincode" required placeholder="500001">
                    </div>
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <label style="font-size: 18px; margin-bottom: 20px; display: block; font-weight: 700;">Select Payment Method</label>
                    
                    <div class="payment-methods-container" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden;">
                        
                        <label class="pay-method" style="display: flex; align-items: center; padding: 20px; border-bottom: 1px solid #eee; cursor: pointer; transition: 0.3s; background: #f0faf8;">
                            <input type="radio" name="payment_method" value="COD" checked style="width: 20px; height: 20px; margin-right: 15px; accent-color: #1ab394;">
                            <div style="flex: 1;">
                                <span style="display: block; font-weight: 600;">Cash on Delivery (COD)</span>
                                <small style="color: #888;">Pay when you receive the product</small>
                            </div>
                            <i class="fa-solid fa-truck-fast" style="font-size: 20px; color: #1ab394;"></i>
                        </label>

                        <label class="pay-method" style="display: flex; align-items: center; padding: 20px; border-bottom: 1px solid #eee; cursor: pointer; transition: 0.3s;">
                            <input type="radio" name="payment_method" value="UPI" style="width: 20px; height: 20px; margin-right: 15px;">
                            <div style="flex: 1;">
                                <span style="display: block; font-weight: 600;">UPI / Google Pay / PhonePe</span>
                                <small style="color: #888;">Pay instantly using UPI</small>
                            </div>
                            <i class="fa-solid fa-mobile-screen-button" style="font-size: 20px; color: #666;"></i>
                        </label>

                    </div>
                </div>

                <input type="hidden" name="total_amount" value="<?php echo $total; ?>">

                <button type="submit" class="place-order-btn">PLACE ORDER NOW</button>
            </form>
        </div>

<div class="order-summary">
            <h3 style="margin-bottom: 20px;">Your Order</h3>
            
            <div style="margin-bottom: 20px; padding: 15px; border: 1px dashed #1ab394; border-radius: 8px; background: #fff;">
                <div style="display: flex; gap: 5px;">
                    <input type="text" id="coupon_code" placeholder="Enter Coupon" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px; outline: none;">
                    <button type="button" onclick="applyCoupon()" style="background: #1ab394; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; font-weight: 600;">Apply</button>
                </div>
                <p id="coupon_msg" style="font-size: 11px; margin-top: 5px; display: none; font-weight: 600;"></p>
            </div>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₹<span id="display_subtotal"><?php echo number_format($total, 2); ?></span></span>
            </div>
            
            <div class="summary-row" id="discount_row" style="display: none; color: #1ab394;">
                <span>Coupon Discount</span>
                <span>-₹<span id="display_discount">0.00</span></span>
            </div>

            <div class="summary-row">
                <span>Shipping</span>
                <span style="color: #1ab394;">FREE</span>
            </div>

            <div class="summary-row total-row">
                <span>Total</span>
                <span>₹<span id="display_total"><?php echo number_format($total, 2); ?></span></span>
            </div>
            
            <p style="font-size: 12px; color: #777; margin-top: 20px; line-height: 1.4;">
                * Your personal data will be used to process your order...
            </p>
        </div>

</body>
    <script>
function applyCoupon() {
    const code = document.getElementById('coupon_code').value.trim().toUpperCase();
    const msg = document.getElementById('coupon_msg');
    const discountRow = document.getElementById('discount_row');
    const displayDiscount = document.getElementById('display_discount');
    const displayTotal = document.getElementById('display_total');
    const finalInput = document.getElementById('final_total_input');

    let subtotal = <?php echo $total; ?>;
    
    if (code === 'NEWUSER50') {
        let discount = 50.00;
        let newTotal = subtotal - discount;
        
        msg.innerHTML = "Success! ₹50 Discount Applied.";
        msg.style.color = "#1ab394";
        msg.style.display = "block";
        
        discountRow.style.display = "flex";
        displayDiscount.innerText = discount.toFixed(2);
        displayTotal.innerText = newTotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
        
        // Database ki updated value velladaniki (make sure you have this hidden input in form)
        if(document.getElementById('final_total_input')) {
            document.getElementById('final_total_input').value = newTotal;
        }
        
    } else {
        msg.innerHTML = "Invalid Coupon Code!";
        msg.style.color = "#e74c3c";
        msg.style.display = "block";
        discountRow.style.display = "none";
        displayTotal.innerText = subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
    }
}
</script>
</html>