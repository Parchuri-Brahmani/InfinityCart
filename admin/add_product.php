<?php
include("../php/db.php");

if(isset($_POST['add'])){

$name=$_POST['name'];
$price=$_POST['price'];
$image=$_POST['image'];
$category=$_POST['category'];

$query="INSERT INTO products(name,price,image,category)
VALUES('$name','$price','$image','$category')";

mysqli_query($conn,$query);

echo "Product Added";

}
?>

<h2>Add Product</h2>

<form method="POST">

<input type="text" name="name" placeholder="Product Name">

<br><br>

<input type="number" name="price" placeholder="Price">

<br><br>

<input type="text" name="image" placeholder="Image name">

<br><br>

<input type="text" name="category" placeholder="Category">

<br><br>

<button name="add">Add Product</button>

</form>