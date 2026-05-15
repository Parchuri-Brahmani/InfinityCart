<?php
session_start();

if(isset($_POST['admin_login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // EXACT CREDENTIALS LOCK
    if($email === 'admin@infinitycart.com' && $password === 'Infinity@123'){
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php"); // Succes ayithe Dashboard ki velthundi
        exit();
    } else {
        $error = "Invalid Admin Credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Infinity Cart</title>
    <style>
        body { background: #f7f7f7; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: 'Segoe UI', sans-serif; margin:0; }
        .login-container { background: #fff; padding: 50px 40px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
        .admin-badge { background: #eee; color: #666; padding: 5px 15px; border-radius: 5px; font-size: 12px; display: inline-block; margin-bottom: 15px; font-weight: 600; }
        h2 { font-weight: 800; color: #222; margin-bottom: 30px; font-size: 28px; }
        input { width: 100%; padding: 15px; margin-bottom: 20px; border: 1px solid #eee; border-radius: 8px; outline: none; box-sizing: border-box; font-size: 15px; }
        button { width: 100%; background: #1ab394; color: white; border: none; padding: 15px; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .links { margin-top: 25px; font-size: 14px; }
        .links a { color: #1ab394; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-container">
        <span class="admin-badge">Admin Mode</span>
        <h2>Admin Login</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="admin_login">Login as Admin</button>
        </form>
        <div class="links"><a href="../login.php">Switch to User Login</a></div>
    </div>
</body>
</html>