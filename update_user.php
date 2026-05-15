<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){ exit(); }
$user_email = $_SESSION['user'];

if(isset($_POST['save_profile'])){
    $name = mysqli_real_escape_string($conn, $_POST['new_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['new_phone']);

    $update = "UPDATE users SET name = '$name', phone = '$phone' WHERE email = '$user_email'";
    
    if(mysqli_query($conn, $update)){
        echo "<script>alert('Profile updated successfully!'); window.location.href='account.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>