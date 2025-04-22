<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';

$message = '';
$id = $_GET['id'] ?? null;

if ($id) {
    // Lấy thông tin sách
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = trim($_POST["title"]);
        $description = trim($_POST["description"]);
        $author = trim($_POST["author"]);

        if (!empty($title) && !empty($author)) {
            $stmt = $conn->prepare("UPDATE books SET title = ?, description = ?, author = ? WHERE id = ?");
            $stmt->bind_param("sssi", $title, $description, $author, $id);

            if ($stmt->execute()) {
                $message = "Sách đã được sửa thành công!";
            } else {
                $message = "Có lỗi xảy ra khi sửa sách.";
            }
        } else {
            $message = "Vui lòng nhập đủ thông tin tiêu đề và tác giả.";
        }
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
    <title>Sửa Sách</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<head>
<link rel="stylesheet" href="/book_manager/css/style.css">
</head>

<body>
    <?php include '../includes/header.php'; ?>

    <h2>✏️ Sửa Sách</h2>

    <a href="list_books.php">← Quay lại danh sách sách</a><br><br>

    <form method="POST" action="">
        <label>Tiêu đề sách:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br><br>

        <label>Mô tả:</label><br>
        <textarea name="description"><?= htmlspecialchars($book['description']) ?></textarea><br><br>

        <label>Tác giả:</label><br>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br><br>

        <input type="submit" value="Sửa sách">
    </form>

    <!-- Hiển thị thông báo -->
    <p style="color: <?= $message === 'Sách đã được sửa thành công!' ? 'green' : 'red'; ?>;">
        <?php echo $message; ?>
    </p>
</body>
</html>
