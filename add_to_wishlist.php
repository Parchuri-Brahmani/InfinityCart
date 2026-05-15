<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){
    echo "<script>alert('Please login first to add items to wishlist!'); window.location.href='login.php';</script>";
    exit();
}

if(isset($_POST['product_id'])){
    $user_email = $_SESSION['user'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);

    // Already wishlist lo undho ledho check cheyadam
    $check = mysqli_query($conn, "SELECT * FROM wishlist WHERE user_email = '$user_email' AND product_id = '$product_id'");

    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Item is already in your wishlist!'); window.location.href='wishlist.php';</script>";
    } else {
        // SQL Table columns match chesi insert
        $insert = "INSERT INTO wishlist (user_email, product_id) VALUES ('$user_email', '$product_id')";
        if(mysqli_query($conn, $insert)){
            echo "<script>alert('Added to Wishlist successfully!'); window.location.href='wishlist.php';</script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: index.php");
exit(); 
}
?>