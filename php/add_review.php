<?php
include("db.php");
session_start();

if(isset($_POST['product_id']) && isset($_SESSION['user'])){
    $user = $_SESSION['user'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']); // comment column name check chesko
    $rating = (int)$_POST['rating'];

    $query = "INSERT INTO reviews(product_id, user_email, comment, rating) 
              VALUES('$product_id', '$user', '$comment', '$rating')";

    if(mysqli_query($conn, $query)){
        header("Location: ../product.php?id=$product_id");
    } else {
        echo "Review error: " . mysqli_error($conn);
    }
}
?>