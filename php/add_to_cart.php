<?php
session_start();
include("db.php");

// Login check mukhyaమైనది
if(!isset($_SESSION['user'])){
    echo "<script>alert('Please login first!'); window.location.href='../login.php';</script>";
    exit();
}

$user = $_SESSION['user'];
$product_id = mysqli_real_escape_string($conn, $_GET['id']);

// SQL logic ni inka strong ga update cheshanu
$query = "INSERT INTO cart(user_email, product_id, quantity) VALUES('$user', '$product_id', 1)";

mysqli_query($conn, $query);
header("Location: ../cart.php");
exit(); // IDI KHARITHANGAA UNDALI

?>