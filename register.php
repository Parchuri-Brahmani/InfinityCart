<?php
include("php/db.php");
session_start();

if(isset($_POST['register'])){
    // SQL Injection nundi safe ga undataniki real_escape_string vadudham
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Password ni Hash cheyadam (Ee step login success avvadaniki mukhyaమైనది)
    // Manam plain text badulu secure hash store chesthunnam
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Email already database lo undho ledho check cheyadam
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($result) > 0){
        $error = "This email is already registered!";
    } else {
        // 2. Database loki $hashed_password ni insert chesthunnam
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
        
        if(mysqli_query($conn, $query)){
            $_SESSION['user'] = $email; // Automatically login after signup
            header("Location: index.php");
            exit();
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Infinity Cart</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        body {
            background: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }
        .register-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .register-card h2 {
            margin-bottom: 10px;
            font-weight: 700;
            color: #222;
        }
        .register-card p.tagline {
            color: #888;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .register-card input {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #eee;
            border-radius: 6px;
            outline: none;
            box-sizing: border-box;
        }
        .register-card button {
            width: 100%;
            background: #1ab394;
            color: white;
            border: none;
            padding: 14px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        .register-card button:hover {
            background: #148e75;
        }
        .error-msg {
            color: #e74c3c;
            background: #fdedec;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .login-link {
            margin-top: 25px;
            font-size: 14px;
            color: #666;
        }
        .login-link a {
            color: #1ab394;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h2>Create Account</h2>
    <p class="tagline">Sign up to start your shopping journey!</p>

    <?php if(isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register Now</button>
    </form>

    <p class="login-link">
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>