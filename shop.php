<?php
session_start();
// Error reporting enable chesthunnam - Dheenivalla loop ekkada aagindo browser lo error kanipisthundi
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("php/db.php");

// Database connection check
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 1. SMART SEARCH, FILTER & SORTING LOGIC
$where_clauses = [];
$order_by = "id DESC"; 

if(!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where_clauses[] = "(name LIKE '%$search%' OR category LIKE '%$search%')";
}

if(!empty($_GET['category'])) {
    $cat = mysqli_real_escape_string($conn, $_GET['category']);
    $where_clauses[] = "category = '$cat'";
}

if(!empty($_GET['category_search'])) { 
    $cat_search = mysqli_real_escape_string($conn, $_GET['category_search']);
    $where_clauses[] = "category = '$cat_search'";
}

if(isset($_GET['min']) && isset($_GET['max'])) {
    $min = (int)$_GET['min'];
    $max = (int)$_GET['max'];
    $where_clauses[] = "price BETWEEN $min AND $max";
}

if(!empty($_GET['sort'])) {
    if($_GET['sort'] == 'low') { $order_by = "price ASC"; }
    elseif($_GET['sort'] == 'high') { $order_by = "price DESC"; }
}
// shop.php lo line 38-45 daggara replace cheyi
$final_where = "";
if(!empty($where_clauses)) {
    $final_where = " WHERE " . implode(" AND ", $where_clauses);
}

$query = "SELECT * FROM products" . $final_where . " ORDER BY " . $order_by;
$result = mysqli_query($conn, $query);

// Query fail ayithe loading aapadaniki idhi mukhyaమైనది
if (!$result) {
    die("Database Query Error: " . mysqli_error($conn));
}

