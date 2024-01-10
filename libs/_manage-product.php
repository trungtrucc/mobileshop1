<!-- start #manage -->
<section id="manage-product" class="py-3"> 
    <!-- Kiểm tra đăng nhập-->
    <?php if ($_SESSION['logged'] == false) {
        header("Location: login.php");
    } ?>
    <div class="container">
        <!-- Hiển thị Form Quản lý Sản phẩm-->
        <form method="POST" id="manage-product" enctype="multipart/form-data">
            <div class="form-group">
                <!-- Hiển thị Bảng Quản lý Sản phẩm-->
                <table class="table table-bordered table-data">
                    <thead>
                        <tr>
                            <th scope="colgroup rowgroup">ID</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Hãng</th>
                            <th scope="col">Xuất xứ</th>
                            <th scope="col">Gía</th>
                            <th scope="col">Hình ảnh</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                         <!-- Vòng lặp để Hiển thị Dữ liệu-->
                        <?php foreach ($manageData as $item): ?>
                             <!-- Hiển thị Dữ liệu Sản phẩm trong Bảng-->
                            <tr data-id="<?php echo $item['id'] ?>">
                                 <!-- Các Ô Input và Select để Sửa thông tin sản phẩm-->
                                <td>
                                    <input type="number" value="<?php echo $item['id'] ?>" readonly
                                        name="id-<?php echo $item['id'] ?>">
                                </td>
                                <td>
                                    <input type="text" value="<?php echo $item['name'] ?>"
                                        name="name-<?php echo $item['id'] ?>">
                                </td>
                                <td>
                                    <select name="brand-<?php echo $item['id'] ?>">
                                        <option value="<?php echo $item['brand'] ?>" selected>
                                            <?php echo $item['brand'] ?>
                                        </option>
                                        <?php foreach ($brandData as $brand) { ?>
                                            <option value="<?php echo $brand['id'] ?>">
                                                <?php echo $brand['brand'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="<?php echo $item['origin'] ?>" disabled
                                        name="origin-<?php echo $item['id'] ?>">
                                </td>
                                <td>
                                    <input type="number" step="0.01" value="<?php echo $item['price'] ?>"
                                        name="price-<?php echo $item['id'] ?>">
                                </td>
                                <td>
                                    <div class="preview-image">
                                        <img src="<?php echo $item['image'] ?>" alt="preview">
                                        <input type="file" name="image-<?php echo $item['id'] ?>" accept="image/*">
                                    </div>
                                </td>
                                <!-- Nút Update và Delete-->
                                <td>
                                    <button type="submit" name="manage-update"
                                        formaction="manage.php?id=<?php echo $item['id'] ?>"
                                        class="btn btn-warning">Update</button>
                                </td>
                                <td>
                                    <button type="submit" name="manage-delete"
                                        formaction="manage.php?id=<?php echo $item['id'] ?>"
                                        class="btn btn-danger">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                 <!-- Nút Thêm Sản phẩm Mới-->
                <button type="button" class="btn btn-secondary addItem">Thêm</button>
            </div>
        </form>
    </div>
</section>
<!-- !start #manage -->