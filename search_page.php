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
   <title>Trang tìm kiếm</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="./css/main.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .title-search {
         font-size: 35px;
         text-align: center;
         margin-top: 20px;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<div>
   <h3 class="title-search">Trang tìm kiếm</h3>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="Tìm bài hát..." class="box"  value=" <?php if(isset($_POST['submit'])) echo($_POST['search'])?>">
      <input type="submit" name="submit" value="Tìm kiếm" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">
      <?php
         if(isset($_POST['submit'])){
            $search_item = trim($_POST['search']);
            $select_products = mysqli_query($conn, "SELECT * FROM `songs` WHERE name LIKE '%{$search_item}%'") or die('query failed');
            if(mysqli_num_rows($select_products) > 0){
               while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
                  <div class="box">
                     <img width="180px" height="207px" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
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
               echo '<p class="empty">Không tìm thấy kết quả phù hợp với yêu cầu tìm kiếm cảu bạn!</p>';
            }
         }else{
            echo '<p class="empty"">Hãy tìm kiếm gì đó!</p>';
         }
      ?>
   </div>
  

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>