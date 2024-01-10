<?php

// Khởi tạo và kết nối với cơ sở dữ liệu
class Product
{
    public $db = null;

    public function __construct(Connect $db)
    {
        if (!isset($db->con))
            exit;
        $this->db = $db;
    }
    
    // Phương thức getData được sử dụng để truy vấn toàn bộ dữ liệu từ bảng sản phẩm, 
    // Dữ liệu được truy vấn từ cơ sở dữ liệu và trả về dưới dạng một mảng liên kết, trong đó mỗi phần tử là một dòng dữ liệu của sản phẩm.
    public function getData($table = 'product')
    {
        $sql = "SELECT * FROM {$table}";
        $result = $this->db->con->query($sql);

        $resultArray = array();

        
        if ($result->num_rows > 0) {
            while ($item = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $resultArray[] = $item;
            }
        }

        return $resultArray;
    }

    // Phương thức getProduct được sử dụng để lấy dữ liệu của một sản phẩm dựa trên ID từ bảng sản phẩm
    // Nếu ID được chỉ định và tồn tại trong cơ sở dữ liệu, một mảng liên kết chứa thông tin của sản phẩm được trả về
    public function getProduct($id = null, $table = 'product')
    {
        if ($id != null) {
            $sql = "SELECT * FROM {$table} WHERE id={$id}";
            $result = $this->db->con->query($sql);

            $resultArray = array();

           
            if ($result->num_rows == 1) {
                $resultArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
            }

            return $resultArray;
        }
    }

}