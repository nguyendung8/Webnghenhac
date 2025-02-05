<?php
ob_start();
session_start();

// Include header.php file
include ('header.php');
include './database/DBController.php';

$user_id = $_SESSION['user_id'] ?? 1;
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// Tách từ khóa thành mảng để tìm kiếm theo từng từ
$keywords = explode(' ', $keyword);
$searchQuery = implode("%' OR `title` LIKE '%", $keywords);

// Truy vấn bài hát theo title và artist
$song_query = mysqli_query($conn, "SELECT * FROM `song` WHERE (`title` LIKE '%$searchQuery%' OR `artist` LIKE '%$searchQuery%')") or die('Query failed');
$songs = mysqli_fetch_all($song_query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả tìm kiếm</title>
</head>
<body>
    <div class="container mt-5">
        <h3 class="font-size-20 text-center mb-4">Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($keyword); ?>"</h3>
        <div class="d-flex flex-wrap mb-4" style="gap: 20px;">
            <?php if (count($songs) > 0): ?>
                <?php foreach ($songs as $song): ?>
                <div class="grid-item">
                    <div class="item py-2" style="width: 200px;">
                        <div class="product font-rale text-center">
                        <img style="min-height: 149px;" src="./assets/products/<?php echo $song['image']; ?>" alt="product1" class="img-fluid">
                        <div class="text-center">
                            <h6 style="height: 39px; margin-top: 10px; font-weight: bold;"><?php echo  $song['title'] ?? "Unknown";  ?></h6>
                            <audio style="width: -webkit-fill-available;" controls>
                                <source src="./assets/songs/<?php echo $song ['url'] ?>" type="audio/ogg">
                            </audio>
                        </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không tìm thấy bài hát nào phù hợp với từ khóa "<?php echo htmlspecialchars($keyword); ?>"</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
// Include footer.php file
include ('footer.php');
?>
