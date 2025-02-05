<!-- Top Sale -->
<?php
    $user_id = @$_SESSION['user_id'];

    if (!$user_id) {
        $select_product =  mysqli_query($conn, "SELECT * FROM `song` WHERE isRestricted = 0 limit 10") or die('Query failed');
    } else {
        $select_product =  mysqli_query($conn, "SELECT * FROM `song` limit 10") or die('Query failed');
    }

    $selectProducts = mysqli_fetch_all($select_product, MYSQLI_ASSOC);

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
                        <h6 style="height: 39px; margin-top: 10px; font-weight: bold;"><?php echo  $item['title'] ?? "Unknown";  ?></h6>
                        <audio style="width: -webkit-fill-available;" controls>
                            <source src="./assets/songs/<?php echo $item ['url'] ?>" type="audio/ogg">
                        </audio>
                    </div>
                </div>
            </div>
            <?php } // closing foreach function ?>
        </div>
        <!-- !owl carousel -->
    </div>
</section>
<!-- !Top Sale -->