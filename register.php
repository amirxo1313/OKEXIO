<?php
$pdo = new PDO("mysql:host=db;dbname=user_db", "myuser", "mypassword");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);
        echo "<p class='success-message'>ثبت‌نام با موفقیت انجام شد!</p>";
    } catch (PDOException $e) {
        echo "<p class='error-message'>خطا: " . $e->getMessage() . "</p>";
    }
}
?>
<!-- کپی کامل HTML از register.html -->
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ثبت‌نام</title>
    <link rel="stylesheet" href="/styles.min.css">
    <link rel="stylesheet" href="/sweper.min.css">
    <!-- سایر تگ‌های <head> از register.html -->
</head>
<body>
    <!-- کپی ساختار صفحه از register.html -->
    <div class="register-container"> <!-- کلاس‌ها باید از register.html کپی بشن -->
        <form method="post">
            <label>نام کاربری:</label><input type="text" name="username" required><br>
            <label>ایمیل:</label><input type="email" name="email" required><br>
            <label>رمز عبور:</label><input type="password" name="password" required><br>
            <button type="submit">ثبت‌نام</button>
        </form>
        <a href="login.php">ورود</a>
    </div>
    <!-- سایر عناصر HTML از register.html -->
</body>
</html>