<?php
include '../database/DBController.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

// Thêm thể loại mới
if (isset($_POST['add_category'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $insert_category_query = mysqli_query($conn, "INSERT INTO `category` (name) VALUES ('$category_name')") or die('Query failed');

    if ($insert_category_query) {
        $message[] = 'Thêm thể loại thành công!';
    } else {
        $message[] = 'Thêm thể loại thất bại!';
    }
}

// Xóa thể loại
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];

    $delete_query = mysqli_query($conn, "DELETE FROM `category` WHERE id = '$delete_id'") or die('Query failed');

    if ($delete_query) {
        $message[] = 'Xóa thể loại thành công!';
    } else {
        $message[] = 'Xóa thể loại thất bại!';
    }
}

// Cập nhật thể loại
if (isset($_POST['update_category'])) {
    $update_id = $_POST['update_id'];
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    $update_query = mysqli_query($conn, "UPDATE `category` SET name = '$category_name' WHERE id = '$update_id'") or die('Query failed');

    if ($update_query) {
        $message[] = 'Cập nhật thể loại thành công!';
    } else {
        $message[] = 'Cập nhật thể loại thất bại!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thể loại</title>

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
            //nhúng vào các trang bán hàng
            if (isset($message)) { // hiển thị thông báo sau khi thao tác với biến message được gán giá trị
                foreach ($message as $msg) {
                    echo '
                    <div class=" alert alert-info alert-dismissible fade show" role="alert">
                        <span style="font-size: 16px;">' . $msg . '</span>
                        <i style="font-size: 20px; cursor: pointer" class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>';
                }
            }
            ?>
            <div class="bg-primary text-white text-center py-2 mb-4 shadow">
                <h1 class="mb-0">Quản Lý Thể Loại</h1>
            </div>

            <!-- Thêm thể loại mới -->
            <section class="add-products mb-4">
                <form action="" method="post">
                    <h3>Thêm thể loại mới</h3>
                    <div class="mb-3">
                        <input type="text" name="category_name" class="form-control" placeholder="Tên thể loại" required>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary">Thêm thể loại</button>
                </form>
            </section>

            <!-- Hiển thị danh sách thể loại -->
            <section class="show-categories">
                <div class="container">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên thể loại</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select_categories = mysqli_query($conn, "SELECT * FROM `category`") or die('Query failed');
                            if (mysqli_num_rows($select_categories) > 0) {
                                while ($category = mysqli_fetch_assoc($select_categories)) {
                            ?>
                                    <tr>
                                        <td><?php echo $category['id']; ?></td>
                                        <td><?php echo $category['name']; ?></td>
                                        <td>
                                            <!-- Modal trigger button -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $category['id']; ?>">Sửa</button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="editModal<?php echo $category['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Sửa thể loại</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="update_id" value="<?php echo $category['id']; ?>">
                                                                <input type="text" name="category_name" class="form-control mb-3" value="<?php echo $category['name']; ?>" required>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" name="update_category" class="btn btn-primary">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                            <a href="admin_categories.php?delete=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa thể loại này?');">Xóa</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">Chưa có thể loại nào.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
