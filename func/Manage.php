<?php

// PHP manage class (Lớp quản lý sản phẩm)
class Manage
{
    public $db = null;

    // Hàm khởi tạo để khởi tạo kết nối cơ sở dữ liệu
    public function __construct(Connect $db)
    {
        // Kiểm tra nếu kết nối không tồn tại, thoát
        if (!isset($db->con))
            exit;
        $this->db = $db;
    }
    
    // Phương thức lấy dữ liệu sản phẩm sử dụng phương thức getData
    public function getData()
    {
        // Truy vấn SQL để lấy dữ liệu sản phẩm và thương hiệu
        $sql = "SELECT product.*, manufacturer.brand, manufacturer.headquarter AS origin FROM product INNER JOIN manufacturer ON product.brand = manufacturer.id";
        $result = $this->db->con->query($sql);

        $resultArray = array();

        // Lặp qua từng dòng dữ liệu của sản phẩm và thêm vào mảng kết quả
        if ($result->num_rows > 0) {
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $resultArray[] = $item;
            }
        }

        return $resultArray;
    }

    // Phương thức lấy dữ liệu thương hiệu sử dụng phương thức getBrands
    public function getBrands()
    {
        // Truy vấn SQL để lấy dữ liệu thương hiệu
        $sql = "SELECT * FROM manufacturer";
        $result = $this->db->con->query($sql);

        $resultArray = array();

        // Lặp qua từng dòng dữ liệu của thương hiệu và thêm vào mảng kết quả
        if ($result->num_rows > 0) {
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $resultArray[] = $item;
            }
        }

        return $resultArray;
    }

    // Lấy thông tin thương hiệu dựa trên ID
    public function getBrand($id = null, $table = 'manufacturer')
    {
        // Kiểm tra ID có giá trị không
        if ($id != null) {
            // Truy vấn SQL để lấy dữ liệu thương hiệu có ID tương ứng
            $sql = "SELECT * FROM {$table} WHERE id={$id}";
            $result = $this->db->con->query($sql);

            $resultArray = array();

            // Lấy dữ liệu thương hiệu nếu có một dòng dữ liệu
            if ($result->num_rows == 1) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }

            return $resultArray;
        }
    }

    // Xử lý tải lên hình ảnh
    public function handleImage($image)
    {
        // Đường dẫn lưu trữ hình ảnh
        $target_dir = "./assets/products/";
        $target_file = $target_dir . basename($image['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra xem tệp hình ảnh có phải là hình ảnh thực sự hay không
        $check = getimagesize($image['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo '<script>alert("File is not an image.")</script>';
            $uploadOk = 0;
        }

        // Kiểm tra xem tệp đã tồn tại chưa
        if (file_exists($target_file)) {
            echo '<script>alert("Sorry, file already exists.")</script>';
            $uploadOk = 0;
        }

        // Kiểm tra kích thước tệp > 1MB
        if ($image['size'] > 1000000) {
            echo '<script>alert("Sorry, your file is too large.")</script>';
            $uploadOk = 0;
        }

        // Cho phép các định dạng tệp nhất định
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo '<script>alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed.")</script>';
            $uploadOk = 0;
        }

        // Kiểm tra xem $uploadOk có được thiết lập thành 0 do lỗi không
        if ($uploadOk == 0) {
            echo '<script>alert("Sorry, your file was not uploaded.")</script>';
            return '';
        } else {
            // Di chuyển tệp tải lên đến đúng đường dẫn
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                return $target_file;
            } else {
                echo '<script>alert("Sorry, there was an error uploading your file.")</script>';
                return '';
            }
        }
    }

    // Xóa sản phẩm dựa trên ID
    public function deleteProduct($id = null, $table = 'product')
    {
        
        if ($id != null) {
            
            $sql = "DELETE FROM {$table} WHERE id={$id}";
            $result = $this->db->con->query($sql);
            if ($result) {
               
                header('Location: ' . $_SERVER['REQUEST_URI']);
            } else {
                echo '<script>alert("Error")</script>';
            }
            return $result;
        }
    }

    // Cập nhật thông tin sản phẩm dựa trên ID
    public function updateProduct($id = null, $name = null, $brand = null, $price = null, $image = null)
    {
        
        if ($id != null) {
           
            if (intval($brand) != 0) {
                $sql = "UPDATE product SET brand='{$brand}' WHERE id={$id}";
                $result = $this->db->con->query($sql);
                if (!$result) {
                    echo '<script>alert("Update brand error!")</script>';
                    return $result;
                }
            }

            // Nếu có hình ảnh mới, xử lý tải lên hình ảnh và cập nhật đường dẫn hình ảnh trong cơ sở dữ liệu
            if ($image['name'] != null) {
                $imgName = $this->handleImage($image);
                if ($imgName != '') {
                    $sql = "UPDATE product SET image='{$imgName}' WHERE id={$id}";
                    $result = $this->db->con->query($sql);
                    if (!$result) {
                        echo '<script>alert("Update image error!")</script>';
                        return $result;
                    }
                }
            }
            
            // Cập nhật tên và giá của sản phẩm
            $sql = "UPDATE product SET name='{$name}', price={$price} WHERE id={$id}";
            $result = $this->db->con->query($sql);
            if ($result) {
                // Tải lại trang
                header('Location: ' . $_SERVER['REQUEST_URI']);
            } else {
                echo '<script>alert("Update error!")</script>';
            }
            return $result;
        }
    }

    // Thêm sản phẩm mới
    public function insertProduct($name = null, $brand = null, $price = null, $image = null)
    {
        
        if ($name != null && $brand != null && $price != null && $image != null) {
            
            $imgName = $this->handleImage($image);
            if ($imgName != '') {
               
                $sql = "INSERT INTO product (name, brand, price, image) VALUES ('{$name}', {$brand}, {$price}, '{$imgName}')";
                $result = $this->db->con->query($sql);
                if ($result) {
                    
                    header('Location: ' . $_SERVER['REQUEST_URI']);
                } else {
                    echo '<script>alert("Insert error!")</script>';
                }
                return $result;
            }
        } else {
            echo '<script>alert("Please fill all fields!")</script>';
        }
    }

}

?>
