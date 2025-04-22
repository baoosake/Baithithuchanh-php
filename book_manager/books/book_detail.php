<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}

include '../includes/db.php';

$id = $_GET['id'] ?? null;
$book = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    
    if (!$book) {
        header("Location: list_books.php");
        exit;
    }
} else {
    header("Location: list_books.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Sách</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<head>
<link rel="stylesheet" href="/book_manager/css/style.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <h2>📖 Chi tiết Sách</h2>

    <a href="list_books.php">← Quay lại danh sách sách</a><br><br>

    <h3><?= htmlspecialchars($book['title']) ?></h3>
    <p><strong>Tác giả:</strong> <?= htmlspecialchars($book['author']) ?></p>
    <p><strong>Mô tả:</strong> <?= htmlspecialchars($book['description']) ?></p>
    <p><strong>Ngày tạo:</strong> <?= $book['created_at'] ?></p>
</body>
</html>
