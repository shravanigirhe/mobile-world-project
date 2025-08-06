<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password_input = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user'] = $username;
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ Incorrect password.";
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: #f0f4f8;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .login-card {
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      width: 100%;
      max-width: 400px;
    }

    h2 {
      text-align: center;
      margin-bottom: 24px;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background-color: #f9f9f9;
      font-size: 15px;
    }

    button {
      width: 100%;
      padding: 12px;
      background-color: #38bdf8;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0ea5e9;
    }

    .error {
      color: #e11d48;
      background: #fef2f2;
      border: 1px solid #fca5a5;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 16px;
      text-align: center;
      font-size: 14px;
    }

    .link {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
    }

    .link a {
      color: #3b82f6;
      text-decoration: none;
    }

    .link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .login-card {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="login-card">
  <h2>Login</h2>

  <?php if (!empty($error)) : ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="text" name="username" placeholder="Enter username" required />
    <input type="password" name="password" placeholder="Enter password" required />
    <button type="submit">Login</button>
  </form>

  <div class="link">
    <p>New user? <a href="register.php">Register here</a></p>
  </div>
</div>

</body>
</html>
