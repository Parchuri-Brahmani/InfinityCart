<?php
session_start();
include("../php/db.php"); // Path correction for database

// 1. Delete Logic
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: manage_products.php");
}

// 2. Fetch All Products
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; padding: 20px; position: fixed; }
        .main-content { margin-left: 250px; padding: 40px; width: calc(100% - 250px); }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 15px; border-radius: 5px; margin-bottom: 10px; }
        .sidebar a:hover { background: #34495e; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #1ab394; color: white; }
        .p-img-sm { width: 50px; height: 50px; object-fit: contain; border: 1px solid #eee; border-radius: 4px; }
        .btn-edit { color: #3498db; text-decoration: none; margin-right: 10px; }
        .btn-delete { color: #e74c3c; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="color: #1ab394;">Infinity Admin</h2>
        <a href="index.php"><i class="fa fa-gauge"></i> Dashboard</a>
        <a href="manage_products.php" style="background:#34495e;"><i class="fa fa-box"></i> Manage Products</a>
        <a href="add_product.php"><i class="fa fa-plus"></i> Add Product</a>
        <a href="../index.php" target="_blank"><i class="fa fa-eye"></i> View Site</a>
    </div>

    <div class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h1>Manage Products</h1>
            <a href="add_product.php" style="background: #1ab394; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">+ Add New</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): 
                    $img_src = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : "../images/" . $row['image'];
                ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><img src="<?php echo $img_src; ?>" class="p-img-sm"></td>
                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td>₹<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa fa-edit"></i> Edit</a>
                        <a href="manage_products.php?delete=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>