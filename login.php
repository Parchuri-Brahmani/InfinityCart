<?php
include("php/db.php");
session_start();

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $_SESSION['user'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Infinity Cart</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        
        body {
            background: #f4f7f6; /* Light greyish-blue background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); /* Soft professional shadow */
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 35px;
            font-weight: 700;
            color: #222;
            font-size: 30px;
            letter-spacing: -1px;
        }

        .login-container input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #f0f0f0;
            border-radius: 8px;
            outline: none;
            background: #fcfcfc;
            font-size: 15px;
            transition: 0.3s;
        }

        .login-container input:focus {
            border-color: #1ab394;
            background: #fff;
        }

        .login-container button {
            width: 100%;
            background: #1ab394; /* Your signature teal color */
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .login-container button:hover {
            background: #148e75;
            transform: translateY(-2px);
        }

        .error-msg {
            color: #e74c3c;
            margin-bottom: 20px;
            font-size: 14px;
            background: #fdf2f2;
            padding: 10px;
            border-radius: 5px;
        }

        .footer-links {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            line-height: 1.8;
        }

        .footer-links a {
            color: #1ab394;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Welcome Back!!</h2>
    
    <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login Now</button>
    </form>
    
    <div class="footer-links">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p style="margin-top: 10px; border-top: 1px solid #f4f4f4; padding-top: 15px;">
            Are you an Admin? <a href="admin/login.php">Sign in here</a>
        </p>
    </div>
</div>

</body>
</html>