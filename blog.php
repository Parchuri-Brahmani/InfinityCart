<?php
session_start();
include("php/db.php");

// Header cart count logic
$cart_count = 0;
if(isset($_SESSION['user'])){
    $user_email = $_SESSION['user'];
    $count_res = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_email = '$user_email'");
    $count_data = mysqli_fetch_assoc($count_res);
    $cart_count = $count_data['total'] ? $count_data['total'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .blog-header { background: #f4f4f4; padding: 60px 0; text-align: center; margin-bottom: 50px; }
        .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; margin-bottom: 80px; }
        .blog-card { background: #fff; border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: 0.3s; }
        .blog-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .blog-img { width: 100%; height: 230px; object-fit: cover; }
        .blog-content { padding: 25px; }
        .blog-meta { font-size: 12px; color: #888; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .blog-title { font-size: 20px; font-weight: 700; margin-bottom: 15px; color: #222; text-decoration: none; display: block; }
        .blog-title:hover { color: #1ab394; }
        .read-more { color: #1ab394; font-weight: 600; text-decoration: none; font-size: 14px; border-bottom: 2px solid #1ab394; }
    </style>
</head>
<body>

<div class="blog-header">
    <div class="container">
        <h1 style="font-size: 40px; font-weight: 800;">Our Blog</h1>
        <p style="color: #666;">Latest news, fashion tips, and tech reviews</p>
    </div>
</div>

<div class="container">
    <div class="blog-grid">
        <?php
        $blog_query = "SELECT * FROM blogs ORDER BY created_at DESC";
        $blog_res = mysqli_query($conn, $blog_query);

        if(mysqli_num_rows($blog_res) > 0) {
            while($row = mysqli_fetch_assoc($blog_res)) {
        ?>
            <div class="blog-card">
                <img src="<?php echo $row['image']; ?>" class="blog-img" alt="Blog Post">
                <div class="blog-content">
                    <div class="blog-meta">
                        <span><i class="fa fa-tag"></i> <?php echo $row['category']; ?></span> | 
                        <span><i class="fa fa-calendar"></i> <?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                    </div>
                    <a href="#" class="blog-title"><?php echo $row['title']; ?></a>
                    <p style="color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 20px;">
                        <?php echo substr($row['content'], 0, 100); ?>...
                    </p>
                    <a href="#" class="read-more">READ MORE</a>
                </div>
            </div>
        <?php 
            }
        } else {
            echo "<p>No blog posts found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>