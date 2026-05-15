<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user'];

// User current details fetch cheyadam
$query = "SELECT * FROM users WHERE email = '$user_email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update Logic
if(isset($_POST['save_changes'])){
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Database update query
    $update_sql = "UPDATE users SET name = '$new_name', phone = '$new_phone' WHERE email = '$user_email'";
    
    if(mysqli_query($conn, $update_sql)){
        echo "<script>alert('Profile updated successfully!'); window.location.href='account.php';</script>";
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login & Security | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fff; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        h1 { font-size: 28px; font-weight: 400; margin-bottom: 20px; color: #111; }
        
        .info-box { border: 1px solid #ddd; border-radius: 4px; padding: 20px; margin-bottom: 15px; position: relative; }
        .info-label { font-weight: 700; font-size: 14px; display: block; margin-bottom: 5px; }
        .info-value { font-size: 14px; color: #111; }
        
        .edit-input { width: 95%; padding: 10px; margin-top: 10px; border: 1px solid #a6a6a6; border-radius: 3px; display: none; }
        
        .btn-edit { position: absolute; top: 20px; right: 20px; background: #fff; border: 1px solid #d5d9d9; padding: 5px 20px; border-radius: 8px; cursor: pointer; font-size: 13px; box-shadow: 0 2px 5px rgba(213,217,217,.5); }
        .btn-save { background: #ffd814; border: 1px solid #fcd200; padding: 12px; border-radius: 8px; width: 100%; font-weight: 600; cursor: pointer; margin-top: 20px; display: none; box-shadow: 0 2px 5px rgba(213,217,217,.5); }
        .btn-save:hover { background: #f7ca00; }
    </style>
</head>
<body>

<div class="container">
    <a href="account.php" style="text-decoration: none; color: #007185; font-size: 14px;"><i class="fa fa-chevron-left"></i> Back to Account</a>
    <h1>Login & Security</h1>

    <form method="POST">
        <div class="info-box">
            <span class="info-label">Name:</span>
            <div class="info-value" id="name-txt"><?php echo htmlspecialchars($user['name']); ?></div>
            <input type="text" name="name" id="name-in" class="edit-input" value="<?php echo htmlspecialchars($user['name']); ?>">
            <button type="button" class="btn-edit" onclick="enableEdit('name')">Edit</button>
        </div>

        <div class="info-box" style="background: #f9f9f9;">
            <span class="info-label">Email:</span>
            <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
            <p style="font-size: 11px; color: #666; margin-top: 8px;">Email cannot be changed directly for security.</p>
        </div>

        <div class="info-box">
            <span class="info-label">Mobile Number:</span>
            <div class="info-value" id="phone-txt"><?php echo !empty($user['phone']) ? htmlspecialchars($user['phone']) : 'Add mobile number'; ?></div>
            <input type="text" name="phone" id="phone-in" class="edit-input" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Enter phone number">
            <button type="button" class="btn-edit" onclick="enableEdit('phone')">Edit</button>
        </div>

        <button type="submit" name="save_changes" id="save-btn" class="btn-save">Done Updating</button>
    </form>
</div>

<script>
function enableEdit(field) {
    document.getElementById(field + '-txt').style.display = 'none';
    document.getElementById(field + '-in').style.display = 'block';
    document.getElementById('save-btn').style.display = 'block';
}
</script>

</body>
</html>