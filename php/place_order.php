<?php
session_start();
include("db.php"); 

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])){
    $user = $_SESSION['user']; // Login ayina user email id
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $total = mysqli_real_escape_string($conn, $_POST['total_amount']);

    // Order database lo insert cheyadam
    $q = "INSERT INTO orders (user_email, customer_name, address, city, pincode, total_amount) 
          VALUES ('$user', '$name', '$address', '$city', '$pincode', '$total')";
    
    if(mysqli_query($conn, $q)){
        // Kotha ga create ayina Order ID ni fetch cheyadam
        $order_id = mysqli_insert_id($conn); 

        // --- Email Logic Start ---
        $to = $user; 
        $subject = "Order Confirmed - Infinity Cart";
        
        // Email Message Structure
        $message = "Hello " . $name . ",\n\n";
        $message .= "Mee order successfull ga place ayyindi.\n";
        $message .= "Order ID: #ORD-" . $order_id . "\n";
        $message .= "Total Amount: ₹" . $total . "\n\n";
        $message .= "Thank you for shopping with Infinity Cart!";
        
        $headers = "From: support@infinitycart.com";

        // PHP Mail function (Online hosting lo unnapude pani chestundhi)
        mail($to, $subject, $message, $headers);
        // --- Email Logic End ---

        // Order success ayyaka cart empty cheyali
        mysqli_query($conn, "DELETE FROM cart WHERE user_email = '$user'");
        
        // Success page ki redirect cheyadam
        header("Location: ../order_success.php");
        exit();
    } else {
        // SQL error unte chupisthundhi
        die("Order Failed: " . mysqli_error($conn));
    }
} else {
    header("Location: ../index.php");
}
?>