// 2. CART COUNT FOR HEADER
$cart_count = 0;
if(isset($_SESSION['user'])){
    $user_email = $_SESSION['user'];
    $count_res = mysqli_query($conn, "SELECT SUM(quantity) as total FROM cart WHERE user_email = '$user_email'");
    $count_data = mysqli_fetch_assoc($count_res);
    $cart_count = ($count_data && $count_data['total']) ? $count_data['total'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop | Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <style>
        .shop-container { display: flex; gap: 30px; padding: 40px 0; }
        .sidebar { flex: 1; background: #fff; padding: 25px; border: 1px solid #eee; border-radius: 8px; height: fit-content; }
        .main-content { flex: 3; }
        .filter-section { margin-bottom: 30px; }
        .filter-section h3 { font-size: 18px; margin-bottom: 15px; border-bottom: 2px solid #1ab394; display: inline-block; padding-bottom: 5px; }
        .filter-list { list-style: none; padding: 0; }
        .filter-list li { margin-bottom: 12px; }
        .filter-list a { text-decoration: none; color: #555; font-size: 14px; transition: 0.3s; display: block; }
        .filter-list a:hover { color: #1ab394; padding-left: 5px; }
        
        .shop-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        
        /* Product Cards Logic Sync with Index */
        .product-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; }
        .p-card { background: #fff; border: 1px solid #f0f0f0; border-radius: 10px; overflow: hidden; transition: 0.3s; display: flex; flex-direction: column; height: 100%; position: relative; }
        .p-card:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
        .p-img { width: 100%; height: 220px; display: flex; align-items: center; justify-content: center; background: #fdfdfd; padding: 15px; }
        .p-img img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .p-info { padding: 15px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .p-info h4 { font-size: 15px; margin: 10px 0; height: 40px; overflow: hidden; }
        .price { font-size: 18px; font-weight: 700; color: #1ab394; margin-bottom: 15px; }
        .select-opt { background: #222; color: #fff; padding: 10px; border-radius: 5px; text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.3s; }
        .select-opt:hover { background: #1ab394; }
    </style>
</head>
<body>

<header class="header-main">
    <div class="container flex-between" style="display:flex; justify-content: space-between; align-items: center; padding: 20px 0;">
        <div class="logo">
            <a href="index.php" style="text-decoration:none; color:inherit; font-size: 24px; font-weight: 800;">Infinity<span>Cart.</span></a>
        </div>
        
        <div class="search-bar" style="flex: 1; margin: 0 40px;">
            <form action="shop.php" method="GET" style="display: flex; width: 100%;">
                <input type="text" name="search" placeholder="Search products..." style="width:100%; padding:12px 20px; border:1px solid #ddd; border-radius: 4px 0 0 4px;" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" style="padding:10px 25px; background:#1ab394; color:white; border:none; border-radius: 0 4px 4px 0; cursor:pointer;"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="header-icons" style="display: flex; gap: 20px;">
            <a href="cart.php" style="text-decoration:none; color:inherit; position: relative;">
                <i class="fa fa-bag-shopping" style="font-size: 20px;"></i>
                <span style="position:absolute; top:-10px; right:-10px; background:#1ab394; color:white; border-radius:50%; padding:2px 6px; font-size:10px;"><?php echo $cart_count; ?></span>
            </a>
        </div>
    </div>
</header>

<div class="container shop-container">
    <aside class="sidebar">
        <div class="filter-section">
            <h3>Categories</h3>
            <ul class="filter-list">
                <li><a href="shop.php">All Products</a></li>
                <li><a href="shop.php?category=Men">Men's Fashion</a></li>
                <li><a href="shop.php?category=Women">Women's Fashion</a></li>
                <li><a href="shop.php?category=Electronics">Electronics</a></li>
                <li><a href="shop.php?category=Watches">Watches</a></li>
            </ul>
        </div>

        <div class="filter-section">
            <h3>Filter By Price</h3>
            <ul class="filter-list">
                <li><a href="shop.php?min=0&max=500">Under ₹500</a></li>
                <li><a href="shop.php?min=500&max=2000">₹500 - ₹2,000</a></li>
                <li><a href="shop.php?min=2000&max=5000">₹2,000 - ₹5,000</a></li>
                <li><a href="shop.php?min=5000&max=100000">Over ₹5,000</a></li>
            </ul>
        </div>
    </aside>

    <main class="main-content">
        <div class="shop-header">
            <p>Showing <strong><?php echo mysqli_num_rows($result); ?></strong> results</p>
            
            <form action="shop.php" method="GET" id="sortForm">
                <?php if(isset($_GET['category'])): ?><input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>"><?php endif; ?>
                <?php if(isset($_GET['min'])): ?><input type="hidden" name="min" value="<?php echo $_GET['min']; ?>"><?php endif; ?>
                <?php if(isset($_GET['max'])): ?><input type="hidden" name="max" value="<?php echo $_GET['max']; ?>"><?php endif; ?>
                
                <select name="sort" onchange="this.form.submit()" style="padding: 8px; border-radius: 4px; border: 1px solid #ddd; cursor:pointer;">
                    <option value="">Sort By: Default</option>
                    <option value="low" <?php if(isset($_GET['sort']) && $_GET['sort']=='low') echo 'selected'; ?>>Price: Low to High</option>
                    <option value="high" <?php if(isset($_GET['sort']) && $_GET['sort']=='high') echo 'selected'; ?>>Price: High to Low</option>
                </select>
            </form>
        </div>

        <div class="product-grid">
            <?php
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    // Universal Image Handling
                    $img = $row['image'];
                    if (!filter_var($img, FILTER_VALIDATE_URL)) {
                        $img = "images/" . $img;
                    }
            ?>
                <div class="p-card">
                    <div class="p-img">
                        <img src="<?php echo $img; ?>" 
                             onerror="this.src='https://via.placeholder.com/220x220?text=No+Image'"
                             alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="p-info">
                        <div>
                            <span style="font-size: 11px; color: #999; text-transform: uppercase;"><?php echo $row['category']; ?></span>
                            <h4 title="<?php echo htmlspecialchars($row['name']); ?>"><?php echo $row['name']; ?></h4>
                            <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>
                        </div>
                        <a href="product.php?id=<?php echo $row['id']; ?>" class="select-opt">View Details</a>
                    </div>
                </div>
            <?php 
                }
            } else {
                echo "<div style='grid-column: 1/4; text-align:center; padding: 80px; color: #999;'>
                        <i class='fa fa-search-minus' style='font-size: 40px; margin-bottom: 15px;'></i>
                        <p>No products found matching your selection.</p>
                        <a href='shop.php' style='color: #1ab394; text-decoration: none;'>Clear All Filters</a>
                      </div>";
            }
            ?>
        </div>
    </main>
</div>

</body>
</html>