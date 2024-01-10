<?php

// php cart class
class Cart
{
    public $db = null;

    public function __construct(Connect $db)
    {
        if (!isset($db->con))
            exit;
        $this->db = $db;
    }

    // get cart using user_id
    public function getCart($userid = null, $table = 'cart')
    {         
    // Tạo câu truy vấn SQL SELECT để lấy thông tin giỏ hàng dựa trên user_id
    $sql = "SELECT * FROM {$table} WHERE user_id={$userid}";

    // Thực hiện câu truy vấn SQL
    $result = $this->db->con->query($sql);

    // Khởi tạo mảng để lưu trữ kết quả
    $resultArray = array();

    // Lấy dữ liệu từ kết quả truy vấn một cách tuần tự
    if ($result->num_rows > 0) {
        while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            // Thêm từng mục dữ liệu vào mảng kết quả
            $resultArray[] = $item;
        }
    }

    // Trả về mảng chứa thông tin giỏ hàng
    return $resultArray;
    }

    // insert into cart table
    public function insertIntoCart($params = null, $table = "cart")
    {   
    if ($this->db->con != null) {
        if ($params != null) {
            // Xác định cột trong bảng giỏ hàng
            $columns = implode(',', array_keys($params));
            
            // Lấy giá trị của các cột tương ứng
            $values = implode(',', array_values($params));

            // Tạo câu truy vấn SQL INSERT
            $sql = sprintf("INSERT INTO %s(%s) VALUES(%s)", $table, $columns, $values);

            // Thực hiện câu truy vấn
            $result = $this->db->con->query($sql);
            return $result;
        }
    }
    }


    // get user_id and item_id and insert into cart table
    public function addToCart($userid, $itemid)
    {
    // Kiểm tra xem userid và itemid có tồn tại không
    if (isset($userid) && isset($itemid)) {
        // Tạo một mảng chứa thông tin user_id và item_id
        $params = array(
            "user_id" => $userid,
            "item_id" => $itemid,
        );

        // Gọi phương thức insertIntoCart để chèn dữ liệu vào bảng giỏ hàng trong CSDL
        $result = $this->insertIntoCart($params);

        // Kiểm tra kết quả chèn dữ liệu
        if ($result) {
            // Nếu thành công, chuyển hướng người dùng đến trang hiện tại (Reload Page)
            header('Location: ' . $_SERVER['REQUEST_URI']);
        } else {
            // Nếu có lỗi, hiển thị thông báo lỗi
            echo '<script>alert("Error")</script>';
        }
    }
    }


    // Xóa mục trong giỏ hàng dựa trên ID của mục
    public function deleteCart($item_id = null, $table = 'cart')
    {
    if ($item_id != null) {
        // Tạo câu truy vấn SQL để xóa mục từ bảng được chỉ định
        $sql = "DELETE FROM {$table} WHERE item_id={$item_id}";
        
        // Thực hiện truy vấn SQL
        $result = $this->db->con->query($sql);
        
        // Nếu truy vấn thành công, reload trang
        if ($result) {
            header('Location: ' . $_SERVER['REQUEST_URI']);
        } else {
            // Nếu có lỗi, hiển thị thông báo lỗi bằng JavaScript
            echo '<script>alert("Error")</script>';
        }
        return $result;
    }
    }

    // Tính tổng cộng của một mảng số
    public function getSum($arr)
    {
    $sum = 0;
    // Duyệt qua mảng và cộng tổng các phần tử
    foreach ($arr as $item) {
        $sum += floatval($item);
    }
    // Định dạng tổng cộng với 2 chữ số thập phân
    return sprintf('%.2f', $sum);
    }

    // Lấy danh sách các item_id từ mảng giỏ hàng
    public function getCartId($cartArray = null)
    {
    $cart_id = array();
    if ($cartArray != null) {
        // Duyệt qua mảng giỏ hàng và lấy item_id của mỗi mục
        foreach ($cartArray as $item) {
            array_push($cart_id, $item['item_id']);
        }
        return $cart_id;
    }
    }
}
