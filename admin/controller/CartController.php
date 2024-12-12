<?php 
require_once __DIR__ . "/../../lib/database.php";
require_once __DIR__ . "/../../helper/format.php";

    class CartController
    {
        private $db;

        public function __construct()   {
            $this->db = Database::getInstance();
        }

        public function addCart($quantity, $id) {
            $quantity = Format::validation($quantity);
            $id = Format::validation($id);

            $quantity = mysqli_real_escape_string($this->db->con, $quantity);
            $id = mysqli_real_escape_string($this->db->con, $id);

            $user_id = Session::get('id');
            $query = "SELECT * FROM products WHERE id = '$id'";
            $result = $this->db->select($query);
            if ($result != false) {
                $value = $result->fetch_assoc();
                $price = $value['price'];
                $total = $price * $quantity;   
                     
                $checkQuery = "SELECT * FROM carts WHERE product_id = '$id' AND user_id = '$user_id'";
                $checkResult = $this->db->select($checkQuery);
                if ($checkResult != false) {    
                    $row = $checkResult->fetch_assoc();
                    $quantity = $row['quantity'] + $quantity;
                    $total = $row['total'] + $total;
                    $query = "UPDATE carts SET quantity = '$quantity', total = '$total' WHERE product_id = '$id' AND user_id = '$user_id'";
                    $updateResult = $this->db->update($query);
                    if ($updateResult) {
                        header("Location: cart.php");
                        exit();
                    } else {
                        $alert = "Co loi xay ra. Vui long thuc hien lai";
                        return $alert;
                    }
                } else {
                    $query = "insert into carts(user_id, product_id, price, quantity, total) values('$user_id', '$id', '$price', '$quantity', '$total')";
                    $result = $this->db->insert($query);
                    if ($result) {
                        header("Location:cart.php");
                        exit();
                    } else {
                        $alert = "Co loi xay ra. Vui long thuc hien lai";
                        return $alert;
                    }
                }   
            } else {
                $alert = "Co loi xay ra. Vui long thuc hien lai";
                return $alert;
            }
        }

        public function listCart() {
            $user_id = Session::get('id');
            $query = "SELECT * FROM carts WHERE user_id = '$user_id'";
            
            $result = $this->db->select($query);
            
            if ($result === false) {
                return [];
            }
            
            return $result;
        }
        

        public function getProduct($id) {
            $query = "SELECT * FROM products WHERE id = '$id'";
            $result = $this->db->select($query);
            return $result->fetch_assoc();
        }


        public function deleteCart($product_id) {
            $user_id = Session::get('id');
            $product_id = mysqli_real_escape_string($this->db->con, $product_id);
            $query = "DELETE FROM carts WHERE user_id = '$user_id' AND product_id = '$product_id'";
            $result = $this->db->delete($query);
            if($result) {
                return "Product deleted successfully.";
            } else {
                return "Error deleting product.";
            }
        }


        public function countCart() {
            $user_id = Session::get('id');
            $query = "SELECT * FROM carts WHERE user_id = '$user_id'";
            $result = $this->db->select($query);
            
            if ($result === false) {
                return 0; 
            }
        
            return mysqli_num_rows($result);
        }
        


        public function updateCart($product_id, $quantity) {
            $user_id = Session::get('id');
            $product_id = mysqli_real_escape_string($this->db->con, $product_id);
            $quantity = mysqli_real_escape_string($this->db->con, $quantity);
        
            $query = "SELECT price FROM products WHERE id = '$product_id'";
            $result = $this->db->select($query);
            if ($result) {
                $product = $result->fetch_assoc();
                $total = $product['price'] * $quantity;
        
                $query = "UPDATE carts SET quantity = '$quantity', total = '$total' WHERE product_id = '$product_id' AND user_id = '$user_id'";
                $this->db->update($query);
            } else {
                return "Xảy ra lỗi.";
            }
        }
        
    }
            