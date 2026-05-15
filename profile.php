<?php
include("php/db.php");
session_start();

$email = $_SESSION['user'];

$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h2 style="text-align:center">My Profile</h2>

<div style="width:300px;margin:auto">

<p><b>Name:</b> <?php echo $user['name']; ?></p>

<p><b>Email:</b> <?php echo $user['email']; ?></p>

<a href="edit_profile.php">
<button>Edit Profile</button>
</a>

<a href="logout.php">
<button>Logout</button>
</a>

</div>

</body>
</html>