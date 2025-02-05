<?php
session_start();
include ('header.php');
include './database/DBController.php';

// Kiểm tra nếu người dùng chưa đăng nhập
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<p class='text-center'>Bạn cần đăng nhập để xem danh sách bài hát yêu thích.</p>";
    include ('footer.php');
    exit;
}

// Lấy danh sách bài hát yêu thích của user hiện tại
$fav_query = mysqli_query($conn, "
    SELECT s.* FROM `favorite` f 
    JOIN `song` s ON f.songId = s.id 
    WHERE f.userId = $user_id
") or die('Query failed');
$fav_songs = mysqli_fetch_all($fav_query, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Hát Yêu Thích</title>
</head>
<body>
    <div class="container mt-5">
        <h4 class="mb-3">Danh Sách Bài Hát Yêu Thích</h4>

        <!-- Danh sách bài hát yêu thích -->
        <div class="d-flex flex-wrap mb-3" style="gap: 20px;">
            <?php if (count($fav_songs) > 0): ?>
                <?php foreach ($fav_songs as $song): ?>
                    <div class="border p-3" style="width: 200px;">
                        <div class="text-center">
                            <img src="./assets/products/<?php echo $song['image'] ?? 'default.jpg'; ?>" alt="Song Image" class="img-fluid" style="height: 150px;">
                            <h6 class="mt-2"><?php echo $song['title']; ?></h6>
                            <p class="text-muted"><?php echo $song['artist']; ?></p>
                            <audio style="width: 100%;" controls>
                                <source src="./assets/songs/<?php echo $song['url']; ?>" type="audio/mp3">
                                Your browser does not support the audio element.
                            </audio>

                            <!-- Nút xóa khỏi yêu thích -->
                            <button class="btn btn-danger btn-sm remove-favorite mt-1" data-song-id="<?php echo $song['id']; ?>">
                                Xóa khỏi yêu thích
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không có bài hát yêu thích nào.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".remove-favorite").click(function() {
                var songId = $(this).data("song-id");
                var card = $(this).closest(".border");

                $.ajax({
                    url: "remove_favorite.php",
                    type: "POST",
                    data: { songId: songId },
                    success: function(response) {
                        if (response == "removed") {
                            card.fadeOut(300, function() { $(this).remove(); });
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php include ('footer.php'); ?>
