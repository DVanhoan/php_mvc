<?php 
require_once __DIR__ . "/../../lib/database.php";
require_once __DIR__ . "/../../helper/format.php";

class OrderController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // 1. Tạo đơn hàng
    public function createOrder($user_id, $total_amount, $status = 'pending') {
        $order_date = date('Y-m-d H:i:s');
        $query = "INSERT INTO orders (user_id, order_date, total_amount, status) 
                  VALUES ('$user_id', '$order_date', '$total_amount', '$status')";
        $result = $this->db->insert($query);
        if ($result) {
            return $this->db->getLastId(); 
        }
        return false;
    }

    // 2. Thêm thanh toán cho đơn hàng
    public function addPayment($order_id, $amount, $payment_method, $status = 'pending') {
        $payment_date = date('Y-m-d H:i:s');
        $query = "INSERT INTO payments (order_id, payment_date, amount, payment_method, status) 
                  VALUES ('$order_id', '$payment_date', '$amount', '$payment_method', '$status')";
        return $this->db->insert($query);
    }

    // 3. Thêm thông tin giao hàng cho đơn hàng
    public function addShipment($order_id, $shipment_date, $delivery_date, $status = 'pending') {
        $query = "INSERT INTO shipment (order_id, shipment_date, delivery_date, status) 
                  VALUES ('$order_id', '$shipment_date', '$delivery_date', '$status')";
        return $this->db->insert($query);
    }

    // 4. Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($order_id, $status) {
        $query = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
        return $this->db->update($query);
    }

    // 5. Lấy thông tin chi tiết của đơn hàng bao gồm thanh toán và giao hàng
    public function getOrderDetails($order_id) {
        // Lấy thông tin đơn hàng
        $queryOrder = "SELECT * FROM orders WHERE id = '$order_id'";
        $order = $this->db->select($queryOrder)->fetch_assoc();

        // Lấy thông tin thanh toán của đơn hàng
        $queryPayment = "SELECT * FROM payments WHERE order_id = '$order_id'";
        $payments = $this->db->select($queryPayment)->fetch_all(MYSQLI_ASSOC);

        // Lấy thông tin giao hàng của đơn hàng
        $queryShipment = "SELECT * FROM shipment WHERE order_id = '$order_id'";
        $shipments = $this->db->select($queryShipment)->fetch_all(MYSQLI_ASSOC);

        return [
            'order' => $order,
            'payments' => $payments,
            'shipments' => $shipments
        ];
    }

   
    public function createOrderItem($order_id, $product_id, $quantity, $price) {
        $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                  VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        return $this->db->insert($query);
    }

    public function updateTotalAmount($order_id, $total_amount) {
        $query = "UPDATE orders SET total_amount = '$total_amount' WHERE id = '$order_id'";
        return $this->db->update($query);
    }

}
