<?php
session_start();
include './database/DBController.php';



$user_id = $_SESSION['user_id'];
$song_id = $_POST['songId'] ?? null;
$content = trim($_POST['content']);
$rating = $_POST['rating'] ?? null;

if (!$song_id || !$rating || $rating < 1 || $rating > 5) {
    die("Dữ liệu không hợp lệ!");
}

// Kiểm tra xem user đã đánh giá chưa
$check_query = mysqli_query($conn, "SELECT * FROM `feedback` WHERE userId = $user_id AND songId = $song_id");
if (mysqli_num_rows($check_query) > 0) {
    die("Bạn đã đánh giá bài hát này!");
}

// Thêm đánh giá vào database
$query = "INSERT INTO `feedback` (userId, songId, content, rating) VALUES ($user_id, $song_id, '$content', $rating)";
if (mysqli_query($conn, $query)) {
    echo "Đánh giá thành công!";
} else {
    echo "Lỗi khi gửi đánh giá!";
}
?>
