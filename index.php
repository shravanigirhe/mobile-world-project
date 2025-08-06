<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $id = $_POST['mobile_id'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: cart.php");
    exit;
}

// Handle search
$search = $_GET['search'] ?? '';
$search_query = $conn->real_escape_string($search);
$sql = "SELECT * FROM mobiles";
if (!empty($search)) {
    $sql .= " WHERE name LIKE '%$search_query%'";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mobile World - Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    body {
      margin: 0;
      padding: 20px;
      background-color: #f9fafb;
    }

    .top-bar {
      background-color: #1e3a8a;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .top-bar a {
      color: white;
      text-decoration: none;
      margin-left: 15px;
      background-color: #3b82f6;
      padding: 8px 12px;
      border-radius: 6px;
      transition: 0.3s;
    }

    .top-bar a:hover {
      background-color: #2563eb;
    }

    .search-bar {
      display: flex;
      justify-content: center;
      margin: 30px 0;
    }

    .search-bar form {
      display: flex;
      background-color: white;
      border: 2px solid #3b82f6;
      border-radius: 50px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      overflow: hidden;
      width: 100%;
      max-width: 500px;
    }

    .search-bar input[type="text"] {
      flex: 1;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      outline: none;
    }

    .search-bar button {
      background-color: #3b82f6;
      color: white;
      border: none;
      padding: 0 25px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .search-bar button:hover {
      background-color: #2563eb;
    }

    @media (max-width: 480px) {
      .search-bar form {
        flex-direction: column;
        border-radius: 12px;
      }

      .search-bar button {
        width: 100%;
        padding: 12px;
      }
    }

    .products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
      gap: 20px;
    }

    @media (min-width: 1024px) {
      .products {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    @media (max-width: 768px) {
      .products {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 480px) {
      .products {
        grid-template-columns: repeat(1, 1fr);
      }
    }

    .product {
      background-color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      text-align: center;
      transition: transform 0.2s ease;
    }

    .product:hover {
      transform: translateY(-5px);
    }

    .product img {
      width: 100%;
      height: 180px;
      object-fit: contain;
      margin-bottom: 10px;
    }

    .product h4 {
      color: #1f2937;
      margin: 10px 0 5px;
      font-size: 18px;
    }

    .product p {
      color: #475569;
      font-size: 16px;
      margin-bottom: 15px;
    }

    .product form button {
      padding: 10px 16px;
      background-color: #10b981;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .product form button:hover {
      background-color: #059669;
    }
  </style>
</head>
<body>

<div class="top-bar">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋</h2>
  <div>
    <a href="cart.php">🛒 Cart</a>
    <a href="logout.php">🚪 Logout</a>
  </div>
</div>

<div class="search-bar">
  <form method="get">
    <input type="text" name="search" placeholder="🔍 Search for mobiles..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
  </form>
</div>

<div class="products">
  <?php if ($result && $result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="product">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
        <p>₹<?php echo number_format($row['price'], 2); ?></p>
        <form method="post">
          <input type="hidden" name="mobile_id" value="<?php echo $row['id']; ?>">
          <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="text-align:center;">No products found for "<strong><?php echo htmlspecialchars($search); ?></strong>".</p>
  <?php endif; ?>
</div>

</body>
</html>
