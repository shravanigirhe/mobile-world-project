<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, sans-serif;
    }
    body {
      margin: 0;
      background: linear-gradient(to right, #f8fafc, #e0f2fe);
      padding: 20px;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 10px;
      color: #0f172a;
    }
    .nav {
      text-align: center;
      margin-bottom: 20px;
    }
    .nav a {
      display: inline-block;
      margin: 0 10px;
      padding: 10px 18px;
      background: #38bdf8;
      color: #fff;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
      transition: 0.3s;
    }
    .nav a:hover {
      background: #0ea5e9;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      padding: 14px;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
    }
    th {
      background-color: #f1f5f9;
      color: #1e293b;
    }
    td:last-child, th:last-child {
      text-align: right;
    }
    .total-row {
      font-weight: bold;
      background-color: #f0fdf4;
    }
    .empty {
      text-align: center;
      padding: 40px 0;
      font-size: 18px;
      color: #64748b;
    }
    .checkout {
      text-align: right;
      margin-top: 20px;
    }
    .checkout button {
      padding: 12px 20px;
      font-size: 16px;
      background-color: #10b981;
      border: none;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .checkout button:hover {
      background-color: #059669;
    }

    @media (max-width: 640px) {
      table, thead, tbody, tr, th, td {
        display: block;
      }
      thead {
        display: none;
      }
      td {
        padding: 10px;
        text-align: right;
        position: relative;
      }
      td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        top: 10px;
        font-weight: bold;
        text-align: left;
      }
      .checkout {
        text-align: center;
      }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Your Cart</h2>

  <div class="nav">
    <a href="index.php">🛍 Continue Shopping</a>
    <a href="logout.php">🔒 Logout</a>
  </div>

  <?php if (empty($cart)): ?>
    <div class="empty">🛒 Your cart is empty.</div>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Qty</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart as $id => $qty): 
          $res = $conn->query("SELECT * FROM mobiles WHERE id=$id");
          $row = $res->fetch_assoc();
          $subtotal = $row['price'] * $qty;
          $total += $subtotal;
        ?>
        <tr>
          <td data-label="Product"><?php echo htmlspecialchars($row['name']); ?></td>
          <td data-label="Qty"><?php echo $qty; ?></td>
          <td data-label="Price">₹<?php echo number_format($row['price']); ?></td>
          <td data-label="Subtotal">₹<?php echo number_format($subtotal); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="total-row">
          <td colspan="3">Total</td>
          <td>₹<?php echo number_format($total); ?></td>
        </tr>
      </tbody>
    </table>

    <div class="checkout">
      <button onclick="alert('Checkout feature coming soon!')">🧾 Proceed to Checkout</button>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
