<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';

// Truy vấn danh sách tác giả
$authors_result = $conn->query("SELECT * FROM authors ORDER BY created_at DESC");

// Phân trang (có thể thêm điều kiện cho books, nếu cần)
$limit = 10; // Số lượng tác giả mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn tổng số tác giả
$total_result = $conn->query("SELECT COUNT(*) AS total FROM authors");
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $limit);

// Lấy danh sách tác giả theo phân trang
$authors_result = $conn->query("SELECT * FROM authors ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Tác giả</title>
    <link rel="stylesheet" href="/book_manager/css/style.css">
    </head>
<body>
    <?php include '../includes/header.php'; ?>

    <h2>📚 Danh sách Tác giả</h2>

    <a href="../index.php">← Về trang chủ</a> |
    <a href="add_author.php">➕ Thêm tác giả</a>
    <br><br>

    <form action="search_authors.php" method="GET">
        <input type="text" name="query" placeholder="Tìm tác giả..." required>
        <button type="submit">Tìm</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Tên tác giả</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = $authors_result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><a href="author_detail.php?id=<?= $row["id"] ?>"><?= htmlspecialchars($row["name"]) ?></a></td>
            <td>
                <a href="edit_author.php?id=<?= $row["id"] ?>">Sửa</a> |
                <a href="delete_author.php?id=<?= $row["id"] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="pagination">
        <a href="?page=1">&laquo; Đầu</a>
        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
        <?php } ?>
        <a href="?page=<?= $total_pages ?>">Cuối &raquo;</a>
    </div>
</body>
</html>
