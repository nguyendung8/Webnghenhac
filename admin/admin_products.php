<?php
include '../database/DBController.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

// Thêm bài hát mới
if (isset($_POST['add_song'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $categoryId = $_POST['categoryId'];
    $isRestricted = isset($_POST['isRestricted']) ? 1 : 0;

    // Upload hình ảnh bài hát
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../assets/products/' . $image_name;

    // Upload file bài hát
    $url_name = $_FILES['url']['name'];
    $url_tmp_name = $_FILES['url']['tmp_name'];
    $url_folder = '../assets/songs/' . $url_name;

    if (move_uploaded_file($image_tmp_name, $image_folder) && move_uploaded_file($url_tmp_name, $url_folder)) {
        $insert_song_query = mysqli_query($conn, "INSERT INTO song (title, artist, categoryId, image, url, isRestricted) 
        VALUES ('$title', '$artist', '$categoryId', '$image_name', '$url_name', '$isRestricted')") or die('Query failed');

        if ($insert_song_query) {
            $message[] = 'Thêm bài hát thành công!';
        } else {
            $message[] = 'Thêm bài hát thất bại!';
        }
    } else {
        $message[] = 'Lỗi khi tải ảnh hoặc file nhạc!';
    }
}

// Xóa bài hát
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image, url FROM song WHERE id = '$delete_id'") or die('Query failed');
    $fetch_files = mysqli_fetch_assoc($delete_image_query);
    unlink('../assets/products/' . $fetch_files['image']);
    unlink('../assets/songs/' . $fetch_files['url']);

    $delete_query = mysqli_query($conn, "DELETE FROM song WHERE id = '$delete_id'") or die('Query failed');

    if ($delete_query) {
        $message[] = 'Xóa bài hát thành công!';
    } else {
        $message[] = 'Xóa bài hát thất bại!';
    }
}

// Cập nhật bài hát
if (isset($_POST['update_song'])) {
    $update_id = $_POST['update_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $artist = mysqli_real_escape_string($conn, $_POST['artist']);
    $categoryId = $_POST['categoryId'];
    $isRestricted = isset($_POST['isRestricted']) ? 1 : 0;

    $update_query = "UPDATE song SET title = '$title', artist = '$artist', categoryId = '$categoryId', isRestricted = '$isRestricted'";

    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../assets/products/' . $image_name;

        move_uploaded_file($image_tmp_name, $image_folder);
        $update_query .= ", image = '$image_name'";
    }

    if (!empty($_FILES['url']['name'])) {
        $url_name = $_FILES['url']['name'];
        $url_tmp_name = $_FILES['url']['tmp_name'];
        $url_folder = '../assets/songs/' . $url_name;

        move_uploaded_file($url_tmp_name, $url_folder);
        $update_query .= ", url = '$url_name'";
    }

    $update_query .= " WHERE id = '$update_id'";
    $update_result = mysqli_query($conn, $update_query) or die('Query failed');

    if ($update_result) {
        $message[] = 'Cập nhật bài hát thành công!';
    } else {
        $message[] = 'Cập nhật bài hát thất bại!';
    }
}

// Phân trang
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 6;
    $offset = ($page - 1) * $limit; 

    $total_songs_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM song") or die('Query failed');
    $total_songs = mysqli_fetch_assoc($total_songs_query)['total'];

    $select_songs = mysqli_query($conn, "SELECT s.*, c.name AS category_name FROM song s 
    LEFT JOIN category c ON s.categoryId = c.id 
    ORDER BY s.id DESC LIMIT $limit OFFSET $offset") or die('Query failed');

    $total_pages = ceil($total_songs / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý bài hát</title>

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
                    <div class=" alert alert-info alert-dismissible fade show" role="alert">
                        <span style="font-size: 16px;">' . $msg . '</span>
                        <i style="font-size: 20px; cursor: pointer" class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>';
                }
            }
            ?>
            <div class="bg-primary text-white text-center py-2 mb-4 shadow">
                <h1 class="mb-0">Quản Lý Bài Hát</h1>
            </div>
            <section class="add-products mb-4">
                <form action="" method="post" enctype="multipart/form-data">
                    <h3>Thêm bài hát mới</h3>
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Tên bài hát" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="artist" class="form-control" placeholder="Nghệ sĩ" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryId">Thể loại</label>
                        <select name="categoryId" class="form-control" required>
                            <?php
                            $categories = mysqli_query($conn, "SELECT * FROM category") or die('Query failed');
                            while ($category = mysqli_fetch_assoc($categories)) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="url">Link nhạc</label>
                        <input type="file" name="url" class="form-control" accept="audio/*" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="isRestricted" class="form-check-input" id="isRestricted">
                        <label class="form-check-label" for="isRestricted">Hạn chế</label>
                    </div>
                    <button type="submit" name="add_song" class="btn btn-primary">Thêm bài hát</button>
                </form>
            </section>

            <section class="show-songs">
                <div class="container">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Tên bài hát</th>
                                <th>Nghệ sĩ</th>
                                <th>Thể loại</th>
                                <th>Hạn chế</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if (mysqli_num_rows($select_songs) > 0) {
                                while ($song = mysqli_fetch_assoc($select_songs)) {
                            ?>
                                    <tr>
                                        <td><?php echo $song['id']; ?></td>
                                        <td><img src="../assets/products/<?php echo $song['image']; ?>" alt="" width="100"></td>
                                        <td><?php echo $song['title']; ?></td>
                                        <td><?php echo $song['artist']; ?></td>
                                        <td><?php echo $song['category_name']; ?></td>
                                        <td><?php echo $song['isRestricted'] ? 'Có' : 'Không'; ?></td>
                                        <td>
                                            <!-- Modal trigger button -->
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $song['id']; ?>">Sửa</button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="editModal<?php echo $song['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Sửa bài hát</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="update_id" value="<?php echo $song['id']; ?>">
                                                                <input type="text" name="title" class="form-control mb-3" value="<?php echo $song['title']; ?>" required>
                                                                <input type="text" name="artist" class="form-control mb-3" value="<?php echo $song['artist']; ?>" required>
                                                                <select name="categoryId" class="form-control mb-3" required>
                                                                    <?php
                                                                    $categories = mysqli_query($conn, "SELECT * FROM category") or die('Query failed');
                                                                    while ($category = mysqli_fetch_assoc($categories)) {
                                                                        echo "<option value='{$category['id']}'" . ($category['id'] == $song['categoryId'] ? ' selected' : '') . ">{$category['name']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <div class="mb-3">
                                                                    <label for="image">Hình ảnh mới (nếu có)</label>
                                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="url">File nhạc mới (nếu có)</label>
                                                                    <input type="file" name="url" class="form-control" accept="audio/*">
                                                                </div>
                                                                <div class="form-check mb-3">
                                                                    <input type="checkbox" name="isRestricted" class="form-check-input" id="isRestricted<?php echo $song['id']; ?>" <?php echo $song['isRestricted'] ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label" for="isRestricted<?php echo $song['id']; ?>">Hạn chế</label>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                                                <button type="submit" name="update_song" class="btn btn-primary">Cập nhật</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                            <a href="admin_products.php?delete=<?php echo $song['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa bài hát này?');">Xóa</a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center">Chưa có bài hát nào.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="admin_products.php?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="admin_products.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="admin_products.php?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>