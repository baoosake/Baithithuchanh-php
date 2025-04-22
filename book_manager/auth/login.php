<?php
session_start();
include '../includes/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            header("Location: ../index.php");
            exit;
        } else {
            $message = "Sai mật khẩu!";
        }
    } else {
        $message = "Tên đăng nhập không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method="POST" action="">
        <label>Tên đăng nhập:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Mật khẩu:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Đăng nhập">
    </form>
    <p><?php echo $message; ?></p>
    <p><a href="register.php">Chưa có tài khoản? Đăng ký</a></p>
</body>
</html>
