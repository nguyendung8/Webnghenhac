<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang chủ</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="./css/main.css">
   <style>
      .list-cate {
         text-align: center;
         font-size: 20px;
         display: flex;
         gap: 10px;
         justify-content: center;
         padding-bottom: 20px;
      }
      .slideshow-container {
         position: relative;
         max-width: 800px;
         margin: 0 auto;
         overflow: hidden; /* Để ẩn phần ngoài khung hình ảnh */
      }
      .slide {
         display: none;
         animation: fade 2s ease-in-out infinite; /* Sử dụng animation để thêm hiệu ứng lướt sang */
      }
      @keyframes fade {
         0%, 100% {
            opacity: 0;
         }
         25%, 75% {
            opacity: 1;
         }
      }
      .slide img {
         width: 100%;
         height: 485px;
         border-radius: 9px;
      }
      .banner {
         min-height: 70vh;
         background:linear-gradient(rgba(0,0,0,.2), rgba(0,0,0,.2)), url(./images/banner.jpg) no-repeat;
         background-size: cover;
         background-position: center;
         display: flex;
         align-items: center;
         justify-content: center;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home banner">

   <div class="content">
      <div class="slideshow-container">
         <div class="slide fade">
            <img src="./images/slider1.jpg" alt="slide 1">
         </div>
         <div class="slide fade">
            <img src="./images/slider2.jpg" alt="slide 2">
         </div>
         <div class="slide fade">
            <img src="./images/slider3.jpg" alt="slide 3">
         </div>
         <div class="slide fade">
            <img src="./images/slider4.jpg" alt="slide 3">
         </div>
         <div class="slide fade">
            <img src="./images/slider5.jpg" alt="slide 3">
         </div>
         <div class="slide fade">
            <img src="./images/slider6.jpg" alt="slide 3">
         </div>
         <div class="slide fade">
            <img src="./images/slider7.jpg" alt="slide 3">
         </div>
      </div>
      <!-- <h3>Mỗi ngày một quyển sách.</h3>
      <p>Những quyển sách đều mang trong mình những bài học ý nghĩa, những trải nghiệm đáng giá.</p>
      <a href="about.php" class="white-btn">Khám phá thêm</a> -->
   </div>

</section>

<section class="products">

   <h1 class="title">Danh sách bài hát</h1>
   <div class="list-cate">
      <?php  
         $select_categoriess = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
         if(mysqli_num_rows($select_categoriess) > 0){
            while($fetch_categoriess = mysqli_fetch_assoc($select_categoriess)){
      ?>
                  <a href="?cate_id=<?php echo $fetch_categoriess['id']; ?> "><?php echo $fetch_categoriess['cate_name']; ?></a>
      <?php
            }
         }else{
            echo '<p class="empty">Chưa có danh mục nào!</p>';
         }
      ?>
   </div>
   <div class="box-container">
      <?php
      if(isset($_GET['cate_id'])) {
         $cate_id = $_GET['cate_id'];
      } else {
         $cate_id = 6;
      }
         $select_products = mysqli_query($conn, "SELECT s.* FROM songs s JOIN categories c ON s.cate_id = c.id  WHERE cate_id = $cate_id") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
         <div class="box">
            <img width="200px" height="207px" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
            <div class="name"><?php echo $fetch_products['name']; ?></div>
            <div class="book-action">
               <a href="song_detail.php?song_id=<?php echo $fetch_products['id'] ?>" class="view-book" >Xem thông tin bài hát</a>
               <audio  style="width: 254px;" controls>
                  <source src="./songs/<?php echo $fetch_products['link_path']  ?>" type="audio/ogg">
               </audio>
            </div>
         </div>
      <?php
            }
         }else{
            echo '<p class="empty">Chưa có bài hát nào!</p>';
         }
      ?>
   </div>

</section>

<section class="home-contact">
   <div class="content">
      <h3>Bạn có thắc mắc?</h3>
      <p>Hãy để lại những điều bạn còn thắc mắc, băn khoăn hay muốn chia sẻ thêm về những quyển truyện cho chúng mình tại đây để chúng mình có thể giải đáp giúp bạn</p>
      <a href="contact.php" class="white-btn">Liên hệ</a>
   </div>

</section>

<?php include 'footer.php'; ?>
<script src="js/script.js"></script>
<script src="./js/slide_show.js" ></script>

</body>
</html>