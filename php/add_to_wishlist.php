<?php
include("db.php");

$product_id = $_GET['id'];

$query = "INSERT INTO wishlist(product_id) VALUES('$product_id')";

mysqli_query($conn,$query);

header("Location: ../wishlist.php");
?>