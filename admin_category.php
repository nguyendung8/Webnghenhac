<?php

   include 'config.php';

   session_start();

   $admin_id = $_SESSION['admin_id']; //tạo session admin

   if(!isset($admin_id)){// session không tồn tại => quay lại trang đăng nhập
      header('location:login.php');
   }

   if(isset($_POST['add_category'])){//Thêm loại sách vào thể loại từ submit có name='add_category'

      $name = mysqli_real_escape_string($conn, $_POST['cate_name']);

      $select_category_name = mysqli_query($conn, "SELECT cate_name FROM `categories` WHERE cate_name = '$name'") or die('query failed');//truy vấn để kiểm tra loại sách đã tồn tại chưa

      if(mysqli_num_rows($select_category_name) > 0){// tồn tại rồi thì thông báo
         $message[] = 'Thể loại nhạc đã tồn tại.';
      }else{//chưa tồn tại thì thêm mới
         $add_category_query = mysqli_query($conn, "INSERT INTO `categories`(cate_name) VALUES('$name')") or die('query failed');

         if($add_category_query){
         $message[] = 'Thêm thể loại  thành công!';
         }else{
            $message[] = 'Không thể thêm thể loại  này!';
         }
      }
   }

   if(isset($_GET['delete'])){//Xóa loại sách từ onclick <a></a> có href='delete'
      $delete_id = $_GET['delete'];
      $delete_category_query = mysqli_query($conn, "DELETE FROM `categories` WHERE id = '$delete_id'") or die('query failed');

      if($delete_category_query){
         $message[] = 'Xóa thể loại  thành công!';
         }else{
            $message[] = 'Không thể xóa thể loại  này!';
         }
      header('location:admin_category.php');
   }

   if(isset($_POST['update_category'])){//Cập nhật loại sách vào thể loại từ submit có name='update_category'

      $update_p_id = $_POST['update_p_id'];
      $update_name = $_POST['update_name'];

      mysqli_query($conn, "UPDATE `categories` SET cate_name = '$update_name' WHERE id = '$update_p_id'") or die('query failed');

      header('location:admin_category.php');

   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thể loại nhạc</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Thể loại nhạc</h1>
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Thêm thể loại</h3>
      <input type="text" name="cate_name" class="box" placeholder="Nhập tên thể loại" required>
      <input type="submit" value="Thêm" name="add_category" class="btn">
   </form>

</section>

<section class="show-products">

   <div class="box-container">

      <?php
         $select_categorys = mysqli_query($conn, "SELECT * FROM `categories`") or die('query failed');
         if(mysqli_num_rows($select_categorys) > 0){
            while($fetch_categorys = mysqli_fetch_assoc($select_categorys)){
      ?>
      <div class="box">
         <div class="name"><?php echo $fetch_categorys['cate_name']; ?></div>
         <a href="admin_category.php?update=<?php echo $fetch_categorys['id']; ?>" class="option-btn">Cập nhật</a>
         <a href="admin_category.php?delete=<?php echo $fetch_categorys['id']; ?>" class="delete-btn" onclick="return confirm('Xóa thể loại này?');">Xóa</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Không có thể loại bài hát nào được thêm!</p>';  
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){//Hiện form cập nhật thông tin loại sách từ <a></a> có href='update'
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `categories` WHERE id = '$update_id'") or die('query failed');//lấy ra thông tin loại sách cần cập nhật
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
               <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                  <input type="text" name="update_name" value="<?php echo $fetch_update['cate_name']; ?>" class="box" required placeholder="Tên">
                  <input type="submit" value="Cập nhật" name="update_category" class="btn"> <!-- submit form cập nhật -->
                  <input type="reset" value="Hủy"  onclick="window.location.href = 'admin_category.php'" class="option-btn">
               </form>
   <?php
            }
         }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>

<script src="js/admin_script.js"></script>

</body>
</html>