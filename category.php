<?php
session_start();
include ('header.php');
include './database/DBController.php';

// Lấy danh sách danh mục
$categories_query = mysqli_query($conn, "SELECT * FROM `category`") or die('Query failed');
$categories = mysqli_fetch_all($categories_query, MYSQLI_ASSOC);

// Kiểm tra xem có danh mục nào được chọn không
$selected_category_id = isset($_GET['cate_id']) ? (int) $_GET['cate_id'] : null;

// Xác định điều kiện truy vấn bài hát
$user_id = $_SESSION['user_id'] ?? null;
$song_condition = $user_id ? "" : "AND `isRestricted` = 0";

// Lấy danh sách bài hát thuộc danh mục được chọn
if ($selected_category_id) {
    $songs_query = mysqli_query($conn, "SELECT * FROM `song` WHERE `categoryId` = $selected_category_id $song_condition") or die('Query failed');
    $songs = mysqli_fetch_all($songs_query, MYSQLI_ASSOC);
} else {
    $songs = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Bài Hát</title>
</head>
<body>
    <div class="container mt-5">

        <h4 class="mb-3">Bài hát trong danh mục: <strong><?php echo $categories[array_search($selected_category_id, array_column($categories, 'id'))]['name']; ?></strong></h4>
        <!-- Danh sách bài hát -->
        <div class="d-flex flex-wrap mb-3" style="gap: 20px;">
                <br>
                <?php if (count($songs) > 0): ?>
                    <?php foreach ($songs as $song): ?>
                        <div class="border p-3" style="width: 200px;">
                            <div class="text-center">
                                <img src="./assets/products/<?php echo $song['image'] ?? 'default.jpg'; ?>" alt="Song Image" class="img-fluid" style="height: 150px;">
                                <h6 class="mt-2"><?php echo $song['title']; ?></h6>
                                <p class="text-muted"><?php echo $song['artist']; ?></p>
                                <audio style="width: 100%;" controls>
                                    <source src="./assets/songs/<?php echo $song['url']; ?>" type="audio/mp3">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Không có bài hát nào trong danh mục này.</p>
                <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php include ('footer.php'); ?>
