<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h2>Chào mừng, <?php echo $_SESSION["username"]; ?>!</h2>

    <p><a href="auth/logout.php">Đăng xuất</a></p>
    <p><a href="books/list_books.php">📚 Quản lý Sách</a></p>
    <p><a href="authors/list_authors.php">🖊️ Quản lý Tác giả</a></p>
</body>
</html>
<link rel="stylesheet" href="/book_manager/css/style.css">
