<?php
session_start();
include("php/db.php"); // Database connection check chesko

// User login check
if(!isset($_SESSION['user'])){
    echo "<script>alert('Please login first to add items to cart!'); window.location.href='login.php';</script>";
    exit();
}

if(isset($_POST['product_id'])){
    $user_email = $_SESSION['user'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;

    // Table exist avvakaquery fail ayithe handle cheyadaniki logic
    $check_query = "SELECT * FROM cart WHERE user_email = '$user_email' AND product_id = '$product_id'";
    $check_result = mysqli_query($conn, $check_query);

    // Fatal error raakunda check chesthunnam
    if($check_result === false){
        die("Database Error: " . mysqli_error($conn) . ". Please check if 'cart' table exists in 'infinitycart' database.");
    }

    if(mysqli_num_rows($check_result) > 0){
        // Product already unte quantity update chey
        $update_sql = "UPDATE cart SET quantity = quantity + $qty WHERE user_email = '$user_email' AND product_id = '$product_id'";
        mysqli_query($conn, $update_sql);
    } else {
        // Kothaga insert chey
        $insert_sql = "INSERT INTO cart (user_email, product_id, quantity) VALUES ('$user_email', '$product_id', '$qty')";
        mysqli_query($conn, $insert_sql);
    }

    echo "<script>alert('Product added to cart!'); window.location.href='cart.php';</script>";
} else {
    header("Location: index.php");
}
?>