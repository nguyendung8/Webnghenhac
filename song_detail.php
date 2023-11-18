<?php

   include 'config.php';

   session_start();

   $user_id = $_SESSION['user_id']; //tạo session người dùng thường

   if(!isset($user_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }
   $song_id = $_GET['song_id'];

   $sql = "SELECT * FROM songs WHERE id = $song_id";
   $result = $conn->query($sql);
   $songItem = $result->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Xem thông tin bài hát</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
   <style>
      .view-book {
         padding: 15px;
      }
      .modal{
         width: 500px;
         margin: auto;
         border: 2px solid #eee;
         padding-bottom: 27px;
         box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
         border-radius: 5px;
      }
      .modal-container{
         background-color:#fff;
         text-align: center;
      }
      .songdetail-title {
         font-size: 21px;
         padding-top: 10px;
         color: #9e1ed4;
      }
      .songdetail-img {
         margin-top: 18px;
         width: 230px;
      }
      .songdetail-author {
         margin-top: 19px;
         font-size: 20px;
      }
      .songdetail-desc {
         margin-top: 20px;
         font-size: 16px;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>


<section class="view-book">
   <?php if ($songItem) : ?>
         <!-- Modal View Detail Book -->
      <div class="modal">
         <div class="modal-container">
            <h3 class="songdetail-title">Xem thông tin bài hát <?php echo($songItem['name']) ?></h3>
            <div>
               <img class="songdetail-img" src="uploaded_img/<?php echo $songItem['image']; ?>" alt="">
            </div>
            <p class="songdetail-author">
               Sáng tác: 
               <?php echo ($songItem['author']) ?>
            </p>
            <p class="songdetail-author">
               Ca sĩ: 
               <?php echo ($songItem['singer']) ?>
            </p>
            <?php
               $cate_id = $songItem['cate_id'];
               $sqlcate = "SELECT * FROM categories WHERE id = $cate_id";
               $result1 = $conn1->query($sqlcate);
               $category = $result1->fetch_assoc();
            ?>
            <p class="songdetail-author">
               Thể loại: 
               <?php echo ($category['cate_name']) ?>
            </p>
            <audio  style="width: 350px; margin-top: 10px;" controls>
                  <source src="./songs/<?php echo $songItem['link_path']  ?>" type="audio/ogg">
            </audio>
         </div>
      </div>
   <?php else : ?>
      <p style="font-size: 20px; text-align: center;">Không xem được chi tiết bài hát này</p>
   <?php endif; ?>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>