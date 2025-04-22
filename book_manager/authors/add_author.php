<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);

    if (!empty($title)) {
        $stmt = $conn->prepare("INSERT INTO books (title, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $description);

        if ($stmt->execute()) {
            $message = "Sách đã được thêm thành công!";
        } else {
            $message = "Có lỗi xảy ra khi thêm sách.";
        }
    } else {
        $message = "Vui lòng nhập đủ thông tin tên sách.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sách</title>
    <link rel="stylesheet" href="/book_manager/css/style.css">
    </head>
<body>
    <?php include '../includes/header.php'; ?>

    <h2>➕ Thêm Sách Mới</h2>

    <a href="list_books.php">← Quay lại danh sách sách</a><br><br>

    <form method="POST" action="">
        <label>Tên sách:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Mô tả:</label><br>
        <textarea name="description"></textarea><br><br>

        <input type="submit" value="Thêm sách">
    </form>
<head>
    <link rel="stylesheet" href="/css/styke.css">
</head>
    <p><?php echo $message; ?></p>
</body>
</html>
