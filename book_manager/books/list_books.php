<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';

$books_per_page = 10;

$total_books_result = $conn->query("SELECT COUNT(*) AS total FROM books");
$total_books_row = $total_books_result->fetch_assoc();
$total_books = $total_books_row['total'];


$total_pages = ceil($total_books / $books_per_page);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$offset = ($page - 1) * $books_per_page;

$stmt = $conn->prepare("SELECT * FROM books ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $books_per_page);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách sách</title>
    <link rel="stylesheet" href="/book_manager/css/style.css">
    </head>
<body>
    <h2>📚 Danh sách Sách</h2>
    <a href="../index.php">← Về trang chủ</a> | 
    <a href="add_book.php">➕ Thêm sách</a><br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Tác giả</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><a href="book_detail.php?id=<?= $row["id"] ?>"><?= htmlspecialchars($row["title"]) ?></a></td>
            <td><?= htmlspecialchars($row["author"]) ?></td>
            <td>
                <a href="edit_book.php?id=<?= $row["id"] ?>">Sửa</a> |
                <a href="delete_book.php?id=<?= $row["id"] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
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
    <form action="search_books.php" method="GET">
        <input type="text" name="query" placeholder="Tìm sách..." required>
        <button type="submit">Tìm</button>
    </form>
</body>
</html>
