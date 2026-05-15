<?php
session_start();
include("../php/db.php");

// Protection: Login avvakapothe admin/login.php ki pampisthundi
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: login.php");
    exit();
}
// 1. Fetch Stats
$product_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM products"));
$customer_count = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));

// Calculate Total Revenue
$revenue_res = mysqli_query($conn, "SELECT SUM(total_amount) as total FROM orders WHERE status != 'Cancelled'");
$revenue_data = mysqli_fetch_assoc($revenue_res);
$total_revenue = $revenue_data['total'] ? $revenue_data['total'] : 0;

// 2. Fetch Recent Orders (Latest 5)
$recent_orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #1ab394; --dark: #2c3e50; --light: #f4f7f6; }
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: var(--light); display: flex; }
        
        /* Sidebar Styles */
        .sidebar { width: 260px; height: 100vh; background: var(--dark); color: white; padding: 30px 20px; position: fixed; }
        .sidebar h2 { color: var(--primary); margin-bottom: 40px; font-size: 24px; }
        .nav-links { list-style: none; padding: 0; }
        .nav-links li { margin-bottom: 15px; }
        .nav-links a { color: #bdc3c7; text-decoration: none; display: flex; align-items: center; gap: 15px; padding: 12px; border-radius: 8px; transition: 0.3s; }
        .nav-links a:hover, .nav-links a.active { background: rgba(26, 179, 148, 0.15); color: var(--primary); }
        .logout-link { margin-top: 50px; color: #e74c3c !important; }

        /* Main Content */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .welcome-header h1 { margin: 0; font-size: 28px; color: #222; }
        .welcome-header p { color: #777; margin-top: 5px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; margin-top: 40px; }
        .stat-card { background: white; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid transparent; transition: 0.3s; }
        .stat-card:hover { border-color: var(--primary); }
        .stat-card i { font-size: 35px; color: var(--primary); margin-bottom: 15px; }
        .stat-card h3 { margin: 10px 0; font-size: 14px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .stat-card .value { font-size: 32px; font-weight: 800; color: #222; }

        /* Table & Section Styles */
        .section-box { background: white; padding: 30px; border-radius: 12px; margin-top: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .section-header h2 { font-size: 20px; margin: 0; color: #222; }
        .view-all-btn { color: var(--primary); text-decoration: none; font-weight: 600; font-size: 14px; }

        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px; color: #888; font-size: 13px; text-transform: uppercase; border-bottom: 1px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #f9f9f9; font-size: 14px; color: #444; }
        .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .status-processing { background: #e8f5f3; color: #1ab394; }
        .status-shipped { background: #e3f2fd; color: #1e88e5; }
        .status-delivered { background: #f1f8e9; color: #7cb342; }

        /* Quick Management Box */
        .btn-add { background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: 0.3s; }
        .btn-add:hover { background: #15967d; transform: translateY(-2px); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Infinity Admin</h2>
        <ul class="nav-links">
            <li><a href="index.php" class="active"><i class="fa fa-gauge"></i> Dashboard</a></li>
            <li><a href="manage_products.php"><i class="fa fa-boxes-stacked"></i> Manage Products</a></li>
            <li><a href="orders.php"><i class="fa fa-cart-shopping"></i> View Orders</a></li>
            <li><a href="../index.php" target="_blank"><i class="fa fa-external-link"></i> Live Website</a></li>
            <li><a href="logout.php" class="logout-link"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="welcome-header">
            <h1>Welcome, Administrator</h1>
            <p>Here is what's happening in your store today.</p>
        </header>

        <!-- Stats Overview -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa fa-box-open"></i>
                <h3>Total Products</h3>
                <div class="value"><?php echo $product_count; ?></div>
            </div>
            <div class="stat-card">
                <i class="fa fa-users"></i>
                <h3>Total Customers</h3>
                <div class="value"><?php echo $customer_count; ?></div>
            </div>
            <div class="stat-card">
                <i class="fa fa-indian-rupee-sign"></i>
                <h3>Total Revenue</h3>
                <div class="value">₹<?php echo number_format($total_revenue, 2); ?></div>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="section-box">
            <div class="section-header">
                <h2>Recent Orders</h2>
                <a href="orders.php" class="view-all-btn">View All Orders <i class="fa fa-arrow-right"></i></a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($recent_orders) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($recent_orders)): ?>
                        <tr>
                            <td style="font-weight: 700;">#ORD-<?php echo $row['id']; ?></td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td>
                                <span class="status-pill status-<?php echo strtolower($row['status'] ?? 'processing'); ?>">
                                    <?php echo $row['status'] ?? 'Processing'; ?>
                                </span>
                            </td>
                            <td style="color: #999;"><?php echo date('d M Y', strtotime($row['order_date'] ?? 'now')); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #aaa;">No recent orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Quick Management Section -->
        <div class="section-box">
            <h2>Quick Actions</h2>
            <p style="color: #666; margin-bottom: 25px;">Perform common tasks like adding new products to your inventory.</p>
            <a href="add_product.php" class="btn-add">
                <i class="fa fa-plus-circle"></i> Add New Product
            </a>
        </div>
    </main>

</body>
</html>