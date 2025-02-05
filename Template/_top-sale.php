<!-- Top Sale -->
<?php
$user_id = $_SESSION['user_id'] ?? null;

// Kết nối cơ sở dữ liệu
include './database/DBController.php';

// Lấy danh sách bài hát
if (!$user_id) {
    $select_product = mysqli_query($conn, "SELECT * FROM `song` WHERE isRestricted = 0 LIMIT 10") or die('Query failed');
} else {
    $select_product = mysqli_query($conn, "SELECT * FROM `song` LIMIT 10") or die('Query failed');
}
$selectProducts = mysqli_fetch_all($select_product, MYSQLI_ASSOC);

// Lấy danh sách bài hát đã yêu thích của người dùng
$favorites = [];
if ($user_id) {
    $fav_query = mysqli_query($conn, "SELECT songId FROM `favorite` WHERE userId = $user_id") or die('Query failed');
    $favorites = array_column(mysqli_fetch_all($fav_query, MYSQLI_ASSOC), 'songId');
}
?>

<section id="top-sale">
    <div class="container py-5">
        <h4 class="font-rubik font-size-20">Danh sách bài hát</h4>
        <hr>
        <!-- owl carousel -->
        <div class="owl-carousel owl-theme">
            <?php foreach ($selectProducts as $item) { ?>
                <div class="item py-2 mr-4">
                    <div class="product font-rale">
                        <img style="min-height: 149px;" src="./assets/products/<?php echo $item['image']; ?>" alt="product1" class="img-fluid">
                        <div class="text-center">
                            <h6 style="height: 39px; margin-top: 10px; font-weight: bold; display: flex; justify-content: center; align-items: center; gap: 10px;">
                                <?php echo $item['title'] ?? "Unknown"; ?>

                                <?php if ($user_id) { ?>
                                    <!-- Icon Trái Tim -->
                                    <i class="fa fa-heart favorite-icon" 
                                        data-song-id="<?php echo $item['id']; ?>" 
                                        style="cursor: pointer; font-size: 20px; <?php echo in_array($item['id'], $favorites) ? 'color: red;' : 'color: gray;'; ?>">
                                    </i>
                                <?php } ?>
                            </h6>

                            <audio style="width: -webkit-fill-available;" controls>
                                <source src="./assets/songs/<?php echo $item['url'] ?>" type="audio/ogg">
                            </audio>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- !owl carousel -->
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".favorite-icon").click(function() {
            var songId = $(this).data("song-id");
            var icon = $(this);

            $.ajax({
                url: "toggle_favorite.php",
                type: "POST",
                data: { songId: songId },
                success: function(response) {
                    if (response == "added") {
                        icon.css("color", "red");
                    } else if (response == "removed") {
                        icon.css("color", "gray");
                    }
                }
            });
        });
    });
</script>
