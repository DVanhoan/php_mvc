<?php
require_once __DIR__ . "/../../lib/database.php";
require_once __DIR__ . "/../../helper/format.php";

class Categorycontroller
{
    public $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function create($name, $slug, $description)
    {
        $name = Format::validation($name);
        $slug = Format::validation($slug);
        $description = Format::validation($description);



        $name = mysqli_real_escape_string($this->db->con, $name);
        $slug = mysqli_real_escape_string($this->db->con, $slug);
        $description = mysqli_real_escape_string($this->db->con, $description);



        $checkQuery = "SELECT * FROM categories WHERE name = '$name'";
        $result1 = $this->db->select($checkQuery);

        if ($result1 != false) {
            $alert = "Danh mục đã tồn tại. Vui lý thử lại.";
            return $alert;
        } else {

            $insertQuery = "INSERT INTO categories(name, slug, description) VALUES('$name', '$slug', '$description')";
            $result2 = $this->db->insert($insertQuery);

            if ($result2) {
                header("Location:category.php");
            } else {
                $alert = "Có lỗi xảy ra. Vui lòng thử lại sau.";
                return  $alert;
            }
        }
    }


    public function list()
    {
        $query = "SELECT * FROM categories ORDER BY id ASC";
        $result = $this->db->select($query);

        if ($result) {
            return $result;
        } else {
            echo "Query thất bại hoặc không có dữ liệu.";
            return false;
        }
    }
}
