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
   <title>Thông tin</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="flex">

      <div class="image">
         <img height="385px" style="border-radius: 4px;" src="images/infoimg.jpg" alt="">
      </div>

      <div class="content">
         <h3>Tại sao chúng ta nên nghe nhạc mỗi ngày?</h3>
         <p>Nghe nhạc giúp cuộc sống bạn trở nên tốt đẹp hơn.</p>
         <p> Lợi ích của âm nhạc không chỉ giúp bạn thư giãn mà còn có thể cải thiện chất lượng giấc ngủ, ngăn ngừa chứng trầm cảm, giảm đau, kiểm soát cơn thèm ăn để làm đẹp vóc dáng.</p>
         <a href="contact.php" class="btn">Liên hệ</a>
      </div>

   </div>

</section>

<section class="authors">

   <h1 class="title">Thành viên của Zing MP3</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/conan.jpg" alt="">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-instagram"></a>
         </div>
         <h3>Nguyễn Tới</h3>
      </div>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>