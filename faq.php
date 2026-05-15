<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ | Infinity Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .faq-container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .faq-item { background: #fff; border: 1px solid #eee; margin-bottom: 10px; border-radius: 5px; }
        .faq-question { padding: 15px; cursor: pointer; font-weight: 600; display: flex; justify-content: space-between; background: #f9f9f9; }
        .faq-answer { padding: 15px; display: none; border-top: 1px solid #eee; color: #666; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="faq-container">
        <h1 style="text-align: center; color: #1ab394;">Frequently Asked Questions</h1>
        <hr><br>
        
       <div class="faq-item">
    <div class="faq-question">How do I track my order? <i class="fa fa-chevron-down"></i></div>
    <div class="faq-answer">You can track your order by logging into your account and visiting the 'My Orders' section. You will see the real-time status of your purchase there.</div>
</div>

<div class="faq-item">
    <div class="faq-question">What is the return policy? <i class="fa fa-chevron-down"></i></div>
    <div class="faq-answer">Our return policy allows you to return items within 7 days of delivery. Please ensure that the products are in their original packaging and condition.</div>
</div>

<div class="faq-item">
    <div class="faq-question">Is Cash on Delivery available? <i class="fa fa-chevron-down"></i></div>
    <div class="faq-answer">Yes, we support Cash on Delivery (COD) for most locations across the country. You can select this option during the checkout process.</div>
</div>

    <script>
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => {
                const answer = item.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>