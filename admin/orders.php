<?php
session_start();
include("../php/db.php");

// Status update logic
if(isset($_GET['id']) && isset($_GET['status'])){
    $id = $_GET['id'];
    $status = $_GET['status'];
    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = $id");
    header("Location: orders.php?msg=Status Updated");
}

$query = "SELECT * FROM orders ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders | Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; margin: 0; display: flex; background: #f4f7f6; }
        .sidebar { width: 250px; height: 100vh; background: #2c3e50; color: white; padding: 20px; position: fixed; }
        .main { margin-left: 250px; padding: 30px; width: 100%; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #1ab394; color: white; }
        .status-select { padding: 5px; border-radius: 4px; border: 1px solid #ddd; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Infinity Admin</h2>
        <a href="index.php">Dashboard</a>
        <a href="manage_products.php">Products</a>
        <a href="orders.php" style="background: #1ab394; border-radius: 4px;">Orders</a>
        <a href="../index.php">View Site</a>
    </div>

    <div class="main">
        <h1>Manage Customer Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo $row['customer_name']; ?><br><small><?php echo $row['user_email']; ?></small></td>
                    <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                    <td style="font-size: 12px;"><?php echo $row['address']; ?></td>
                    <td><strong><?php echo $row['status']; ?></strong></td>
                    <td>
                        <form method="GET" style="display: flex; gap: 5px;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="status-select">
                                <option value="Processing">Processing</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                            <button type="submit" style="background: #222; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>