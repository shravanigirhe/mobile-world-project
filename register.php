<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable exception mode
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO users (name, mobile, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $mobile, $username, $password);
        $stmt->execute();

        // Auto login
        $_SESSION['user'] = $username;
        $_SESSION['name'] = $name;

        // Redirect to homepage
        header("Location: index.php");
        exit();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $message = "<div class='error'>❌ Username already exists.<br>Already have an account? <a href='login.php'>Login now</a></div>";
        } else {
            $message = "<div class='error'>❌ Registration failed. Please try again later.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body {
      margin: 0; padding: 0;
      display: flex; align-items: center; justify-content: center;
      height: 100vh; background: #f0f4f8;
    }
    .register-card {
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      max-width: 420px;
      width: 100%;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    input {
      width: 100%;
      padding: 12px;
      margin-bottom: 16px;
      border: 1px solid #ccc;
      border-radius: 6px;
      background: #f9f9f9;
      font-size: 15px;
    }
    button {
      width: 100%;
      padding: 12px;
      background-color: #22c55e;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background-color: #16a34a;
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
    .error a {
      color: #3b82f6;
      text-decoration: none;
    }
    .error a:hover {
      text-decoration: underline;
    }
    @media (max-width: 480px) {
      .register-card {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="register-card">
  <h2>Register</h2>
  <?= $message ?>
  <form method="post">
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="text" name="mobile" placeholder="Mobile Number" required pattern="\d{10}" title="Enter 10 digit mobile number" />
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Register</button>
  </form>
</div>

</body>
</html>
