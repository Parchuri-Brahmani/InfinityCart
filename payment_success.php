<?php
session_start();
include("php/db.php");

/* check user login */
if(isset($_SESSION['user'])){
$user = $_SESSION['user'];
}else{
$user = "guest";
}

/* payment method */
if(isset($_POST['payment'])){
$payment = $_POST['payment'];
}else{
$payment = "Unknown";
}

/* get cart products */
$query = "SELECT products.id,products.price 
FROM cart 
JOIN products ON cart.product_id = products.id";

$result = mysqli_query($conn,$query);

/* insert orders */
while($row = mysqli_fetch_assoc($result)){

$product_id = $row['id'];
$price = $row['price'];

mysqli_query($conn,"INSERT INTO orders(user_email,product_id,price)
VALUES('$user','$product_id','$price')");

}

/* clear cart after order */
mysqli_query($conn,"DELETE FROM cart");

?>
<!DOCTYPE html>
<html>
<head>
<title>Order Success</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h2 style="text-align:center">Order Placed Successfully</h2>

<p style="text-align:center">
Payment Method: <?php echo $payment; ?>
</p>

<br>

<a href="index.php">
<button style="display:block;margin:auto">Go Home</button>
</a>

</body>
</html>