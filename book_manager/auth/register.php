<?php
include '../includes/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Kiểm tra trùng username
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "Tên đăng nhập đã tồn tại!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            $message = "Đăng ký thành công!";
        } else {
            $message = "Lỗi: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Đăng ký</h2>
    <form method="POST" action="">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Mật khẩu:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Đăng ký">
    </form>
    <p><?php echo $message; ?></p>
    <p><a href="login.php">Đã có tài khoản? Đăng nhập</a></p>
</body>
</html>
