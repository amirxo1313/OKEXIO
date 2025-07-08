<?php
session_start();
$pdo = new PDO("mysql:host=db;dbname=user_db", "myuser", "mypassword");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$user = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])) {
    $new_email = $_POST["email"];
    $new_password = !empty($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : $user["password"];
    $btc_balance = floatval($_POST["btc_balance"]);
    $eth_balance = floatval($_POST["eth_balance"]);
    $stmt = $pdo->prepare("UPDATE users SET email = ?, password = ?, btc_balance = ?, eth_balance = ? WHERE id = ?");
    $stmt->execute([$new_email, $new_password, $btc_balance, $eth_balance, $_SESSION["user_id"]]);
    echo "<p class='success-message'>اطلاعات به‌روزرسانی شد!</p>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_notification"])) {
    $message = $_POST["message"];
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->execute([$_SESSION["user_id"], $message]);
    echo "<p class='success-message'>اطلاعیه اضافه شد!</p>";
}

$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION["user_id"]]);
$notifications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>پنل کاربری</title>
    <link rel="stylesheet" href="/styles.min.css">
    <link rel="stylesheet" href="/sweper.min.css">
    <!-- کپی تگ‌های <head> از register.html یا login.html -->
</head>
<body>
    <div class="profile-container"> <!-- کلاس‌ها باید با register.html یا login.html هماهنگ بشن -->
        <h2>پنل کاربری - خوش آمدید <?php echo htmlspecialchars($user["username"]); ?></h2>
        <form method="post">
            <input type="hidden" name="update_profile" value="1">
            <label>ایمیل:</label><input type="email" name="email" value="<?php echo htmlspecialchars($user["email"]); ?>" required><br>
            <label>رمز جدید (خالی بگذارید اگر نمی‌خواهید تغییر کند):</label><input type="password" name="password"><br>
            <label>موجودی بیت‌کوین:</label><input type="number" step="0.00000001" name="btc_balance" value="<?php echo htmlspecialchars($user["btc_balance"]); ?>" required><br>
            <label>موجودی اتریوم:</label><input type="number" step="0.00000001" name="eth_balance" value="<?php echo htmlspecialchars($user["eth_balance"]); ?>" required><br>
            <button type="submit">به‌روزرسانی</button>
        </form>
        <h3>افزودن اطلاعیه</h3>
        <form method="post">
            <input type="hidden" name="add_notification" value="1">
            <label>پیام اطلاعیه:</label><textarea name="message" required></textarea><br>
            <button type="submit">افزودن اطلاعیه</button>
        </form>
        <h3>اطلاعیه‌ها</h3>
        <ul>
            <?php foreach ($notifications as $notification): ?>
                <li><?php echo htmlspecialchars($notification["message"]) . " (" . $notification["created_at"] . ")"; ?></li>
            <?php endforeach; ?>
        </ul>
        <a href="logout.php">خروج</a>
    </div>
</body>
</html>