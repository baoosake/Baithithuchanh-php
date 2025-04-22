<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../includes/db.php';

$message = ''; // Thông báo từ các hành động

// Xử lý form thêm sách
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $author = trim($_POST["author"]);

    if (!empty($title) && !empty($author)) {
        $stmt = $conn->prepare("INSERT INTO books (title, description, author) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $author);

        if ($stmt->execute()) {
            $message = "Sách đã được thêm thành công!";
        } else {
            $message = "Có lỗi xảy ra khi thêm sách.";
        }
    } else {
        $message = "Vui lòng nhập đủ thông tin tiêu đề và tác giả.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sách</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h2>➕ Thêm Sách Mới</h2>

    <a href="list_books.php">← Quay lại danh sách sách</a><br><br>
    <form method="POST" action="">
        <label>Tiêu đề sách:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Mô tả:</label><br>
        <textarea name="description"></textarea><br><br>

        <label>Tác giả:</label><br>
        <input type="text" name="author" required><br><br>

        <input type="submit" value="Thêm sách">
    </form>
<head>
<link rel="stylesheet" href="/book_manager/css/style.css">
</head>

    <p><?php echo $message; ?></p>
</body>
</html>
