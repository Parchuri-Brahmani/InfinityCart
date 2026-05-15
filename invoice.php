<?php
session_start();
include("php/db.php");

if(!isset($_GET['order_id'])){
    header("Location: index.php");
    exit();
}

$o_id = (int)$_GET['order_id'];
$query = "SELECT * FROM orders WHERE id = $o_id";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);

if(!$order) { die("Invoice not found."); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $o_id; ?> | Infinity Cart</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #555; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; background: #fff; }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .invoice-header h2 { color: #1ab394; margin: 0; }
        .invoice-details { margin-bottom: 40px; }
        .table-items { width: 100%; text-align: left; border-collapse: collapse; }
        .table-items th { background: #f9f9f9; padding: 10px; border-bottom: 2px solid #eee; }
        .table-items td { padding: 10px; border-bottom: 1px solid #eee; }
        .total-row { text-align: right; font-weight: bold; font-size: 20px; color: #1ab394; padding-top: 20px; }
        .print-btn { background: #1ab394; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 20px; font-weight: bold; }
        @media print { .print-btn { display: none; } .invoice-box { border: none; box-shadow: none; } }
    </style>
</head>
<body>

<div style="text-align: center;">
    <button class="print-btn" onclick="window.print()"><i class="fa fa-print"></i> Print / Download PDF</button>
</div>

<div class="invoice-box">
    <div class="invoice-header">
        <div>
            <h2>Infinity Cart</h2>
            <p>Order Date: <?php echo date('d M Y', strtotime($order['order_date'] ?? 'now')); ?></p>
        </div>
        <div style="text-align: right;">
            <p><strong>Invoice #ORD-<?php echo $o_id; ?></strong></p>
            <p>Status: <?php echo $order['status']; ?></p>
        </div>
    </div>

    <div class="invoice-details">
        <p><strong>Billed To:</strong></p>
        <p><?php echo htmlspecialchars($order['customer_name']); ?><br>
        <?php echo htmlspecialchars($order['address']); ?><br>
        <?php echo htmlspecialchars($order['city'] ?? ''); ?> - <?php echo htmlspecialchars($order['pincode'] ?? ''); ?></p>
    </div>

    <table class="table-items">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Order Total (Including Shipping & Taxes)</td>
                <td style="text-align: right;">₹<?php echo number_format($order['total_amount'], 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total-row">
        Total Paid: ₹<?php echo number_format($order['total_amount'], 2); ?>
    </div>

    <p style="margin-top: 50px; text-align: center; font-size: 12px; color: #aaa;">
        This is a computer-generated invoice. No signature required.<br>
        Thank you for shopping with Infinity Cart!
    </p>
</div>

</body>
</html>