<?php
include '../database/DBController.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

// Thêm quảng cáo mới
if (isset($_POST['add_ad'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Upload hình ảnh quảng cáo
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../assets/blog/' . $image_name;

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $insert_ad_query = mysqli_query($conn, "INSERT INTO `advertisement` (title, content, image) 
        VALUES ('$title', '$content', '$image_name')") or die('Query failed');

        if ($insert_ad_query) {
            $message[] = 'Thêm quảng cáo thành công!';
        } else {
            $message[] = 'Thêm quảng cáo thất bại!';
        }
    } else {
        $message[] = 'Lỗi khi tải ảnh!';
    }
}

// Xóa quảng cáo
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `advertisement` WHERE id = '$delete_id'") or die('Query failed');
    $fetch_image = mysqli_fetch_assoc($delete_image_query);
    unlink('../assets/blog/' . $fetch_image['image']);

    $delete_query = mysqli_query($conn, "DELETE FROM `advertisement` WHERE id = '$delete_id'") or die('Query failed');

    if ($delete_query) {
        $message[] = 'Xóa quảng cáo thành công!';
    } else {
        $message[] = 'Xóa quảng cáo thất bại!';
    }
}

// Cập nhật quảng cáo
if (isset($_POST['update_ad'])) {
    $update_id = $_POST['update_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $update_query = "UPDATE `advertisement` SET title = '$title', content = '$content'";

    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../assets/blog/' . $image_name;

        move_uploaded_file($image_tmp_name, $image_folder);
        $update_query .= ", image = '$image_name'";
    }
    $update_query .= " WHERE id = '$update_id'";
    $update_result = mysqli_query($conn, $update_query) or die('Query failed');

    if ($update_result) {
        $message[] = 'Cập nhật quảng cáo thành công!';
    } else {
        $message[] = 'Cập nhật quảng cáo thất bại!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Quảng cáo</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>
    <div class="d-flex">
        <?php include 'admin_navbar.php'; ?>
        <div class="manage-container">
        <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <span style="font-size: 16px;">' . $msg . '</span>
                        <i style="font-size: 20px; cursor: pointer" class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>';
                }
            }
            ?>
            <div class="bg-primary text-white text-center py-2 mb-4 shadow">
                <h1 class="mb-0">Quản lý Quảng cáo</h1>
            </div>
            <section class="add-products mb-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Thêm quảng cáo mới</h3>
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Tiêu đề quảng cáo" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="content" class="form-control" placeholder="Nội dung quảng cáo" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" name="add_ad" class="btn btn-primary">Thêm quảng cáo</button>
                </form>
            </section>

            <section class="show-ads">
                <div class="container">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tiêu đề</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select_ads = mysqli_query($conn, "SELECT * FROM `advertisement` ORDER BY id DESC") or die('Query failed');
                            if (mysqli_num_rows($select_ads) > 0) {
                                while ($ad = mysqli_fetch_assoc($select_ads)) {
                            ?>
                                    <tr>
                                        <td><?php echo $ad['id']; ?></td>
                                        <td><img src="../assets/blog/<?php echo $ad['image']; ?>" alt="" width="90"></td>
                                        <td><?php echo $ad['title']; ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $ad['id']; ?>">Sửa</button>
                                            
                                            <div class="modal fade" id="editModal<?php echo $ad['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Sửa quảng cáo</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="update_id" value="<?php echo $ad['id']; ?>">
                                                                <input type="text" name="title" class="form-control mb-3" value="<?php echo $ad['title']; ?>" required>
                                                                <textarea name="content" class="form-control mb-3" rows="5" required><?php echo $ad['content']; ?></textarea>
                                                                <input type="file" name="image" class="form-control mb-3" accept="image/*">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" name="update_ad" class="btn btn-primary">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <a href="admin_blogs.php?delete=<?php echo $ad['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa quảng cáo này?');">Xóa</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">Chưa có quảng cáo nào.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
