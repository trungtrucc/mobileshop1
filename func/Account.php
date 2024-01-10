<?php

// PHP account class (Lớp quản lý tài khoản)
class Account
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

    // Phương thức lấy dữ liệu tài khoản sử dụng phương thức getData
    public function getData($table = 'account')
    {
        // Truy vấn SQL để lấy tất cả dữ liệu từ bảng
        $sql = "SELECT * FROM {$table}";
        $result = $this->db->con->query($sql);

        $resultArray = array();

        // Lặp qua từng dòng dữ liệu của tài khoản và thêm vào mảng kết quả
        if ($result->num_rows > 0) {
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $resultArray[] = $item;
            }
        }

        return $resultArray;
    }

    // Phương thức lấy thông tin tài khoản dựa trên ID
    public function getAccount($id = null, $table = 'account')
    {
        // Kiểm tra ID có giá trị không
        if ($id != null) {
            // Truy vấn SQL để lấy dữ liệu tài khoản có ID tương ứng
            $sql = "SELECT * FROM {$table} WHERE id={$id}";
            $result = $this->db->con->query($sql);

            $resultArray = array();

            // Lấy dữ liệu tài khoản nếu có một dòng dữ liệu
            if ($result->num_rows == 1) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }

            return $resultArray;
        }
    }

    // Xử lý chức năng đăng nhập
    public function login($username = null, $password = null)
    {
        // Kiểm tra thông tin đăng nhập không rỗng
        if ($username != null && $password != null) {
            // Truy vấn SQL để kiểm tra đăng nhập
            $sql = "SELECT * FROM account WHERE username='{$username}' AND password='{$password}'";
            $result = $this->db->con->query($sql);

            // Nếu có một dòng dữ liệu, đăng nhập thành công
            if ($result->num_rows == 1) {
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $_SESSION['logged'] = true;
                setcookie('user_id', $row['id'], time() + (86400 * 1), "/"); // 86400 = 1 day
                setcookie('user_type', $row['privilege'], time() + (86400 * 1), "/"); // 86400 = 1 day
                header('Location: ' . $_SERVER['REQUEST_URI']);
                return true;
            } else {
                echo "<script>alert('Đăng nhập thất bại');</script>";
                return false;
            }
        }
    }

    // Xử lý đăng xuất người dùng
    public function logout()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if ($_SESSION['logged'] == true) {
            $_SESSION['logged'] = false;
            setcookie('user_id', '0', time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('user_type', '0', time() + (86400 * 30), "/"); // 86400 = 1 day
            header('Location: ' . $_SERVER['REQUEST_URI']);
            return true;
        }
    }

    // Xử lý đăng ký người dùng
    public function register(
        $fullname = null,
        $username = null,
        $password = null,
        $phone = null,
        $avatar = null,
        $email = null,
        $city = null,
        $gender = null,
        $address = null
    )
    {
        // Kiểm tra thông tin đăng ký không rỗng
        if (
            $fullname != null
            && $username != null
            && $password != null
            && $phone != null
            // && $avatar != null
            && $email != null
            && $city != null
            && $gender != null
            // && $address != null
        ) {
            // Kiểm tra nếu avatar hoặc địa chỉ không có, thiết lập giá trị mặc định
            if ($avatar == null) {
                $avatar = null;
            }
            if ($address == null) {
                $address = null;
            }

            // Truy vấn SQL để thêm tài khoản và người dùng mới
            $sqlAccount = "INSERT INTO account(username, password, email) VALUES ('{$username}','{$password}','{$email}')";
            $sqlUser = "INSERT INTO user(fullname, phone, avatar, city, gender, address) VALUES ('{$fullname}','{$phone}','{$avatar}','{$city}','{$gender}','{$address}')";
            $resultAcc = $this->db->con->query($sqlAccount);
            $resultUser = $this->db->con->query($sqlUser);

            // Kiểm tra kết quả thực hiện đăng ký
            if ($resultAcc && $resultUser) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
                return true;
            } else if ($resultUser) {
                echo "<script>alert('Đăng ký tài khoản thất bại');</script>";
                $this->db->con->query("DELETE FROM account WHERE username = '{$username}'");
                return false;
            } else if ($resultAcc) {
                echo "<script>alert('Đăng ký người dùng thất bại');</script>";
                $this->db->con->query("DELETE FROM user WHERE fullname = '{$fullname}'");
                return false;
            } else {
                echo "<script>alert('Đăng ký thất bại');</script>";
                return false;
            }
        }
    }

    // Xóa tài khoản dựa trên ID
    public function deleteAcc($id = null, $table = 'account')
    {
        // Kiểm tra ID có giá trị không
        if ($id != null) {
            // Truy vấn SQL để xóa tài khoản
            $sql = "DELETE FROM {$table} WHERE id={$id}";
            $result = $this->db->con->query($sql);

            // Kiểm tra kết quả và tải lại trang nếu thành công
            if ($result) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
            } else {
                echo '<script>alert("Lỗi")</script>';
            }
            return $result;
        }
    }

    // Cập nhật thông tin tài khoản dựa trên ID
    public function updateAcc($id = null, $username = null, $password = null, $email = null, $privilege = null)
    {
        // Kiểm tra ID có giá trị không
        if ($id != null) {
            // Truy vấn SQL để cập nhật thông tin tài khoản
            $sql = "UPDATE account SET username='{$username}', password='{$password}', email='{$email}', privilege='{$privilege}' WHERE id={$id}";
            $result = $this->db->con->query($sql);

            // Kiểm tra kết quả và tải lại trang nếu thành công
            if ($result) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
            } else {
                echo '<script>alert("Lỗi")</script>';
            }
            return $result;
        }
    }

    // Thêm tài khoản mới
    public function insertAcc($username = null, $password = null, $email = null, $privilege = null)
    {
        // Kiểm tra thông tin tài khoản không rỗng
        if ($username != null && $password != null && $email != null && $privilege != null) {
            // Truy vấn SQL để thêm tài khoản mới
            $sql = "INSERT INTO account(username, password, email, privilege) VALUES ('{$username}','{$password}','{$email}','{$privilege}')";
            $result = $this->db->con->query($sql);

            // Kiểm tra kết quả và tải lại trang nếu thành công
            if ($result) {
                header('Location: ' . $_SERVER['REQUEST_URI']);
            } else {
                echo '<script>alert("Lỗi")</script>';
            }
            return $result;
        } else {
            echo '<script>alert("Vui lòng điền đầy đủ thông tin!")</script>';
        }
    }

}

// Kết thúc lớp PHP
