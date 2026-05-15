<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){ header("Location: login.php"); exit(); }
$user_email = $_SESSION['user'];

// Form Submission Logic
if(isset($_POST['submit_address'])){
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $house = mysqli_real_escape_string($conn, $_POST['house_no']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);

    $insert = "INSERT INTO user_addresses (user_email, full_name, mobile, pincode, house_no, area, city, state) 
               VALUES ('$user_email', '$name', '$mobile', '$pincode', '$house', '$area', '$city', '$state')";
    
    if(mysqli_query($conn, $insert)){
        echo "<script>alert('Address added successfully!'); window.location.href='addresses.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Address | Infinity Cart</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #fff; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; border: 1px solid #ddd; padding: 30px; border-radius: 8px; }
        h2 { font-size: 24px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 700; margin-bottom: 5px; font-size: 13px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #a6a6a6; border-radius: 3px; box-sizing: border-box; }
        .btn-add { background: #ffd814; border: 1px solid #fcd200; padding: 12px; border-radius: 8px; width: 100%; font-weight: 600; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add a new address</h2>
    <form method="POST">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>
        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" required>
        </div>
        <div class="form-group">
            <label>Pincode</label>
            <input type="text" name="pincode" placeholder="6 digits [0-9] PIN code" required>
        </div>
        <div class="form-group">
            <label>Flat, House no., Building, Company, Apartment</label>
            <input type="text" name="house_no" required>
        </div>
        <div class="form-group">
            <label>Area, Street, Sector, Village</label>
            <input type="text" name="area" required>
        </div>
        <div class="form-group">
            <label>Town/City</label>
            <input type="text" name="city" required>
        </div>
        <div class="form-group">
            <label>State</label>
            <input type="text" name="state" required>
        </div>
        <button type="submit" name="submit_address" class="btn-add">Add address</button>
    </form>
</div>

</body>
</html>