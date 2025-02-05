<?php
session_start();
include './database/DBController.php';

$user_id = $_SESSION['user_id'] ?? null;
$song_id = $_POST['songId'] ?? null;

if (!$user_id || !$song_id) {
    echo "error";
    exit;
}

// Kiểm tra xem bài hát đã yêu thích chưa
$check_fav = mysqli_query($conn, "SELECT * FROM `favorite` WHERE userId = $user_id AND songId = $song_id");

if (mysqli_num_rows($check_fav) > 0) {
    // Nếu đã yêu thích → Xóa khỏi danh sách
    mysqli_query($conn, "DELETE FROM `favorite` WHERE userId = $user_id AND songId = $song_id");
    echo "removed";
} else {
    // Nếu chưa yêu thích → Thêm vào danh sách
    mysqli_query($conn, "INSERT INTO `favorite` (userId, songId) VALUES ($user_id, $song_id)");
    echo "added";
}
?>
