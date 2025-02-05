<?php
session_start();
include './database/DBController.php';

$user_id = $_SESSION['user_id'] ?? null;
$song_id = $_POST['songId'] ?? null;

if (!$user_id || !$song_id) {
    echo "error";
    exit;
}

// Xóa bài hát khỏi danh sách yêu thích
mysqli_query($conn, "DELETE FROM `favorite` WHERE userId = $user_id AND songId = $song_id") or die('Query failed');
echo "removed";
?>
