<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'mobile_world';

$conn = mysqli_connect($host, $user, $pass, $dbname);

// Check connection
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// ✅ Show message only when db.php is accessed directly in browser
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    echo "✅ Database connected successfully";
}
?>
