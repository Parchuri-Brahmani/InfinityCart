<?php
include("php/db.php");
session_start();

$user = $_SESSION['user'];

$query = "SELECT products.name,products.price,products.image 
FROM orders 
JOIN products ON orders.product_id = products.id
WHERE orders.user_email='$user'";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Order History</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h2 style="text-align:center">My Orders</h2>

<div class="products">

<?php
while($row=mysqli_fetch_assoc($result)){
?>

<div class="product">

<img src="images/<?php echo $row['image']; ?>">

<h3><?php echo $row['name']; ?></h3>

<p>₹<?php echo $row['price']; ?></p>

<p>Order Completed</p>

</div>

<?php } ?>

</div>

</body>
</html>