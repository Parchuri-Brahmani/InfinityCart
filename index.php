<?php
session_start();
include("php/db.php");

// 1. Dynamic Counts Fetching Logic
$cart_count = 0;
$wish_count = 0;

if(isset($_SESSION['user'])){
    $user_email = $_SESSION['user'];
    
    // Cart Count
    $cart_q = "SELECT SUM(quantity) as total FROM cart WHERE user_email = '$user_email'";
    $cart_res = mysqli_query($conn, $cart_q);
    $cart_data = mysqli_fetch_assoc($cart_res);
    $cart_count = ($cart_data && $cart_data['total']) ? $cart_data['total'] : 0;

    // Wishlist Count
    $wish_q = "SELECT COUNT(*) as total FROM wishlist WHERE user_email = '$user_email'";
    $wish_res = mysqli_query($conn, $wish_q);
    $wish_data = mysqli_fetch_assoc($wish_res);
    $wish_count = ($wish_data && $wish_data['total']) ? $wish_data['total'] : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <title>Infinity Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <style>
        /* Search Bar & Product Card Layout Fixes */
        .search-bar form { display: flex; width: 100%; border: 2px solid #1ab394; border-radius: 50px; overflow: hidden; background: #fff; }
        .search-bar input { flex: 1; border: none; padding: 12px 20px; outline: none; }
        .search-bar select { border: none; border-left: 1px solid #eee; padding: 0 15px; outline: none; background: #f9f9f9; cursor: pointer; }
        .search-bar button { background: #1ab394; color: white; border: none; padding: 0 25px; cursor: pointer; }

        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px; margin-top: 30px; }
        .p-card { background: #fff; border: 1px solid #f0f0f0; border-radius: 10px; overflow: hidden; transition: 0.3s; display: flex; flex-direction: column; height: 100%; position: relative; }
        .p-card:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); }
        .p-img { width: 100%; height: 220px; display: flex; align-items: center; justify-content: center; background: #fdfdfd; padding: 15px; }
        .p-img img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .p-info { padding: 15px; text-align: center; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
        .p-info h4 { font-size: 15px; margin: 10px 0; height: 40px; overflow: hidden; }
        .price { font-size: 18px; font-weight: 700; color: #1ab394; margin-bottom: 15px; }
        .tag-sale { position: absolute; top: 10px; left: 10px; background: #e74c3c; color: white; padding: 2px 8px; font-size: 10px; font-weight: bold; border-radius: 3px; z-index: 1; }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
    
<body>
<div id="counter-box" style="display:none; background: #fff; border-left: 5px solid #1ab394; padding: 15px; position: fixed; bottom: 20px; left: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; z-index: 9999; font-family: sans-serif;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="height: 10px; width: 10px; background: #1ab394; border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite;"></span>
        <span id="live-users" style="color: #333; font-weight: 600;">Checking live users...</span>
    </div>
</div>

<style>
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(26, 179, 148, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(26, 179, 148, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(26, 179, 148, 0); }
}
</style>
    <style>
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    const socket = io("https://infinitycart-smart.onrender.com");
    const counterBox = document.getElementById("counter-box");

    socket.on("connect", () => {
        console.log("Connected to Smart Server!");
        counterBox.style.display = "block";
    });

    // 1. Live User Counter Logic
    socket.on("user-count", (count) => {
        document.getElementById("live-users").innerText = count + " shoppers browsing now";
    });

    // 2. Live Cart Notification Logic
    socket.on("show-cart-notif", (data) => {
        const notif = document.createElement("div");
        notif.style.cssText = "position: fixed; top: 20px; right: 20px; background: #333; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 10000; display: flex; align-items: center; gap: 10px; animation: slideIn 0.5s ease-out;";
        notif.innerHTML = `
            <span style="font-size: 24px;">🛒</span>
            <div>
                <strong style="display:block; color: #1ab394;">Smart Update!</strong>
                <span style="font-size: 13px;">Someone just added an item to their cart.</span>
            </div>
        `;
        document.body.appendChild(notif);
        setTimeout(() => { notif.style.opacity = '0'; setTimeout(() => notif.remove(), 500); }, 4000);
    });

    // Function to trigger notification
    function notifyCartAdd() {
        socket.emit("cart-added", { time: new Date() });
    }
</script>

<script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
<script>
    const socket = io("https://infinitycart-smart.onrender.com");
    const counterBox = document.getElementById("counter-box");

    socket.on("connect", () => {
        console.log("Connected to Smart Server!");
        counterBox.style.display = "block"; 
    });

    // 1. Live User Counter Logic
    socket.on("user-count", (count) => {
        document.getElementById("live-users").innerText = count + " shoppers browsing now";
    });

    // 2. Live Cart Notification Logic (Idi kothaga add chesthunnam)
    socket.on("show-cart-notif", (data) => {
        const notif = document.createElement("div");
        notif.style.cssText = "position: fixed; top: 20px; right: 20px; background: #333; color: white; padding: 15px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); z-index: 10000; display: flex; align-items: center; gap: 10px; animation: slideIn 0.5s ease-out; border-left: 4px solid #1ab394;";
        notif.innerHTML = `
            <span style="font-size: 24px;">🛒</span>
            <div>
                <strong style="display:block; color: #1ab394;">Smart Update!</strong>
                <span style="font-size: 13px;">Someone just added an item to their cart.</span>
            </div>
        `;
        document.body.appendChild(notif);
        
        // 4 seconds tharvatha popup poyettu cheyadam
        setTimeout(() => { 
            notif.style.opacity = '0'; 
            notif.style.transition = '0.5s';
            setTimeout(() => notif.remove(), 500); 
        }, 4000);
    });

    // Function to trigger notification (Add to Cart button ki idhe connect chestham)
    function notifyCartAdd() {
        socket.emit("cart-added", { time: new Date() });
    }
</script>
<div class="top-bar">
    <div class="container flex-between">
        <div class="contact">
            <span><i class="fa fa-envelope"></i> support@infinitycart.com</span> | 
            <span><i class="fa fa-phone"></i> +91 9876543210</span>
        </div>
        <div class="top-links">
            <?php if(isset($_SESSION['user'])): ?>
                Welcome, <strong><?php echo $_SESSION['user']; ?></strong> | 
                <a href="account.php">My Account</a>
            <?php else: ?>
                Welcome to Our Store! | <a href="login.php">Login / Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<header class="header-main">
    <div class="container flex-between">
        <div class="logo">
    <a href="index.php" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
        Infinity<span class="cart-text" style="font-family: 'Montserrat', sans-serif !important; font-weight: 300; color: #666; margin-left: 2px; font-size: 28px;">Cart</span><span class="dot" style="color: #1ab394; font-size: 35px; margin-left: -1px;">.</span>
    </a>
</div>
        
        <div class="search-bar">
            <form action="shop.php" method="GET">
                <input type="text" name="search" placeholder="Search for products, categories, sku..." 
                       value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <select name="category_search">
                    <option value="">All Categories</option>
                    <option value="Men">Men</option>
                    <option value="Women">Women</option>
                    <option value="Electronics">Electronics</option>
                </select>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
<div class="header-icons">
    <a href="<?php echo isset($_SESSION['user']) ? 'account.php' : 'login.php'; ?>" title="Account" style="text-decoration:none; color:inherit;">
        <div class="h-icon">
            <i class="fa-regular fa-user"></i>
        </div>
    </a>
    
    <a href="wishlist.php" title="Wishlist" style="text-decoration:none; color:inherit;">
        <div class="h-icon">
            <i class="fa-regular fa-heart"></i>
            <?php if($wish_count > 0): ?>
                <span class="count"><?php echo $wish_count; ?></span>
            <?php endif; ?>
        </div>
    </a>
    
   </a> <a href="cart.php" title="Cart" style="text-decoration:none; color:inherit;">
            <div class="h-icon">
                <i class="fa fa-bag-shopping"></i>
                <?php if($cart_count > 0): ?>
                    <span class="count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </div>
        </a>
    </div> 
</div> 
            
<nav class="nav-green">
    <div class="container flex-between" style="justify-content: flex-start; display: flex; align-items: center;">
        <div class="cat-dropdown" style="white-space: nowrap; position: relative;">
            <i class="fa fa-bars"></i> SHOPPING BY CATEGORIES
            <div class="dropdown-content">
                <a href="shop.php?category=Men">Men's Fashion</a>
                <a href="shop.php?category=Women">Women's Fashion</a>
                <a href="shop.php?category=Electronics">Electronics</a>
                <a href="shop.php?category=Watches">Watches</a>
            </div>
        </div>

        <ul class="main-nav" style="display: flex; list-style: none; margin: 0; padding: 0;">
            <li><a href="index.php" class="active">Home</a></li>
            <li><a href="shop.php">Shop</a></li>
            <li class="has-children">
                <a href="#">Pages <i class="fa fa-chevron-down" style="font-size: 10px;"></i></a>
                <ul class="sub-menu">
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="terms.php">Terms & Conditions</a></li>
                </ul>
            </li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 20px;">
    <section class="hero-slider swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
    <div class="hero-inner" style="background: linear-gradient(to right, rgba(255,255,255,0.6), transparent);">
        <div class="hero-text" style="left: 8%; right: auto; text-align: left;">
            <h4 style="color: #1ab394; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Summer Sale</h4>
            <h1 style="color: #222; font-size: 65px; font-weight: 800; line-height: 1.1;">NEW <br>COLLECTIONS</h1>
            <h3 style="color: #555; font-size: 24px; margin-bottom: 25px;">UPTO 65% OFF</h3>
            <a href="shop.php" class="btn-dark" style="text-decoration:none; background: #1ab394; padding: 15px 40px; border-radius: 4px; font-weight: 700;">Shop Now</a>
        </div>
        <div class="hero-img">
            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800" alt="Fashion Sale">
        </div>
    </div>
</div>
            
            <div class="swiper-slide">
                <div class="hero-inner" style="background: #f0f4f7;">
                    <div class="hero-text">
                        <h4>Gadget Fest</h4>
                        <h1>TECH GEAR</h1>
                        <h3>LATEST GADGETS</h3>
                        <a href="shop.php?category=Electronics" class="btn-dark" style="text-decoration:none;">Explore Tech</a>
                    </div>
                    <div class="hero-img">
                        <img src="images/slidertechimage.jpg" alt="Electronics">
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </section>
</div>

<section class="trust-badges container" style="display: flex; justify-content: space-around; padding: 40px 0; border-top: 1px solid #eee;">
    <div class="badge-item" style="text-align: center;">
        <i class="fa fa-truck" style="font-size: 30px; color: #1ab394;"></i>
        <h4 style="margin-top: 10px;">Free Shipping</h4>
        <p style="font-size: 12px; color: #888;">On orders above ₹999</p>
    </div>
    <div class="badge-item" style="text-align: center;">
        <i class="fa fa-rotate-left" style="font-size: 30px; color: #1ab394;"></i>
        <h4 style="margin-top: 10px;">Easy Returns</h4>
        <p style="font-size: 12px; color: #888;">7-day return policy</p>
    </div>
    <div class="badge-item" style="text-align: center;">
        <i class="fa fa-lock" style="font-size: 30px; color: #1ab394;"></i>
        <h4 style="margin-top: 10px;">Secure Payment</h4>
        <p style="font-size: 12px; color: #888;">100% safe transactions</p>
    </div>
</section>

<section class="container deals-section" style="margin-top: 50px;">
    <div class="section-title">
        <h2 style="font-weight: 600;">Deals Of The Day</h2>
    </div>
    <div class="product-grid">
        <?php
        $product_query = "SELECT * FROM products LIMIT 10"; 
        $product_result = mysqli_query($conn, $product_query);
        while($row = mysqli_fetch_assoc($product_result)):
            // Image handling update
            $image_src = $row['image'];
            if (!filter_var($image_src, FILTER_VALIDATE_URL)) {
                $image_src = "images/" . $image_src;
            }
        ?>
            <div class="p-card">
                <div class="tag-sale">SALE</div>
                <div class="p-img">
                    <img src="<?php echo $image_src; ?>" 
                         onerror="this.src='https://via.placeholder.com/220x220?text=No+Image'"
                         alt="<?php echo htmlspecialchars($row['name']); ?>">
                </div>
                <div class="p-info">
                    <div>
                        <span class="p-cat"><?php echo $row['category']; ?></span>
                        <h4><?php echo $row['name']; ?></h4>
                        <div class="price">₹<?php echo number_format($row['price'], 2); ?></div>
                    </div>
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="select-opt">View Details</a>
                    <button onclick="notifyCartAdd()" style="width: 100%; background: #222; color: #fff; padding: 12px; border: none; border-radius: 5px; margin-top: 10px; cursor: pointer; font-weight: bold; font-family: 'Montserrat', sans-serif; display: flex; align-items: center; justify-content: center; gap: 8px;">
    <i class="fa fa-cart-plus"></i> Add to Cart
</button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>
<section class="newsletter" style="background: #1ab394; padding: 60px 0; color: white; text-align: center;">
    <div class="container">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 36px; margin-bottom: 10px;">Subscribe to Our Newsletter</h2>
        <p style="margin-bottom: 30px; opacity: 0.9;">Get the latest updates on new collections and infinite deals.</p>
        <form class="news-form" action="javascript:void(0);" onsubmit="subscribeAlert()" style="display: flex; justify-content: center; gap: 0;">
    <input type="email" id="newsEmail" placeholder="Enter your email address" required style="padding: 15px 25px; border-radius: 50px 0 0 50px; border: none; width: 350px; outline: none;">
    <button type="submit" style="background: #222; color: white; border: none; padding: 15px 35px; border-radius: 0 50px 50px 0; font-weight: 700; cursor: pointer; transition: 0.3s;">Subscribe</button>
</form>
    </div>
</section>
<footer class="footer-main" style="background: #111; color: #fff; padding: 80px 0 30px; margin-top: 0;">
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
        <div class="footer-col">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 28px; margin-bottom: 20px;">
                Infinity<span style="font-family: 'Montserrat', sans-serif; font-weight: 300; color: #888;">Cart</span><span style="color: #1ab394;">.</span>
            </h3>
            <p style="color: #888; font-size: 14px; line-height: 1.8;">Your one-stop destination for infinite fashion and tech gear. Elevating your lifestyle with premium choices.</p>
        </div>
        <div class="footer-col">
            <h4 style="font-family: 'Playfair Display', serif; margin-bottom: 25px;">Quick Links</h4>
            <ul style="list-style: none; padding: 0;">
                <li><a href="shop.php" style="color: #888; text-decoration: none; font-size: 14px; display: block; margin-bottom: 12px;">Shop Now</a></li>
                <li><a href="about.php" style="color: #888; text-decoration: none; font-size: 14px; display: block; margin-bottom: 12px;">About Us</a></li>
                <li><a href="faq.php" style="color: #888; text-decoration: none; font-size: 14px; display: block; margin-bottom: 12px;">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4 style="font-family: 'Playfair Display', serif; margin-bottom: 25px;">Contact Info</h4>
            <p style="color: #888; font-size: 14px; margin-bottom: 10px;"><i class="fa fa-location-dot" style="color: #1ab394; margin-right: 10px;"></i> 123 Street, Hyderabad, India</p>
            <p style="color: #888; font-size: 14px;"><i class="fa fa-envelope" style="color: #1ab394; margin-right: 10px;"></i> support@infinitycart.com</p>
        </div>
    </div>
</footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  const swiper = new Swiper('.hero-slider', {
    loop: true,
    autoplay: { delay: 4000, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
  });
    function subscribeAlert() {
    var email = document.getElementById('newsEmail').value;
    alert("Success! " + email + " has been added to our mailing list. Thank you for subscribing to InfinityCart!");
    document.getElementById('newsEmail').value = ""; // Form clear chesthundhi
  }
</script>
    <div style="text-align: center; margin-top: 40px; border-top: 1px solid #333; padding-top: 20px; font-size: 13px; color: #777;">
        &copy; 2026 Infinity Cart. All Rights Reserved.
    </div>
</footer>

<div class="chat-btn" onclick="toggleChat()">
    <i class="fa fa-comments"></i>
</div>

<div class="chat-window" id="chatWindow">
    <div class="chat-header">
        <span>Infinity Support</span>
        <i class="fa fa-times" onclick="toggleChat()" style="cursor:pointer;"></i>
    </div>
    <div class="chat-body" id="chatBody">
        <div class="bot-msg">Hello! How can I help you today?</div>
    </div>
    <div class="chat-footer">
        <input type="text" id="userInput" placeholder="Type a message...">
        <button onclick="sendMessage()" style="background:none; border:none; color:#1ab394; margin-left:5px; cursor:pointer;"><i class="fa fa-paper-plane"></i></button>
    </div>
</div>

<script>
function toggleChat() {
    var win = document.getElementById('chatWindow');
    win.style.display = (win.style.display === 'flex') ? 'none' : 'flex';
}

function sendMessage() {
    var input = document.getElementById('userInput');
    var body = document.getElementById('chatBody');
    if(input.value.trim() === "") return;

    // User Message
    body.innerHTML += '<div class="user-msg">' + input.value + '</div>';
    
    // Simple Auto-Response Logic
    var msg = input.value.toLowerCase();
    input.value = "";
    
    setTimeout(() => {
        var reply = "I'm not sure about that. Can you please contact our support team?";
        if(msg.includes("order")) reply = "You can track your order in the 'My Orders' section.";
        else if(msg.includes("return")) reply = "We have a 7-day return policy.";
        else if(msg.includes("hello") || msg.includes("hi")) reply = "Hi there! How can I assist you?";

        body.innerHTML += '<div class="bot-msg">' + reply + '</div>';
        body.scrollTop = body.scrollHeight;
    }, 1000);
}
</script>
        </body>
</html>