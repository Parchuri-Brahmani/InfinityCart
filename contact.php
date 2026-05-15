<?php
session_start();
include("php/db.php");

// Cart count fetch
$cart_count = 0;
if(isset($_SESSION['user'])){
    $user_email = $_SESSION['user'];
    $count_res = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_email = '$user_email'");
    $count_data = mysqli_fetch_assoc($count_res);
    $cart_count = $count_data['total'] ? $count_data['total'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .contact-hero { background: #f9f9f9; padding: 60px 0; text-align: center; border-bottom: 1px solid #eee; }
        .contact-flex { display: flex; gap: 50px; padding: 60px 0; align-items: flex-start; }
        .contact-info { flex: 1; }
        .contact-form { flex: 2; background: #fff; padding: 40px; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        
        .info-item { display: flex; gap: 20px; margin-bottom: 30px; }
        .info-item i { font-size: 24px; color: #1ab394; background: #f0faf8; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .info-text h4 { margin: 0 0 5px 0; font-size: 18px; }
        .info-text p { margin: 0; color: #666; font-size: 14px; }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; outline: none; box-sizing: border-box; }
        .form-group input:focus, .form-group textarea:focus { border-color: #1ab394; }
        
        .submit-btn { background: #222; color: #fff; border: none; padding: 15px 40px; border-radius: 5px; font-weight: 700; cursor: pointer; transition: 0.3s; width: 100%; }
        .submit-btn:hover { background: #1ab394; }
        
        .map-section { width: 100%; height: 400px; background: #eee; margin-top: 50px; }

        /* Status Alert Styles */
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 25px; text-align: center; font-weight: 600; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

<div class="contact-hero">
    <div class="container">
        <h1 style="font-size: 40px; font-weight: 800; margin: 0;">Contact Us</h1>
        <p style="color: #888; margin-top: 10px;">We are here to help you with any questions or concerns</p>
    </div>
</div>

<div class="container contact-flex">
    <div class="contact-info">
        <div class="info-item">
            <i class="fa fa-location-dot"></i>
            <div class="info-text">
                <h4>Our Store</h4>
                <p>123 Infinity Street, Tech Park Area,<br>Hyderabad, Telangana - 500081</p>
            </div>
        </div>
        <div class="info-item">
            <i class="fa fa-phone"></i>
            <div class="info-text">
                <h4>Call Us</h4>
                <p>+91 98765 43210<br>Mon-Sat: 9:00 AM - 7:00 PM</p>
            </div>
        </div>
        <div class="info-item">
            <i class="fa fa-envelope"></i>
            <div class="info-text">
                <h4>Email Support</h4>
                <p>support@infinitycart.com<br>info@infinitycart.com</p>
            </div>
        </div>
    </div>

    <div class="contact-form">
        <h3 style="margin-bottom: 25px;">Send Us a Message</h3>

        <!-- Status Message Logic -->
        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i> Thank you! Your message has been sent successfully.
            </div>
        <?php elseif(isset($_GET['status']) && $_GET['status'] == 'error'): ?>
            <div class="alert alert-error">
                <i class="fa fa-exclamation-circle"></i> Oops! Something went wrong. Please try again.
            </div>
        <?php endif; ?>

        <!-- Updated form action path -->
        <form action="php/send_contact.php" method="POST">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" name="name" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" required placeholder="What is this about?">
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="5" required placeholder="Write your message here..."></textarea>
            </div>
            <button type="submit" class="submit-btn">SEND MESSAGE <i class="fa fa-paper-plane"></i></button>
        </form>
    </div>
</div>

<div class="map-section">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3806.8272226612346!2d78.38139367516347!3d17.420077383471017!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bcb9158f201b205%3A0x11bbe7be7792411b!2sHITEC%20City%2C%20Hyderabad!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</div>

</body>
</html>