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

// Lấy danh sách đánh giá trung bình của mỗi bài hát
$ratings = [];
$rating_query = mysqli_query($conn, "SELECT songId, AVG(rating) as avg_rating FROM `feedback` GROUP BY songId") or die('Query failed');
while ($row = mysqli_fetch_assoc($rating_query)) {
    $ratings[$row['songId']] = round($row['avg_rating'], 1);
}
?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap JS (Bundle with Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<section id="top-sale">
    <div class="container py-5">
        <h4 class="font-rubik font-size-20">Danh sách bài hát</h4>
        <hr>
        <!-- owl carousel -->
        <div class="owl-carousel owl-theme">
            <?php foreach ($selectProducts as $item) { ?>
                <div class="item py-2 mr-4">
                    <div class="product font-rale">
                        <img style="min-height: 177px;" src="./assets/products/<?php echo $item['image']; ?>" alt="product1" class="img-fluid">
                        <div class="text-center">
                            <h6 style="height: 39px; margin-top: 10px; font-weight: bold; display: flex; justify-content: center; align-items: center; gap: 10px;">
                                <?php echo $item['title'] ?? "Unknown"; ?>

                                <?php if ($user_id) { ?>
                                    <!-- Icon Trái Tim -->
                                    <i class="fa fa-heart favorite-icon" 
                                        data-song-id="<?php echo $item['id']; ?>" 
                                        style="cursor: pointer; font-size: 20px; <?php echo in_array($item['id'], $favorites) ? 'color: red;' : 'color: gray;'; ?>">
                                    </i>

                                    <!-- Icon Đánh Giá -->
                                    <i class="fa fa-star rating-icon" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#ratingModal"
                                        data-song-id="<?php echo $item['id']; ?>"
                                        style="cursor: pointer; font-size: 20px; color: gold;">
                                    </i>
                                <?php } ?>
                            </h6>

                            <!-- Hiển thị đánh giá trung bình -->
                            <p class="text-muted">Đánh giá: <?php echo $ratings[$item['id']] ?? "Chưa có"; ?> ★</p>

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

<!-- Modal Đánh Giá -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ratingModalLabel">Đánh giá bài hát</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ratingForm">
                    <input type="hidden" id="songId" name="songId">
                    <div class="mb-3">
                        <label class="form-label">Đánh giá (1-5):</label>
                        <input type="number" class="form-control" name="rating" min="1" max="5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nhận xét:</label>
                        <textarea class="form-control" name="content" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Toggle favorite
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

        // Open rating modal and set songId
        $(".rating-icon").click(function() {
            var songId = $(this).data("song-id");
            $("#songId").val(songId);
        });

        // Submit rating form
        $("#ratingForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "submit_feedback.php",
                type: "POST",
                data: $("#ratingForm").serialize(),
                success: function(response) {
                    alert(response);
                    $("#ratingModal").modal("hide");
                    location.reload();
                }
            });
        });
    });
</script>
