<?php
include '../database/DBController.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

// Thống kê tổng số người dùng
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM `users` WHERE role='user'"))['count'];

// Tổng số bài hát
$total_songs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM `song`"))['count'];

// Thống kê bài hát được yêu thích nhất
$top_songs_query = mysqli_query($conn, "SELECT s.title, COUNT(f.songId) AS likes 
    FROM `favorite` f 
    JOIN `song` s ON f.songId = s.id 
    GROUP BY f.songId 
    ORDER BY likes DESC 
    LIMIT 5");
$top_songs = mysqli_fetch_all($top_songs_query, MYSQLI_ASSOC);

// Tổng số đánh giá và đánh giá trung bình
$total_feedbacks = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM `feedback`"))['count'];
$average_rating = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(rating) AS avg FROM `feedback`"))['avg'];

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="d-flex">
    <?php include 'admin_navbar.php'; ?>
    <div class="manage-container">
        <div class="bg-primary text-white text-center py-2 mb-4 shadow">
            <h1 class="mb-0">Quản Lý Bài Hát</h1>
        </div>       
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h3><?= $total_users; ?></h3>
                        <p>Tổng số người dùng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-warning text-white">
                    <div class="card-body">
                        <h3><?= $total_songs; ?></h3>
                        <p>Tổng số bài hát</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top bài hát yêu thích -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title text-center">Top 5 bài hát yêu thích</h5>
                <ul class="list-group">
                    <?php foreach ($top_songs as $song) : ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $song['title']; ?>
                            <span class="badge bg-primary rounded-pill"><?= $song['likes']; ?> ❤️</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Thống kê đánh giá -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h3><?= $total_feedbacks; ?></h3>
                        <p>Tổng số đánh giá</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center bg-secondary text-white">
                    <div class="card-body">
                        <h3><?= number_format($average_rating ?? 0, 1); ?> ⭐</h3>
                        <p>Đánh giá trung bình</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
