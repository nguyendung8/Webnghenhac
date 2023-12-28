<?php
   //nhúng vào các trang bán hàng
   if(isset($message)){//hiển thị thông báo sau khi thao tác với biến message được gán giá trị
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>';//đóng thẻ này
      }
   }
?>
<style>
   .changepw-btn {
      border-radius: 4px;
      font-size: 20px;
      background: blue;
      color: #fff;
      padding: 7px 12px;
   }
   .changepw-btn:hover {
      opacity: 0.7;
   }
</style>
<header class="header">
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-instagram"></a>
         </div>
         <p><a href="login.php">Đăng nhập mới</a> | <a href="register.php">Đăng ký</a> </p>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">Zing MP3</a>

         <nav class="navbar">
            <a href="home.php">Trang chủ</a>
            <a href="about.php">Thông tin</a>
            <a href="contact.php">Liên hệ</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
         </div>

         <div style="z-index: 1000;" class="user-box">
            <p>Tên người dùng : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <a href="change_password.php" class="changepw-btn">Đổi mật khẩu</a>
            <a style="margin-top: 13px;" href="logout.php" class="delete-btn">Đăng xuất</a>
         </div>
      </div>
   </div>

</header>