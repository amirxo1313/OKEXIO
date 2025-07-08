<?php
session_start();
$pdo = new PDO("mysql:host=db;dbname=user_db", "myuser", "mypassword");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: profile.php");
    } else {
        echo "<p class='error-message'>ایمیل یا رمز عبور اشتباه است!</p>";
    }
}
?>
<!-- کپی کامل HTML از login.html -->
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ورود</title>
    <link rel="stylesheet" href="/styles.min.css">
    <link rel="stylesheet" href="/sweper.min.css">
    <!-- سایر تگ‌های <head> از login.html -->
</head>
<body>
    <!-- کپی ساختار صفحه از login.html -->
    <div class="login-container"> <!-- کلاس‌ها باید از login.html کپی بشن -->
        <form method="post">
            <label>ایمیل:</label><input type="email" name="email" required><br>
            <label>رمز عبور:</label><input type="password" name="password" required><br>
            <button type="submit">ورود</button>
        </form>
        <a href="register.php">ثبت‌نام</a>
    </div>
    <!-- سایر عناصر HTML از login.html -->
</body>
</html>