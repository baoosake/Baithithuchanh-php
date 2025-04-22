<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit;
}
include '../includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: list_books.php");
        exit;
    } else {
        echo "Có lỗi xảy ra khi xóa sách.";
    }
} else {
    header("Location: list_books.php");
    exit;
}
