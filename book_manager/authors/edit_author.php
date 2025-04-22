<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';

$message = '';
$id = $_GET['id'] ?? null;
$author = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM authors WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $author = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST["name"]);
        $bio = trim($_POST["bio"]);

        if (!empty($name)) {
    
            $stmt = $conn->prepare("UPDATE authors SET name = ?, bio = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $bio, $id);

            if ($stmt->execute()) {
                $message = "Tác giả đã được sửa thành công!";
            } else {
                $message = "Có lỗi xảy ra khi sửa tác giả.";
            }
        } else {
            $message = "Vui lòng nhập đủ thông tin tên tác giả.";
        }
    }
} else {
    header("Location: list_authors.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Tác giả</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <h2>✏️ Sửa Tác giả</h2>

    <a href="list_authors.php">← Quay lại danh sách tác giả</a><br><br>

    <form method="POST" action="">
        <label>Tên tác giả:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($author['name']) ?>" required><br><br>

        <label>Tiểu sử:</label><br>
        <textarea name="bio"><?= htmlspecialchars($author['bio']) ?></textarea><br><br>

        <input type="submit" value="Sửa tác giả">
    </form>
<head>
<link rel="stylesheet" href="/book_manager/css/style.css">
</head>


    <p><?php echo $message; ?></p>
</body>
</html>
