<?php 
require_once __DIR__ . "/../../lib/database.php";
require_once __DIR__ . "/../../lib/session.php";
require_once __DIR__ . "/../../helper/format.php";

class Productcontroller
{
    public $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }

    public function create($name, $slug, $description, $imageNames, $price, $category, $quantity, $discount, $stock, $featured) {

        $name = Format::validation($name);
        $slug = Format::validation($slug);
        $description = Format::validation($description);
        $imageNames = Format::validation($imageNames);
        $price = Format::validation($price);
        $category = Format::validation($category);
        $quantity = Format::validation($quantity);
        $discount = Format::validation($discount);
        $stock = Format::validation($stock);
        $featured = Format::validation($featured);


        $name = mysqli_real_escape_string($this->db->con, $name);
        $slug = mysqli_real_escape_string($this->db->con, $slug);
        $description = mysqli_real_escape_string($this->db->con, $description);
        $imageNames = mysqli_real_escape_string($this->db->con, $imageNames);
        $price = mysqli_real_escape_string($this->db->con, $price);
        $category = mysqli_real_escape_string($this->db->con, $category);
        $quantity = mysqli_real_escape_string($this->db->con, $quantity);
        $discount = mysqli_real_escape_string($this->db->con, $discount);
        $stock = mysqli_real_escape_string($this->db->con, $stock);
        $featured = mysqli_real_escape_string($this->db->con, $featured);


        $checkQuery = "SELECT * FROM products WHERE name = '$name'";
        $result1 = $this->db->select($checkQuery);
    
        if($result1 != false) {
            $alert = "Sản phẩm đã tồn tại. Vui lòng thử lại.";
            return $alert;
        } else {

            $insertQuery = "INSERT INTO products(name, slug, description, image, price, category_id, quantity, discount, stock, featured) 
                            VALUES('$name', '$slug', '$description', '$imageNames', '$price', '$category', '$quantity', '$discount', '$stock', '$featured')";
    
            $result2 = $this->db->insert($insertQuery);
    
            if($result2) {
                header("Location: products.php");
            } else {
                $alert = "Có lỗi xảy ra. Vui lòng thử lại sau.";
                return $alert;
            }
        }
    }


    public function list(){

        $query = "SELECT * FROM products ORDER BY id ASC";

        $result = $this->db->select($query);

        return $result;
    }

    public function get($id){

        $query = "SELECT * FROM products WHERE id = '$id' LIMIT 1";

        $result = $this->db->select($query);

        if ($result === false) {
            die("Lỗi truy vấn SQL: " . $this->db->error);
        } else {
            return $result->fetch_assoc();
        }
    }

    public function searchByUserInput($search){ 
        $query = "SELECT * FROM products WHERE name LIKE '%".$search."%' OR description LIKE '%".$search."%' ORDER BY id ASC";
        $result = $this->db->select($query);
        return $result;
    }
    

}