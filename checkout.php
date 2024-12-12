<?php
require_once "lib/session.php";
require_once "admin/controller/CartController.php";
require_once "admin/controller/OrderController.php";
require_once "admin/controller/ProductController.php";
Session::init();

$user_id = Session::get('id');
$orderController = new OrderController();
$productController = new ProductController();
$order_id  = $orderController->createOrder($user_id, '0', 'pending');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (isset($_POST['selected_products']) && is_array($_POST['selected_products'])) {

        $total = 0;

        $selectedProducts = $_POST['selected_products'];
        foreach ($selectedProducts as $jsonString) {
            $productData = json_decode($jsonString, true);

            $productId = $productData[0]['id'];
            $quantity = $productData[0]['quantity'];

            // Lấy thông tin sản phẩm
            $product = $productController->get($productId);

            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;

            // Thêm sản phẩm vào đơn hàng đã tạo
            $orderController->createOrderItem($order_id, $productId, $quantity, $subtotal);
        }

        // Cập nhật tổng số tiền và thêm thông tin thanh toán sau khi đã thêm tất cả các sản phẩm
        $orderController->addPayment($order_id, $total, 'cash_on_delivery', 'pending');
        $orderController->updateTotalAmount($order_id, $total);
    } else {
        echo "Vui lòng chọn sản phẩm";
    }
}






?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ben dev</title>
    <link rel="shortcut icon" type="image/x-icon" href="">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"
        type="text/css">
</head>

<body class="bg-gray-100">

    <?php include "inc/header.php"; ?>

    <div class="container mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-lg space-y-8">
            <h2 class="text-lg font-semibold">Thông tin sản phẩm:</h2>
            <div class="flex items-center space-x-4">
                <img src="https://huynt.dev/storage/dalle-2024-06-25-113744-a-simple-promotional-image-showcasing-a-combo-package-of-three-ebooks-each-e-150x150.webp"
                    alt="Combo Tân thủ" class="w-24 h-24 rounded-lg">
                <div class="flex-1">
                    <p class="text-lg font-medium">Combo "Tân thủ" cho Junior Dev</p>
                    <div class="flex items-center space-x-2">
                        <span class="font-semibold">Số lượng:</span>
                        <div class="flex items-center">
                            <button type="button" class="px-2 py-1 border border-gray-300 rounded-lg">-</button>
                            <input type="number" name="qty" value="1" min="1"
                                class="w-12 text-center mx-2 border border-gray-300 rounded-lg">
                            <button type="button" class="px-2 py-1 border border-gray-300 rounded-lg">+</button>
                        </div>
                    </div>
                </div>
                <p class="text-xl font-semibold text-blue-600">99.000đ</p>
            </div>
            <hr class="my-4 border-gray-300">
            <div class="flex justify-between">
                <p class="text-lg">Tạm tính:</p>
                <p class="text-lg font-semibold">99.000đ</p>
            </div>
            <div class="flex justify-between">
                <p class="text-lg">Phí vận chuyển:</p>
                <p class="text-lg font-semibold">25.000đ</p>
            </div>
            <div class="flex justify-between">
                <p class="text-lg">Tổng cộng:</p>
                <p class="text-lg font-semibold text-blue-600">99.000đ</p>
            </div>
        </div>

        <!-- Customer Information Section -->
        <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-lg space-y-6">
            <h2 class="text-xl font-semibold">Thông tin khách hàng</h2>
            <div class="grid grid-cols-1 gap-4">
                <!-- address -->
                <div class="space-y-1">
                    <label for="address" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                    <input type="text" name="address[address]" id="address" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-blue-500 focus:border-blue-500">
                </div>
                <!-- phone -->
                <div class="space-y-1">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Điện thoại</label>
                    <input type="text" name="address[phone]" id="phone" required
                        class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-blue-500 focus:border-blue-500">
                </div>

            </div>

            <!-- Payment Section -->
            <h2 class="text-xl font-semibold">Phương thức thanh toán</h2>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn phương thức thanh toán:</label>
                <div class="space-y-2">
                    <div class="flex items-center space-x-3">
                        <input type="radio" id="payment_sepay" name="payment_method" value="sepay" checked
                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="payment_sepay" class="block text-sm text-gray-700">Thanh toán trực tuyến với
                            Sepay</label>
                    </div>
                    <div class="flex items-center space-x-3">
                        <input type="radio" id="payment_qrcode" name="payment_method" value="qrcode"
                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="payment_qrcode" class="block text-sm text-gray-700">Thanh toán QR Code</label>
                    </div>
                    <div class="flex items-center space-x-3">
                        <input type="radio" id="payment_qrcode" name="payment_method" value="qrcode"
                            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <label for="payment_qrcode" class="block text-sm text-gray-700">Thanh toán khi nhận hàng</label>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Quét QR Code để thanh toán qua tài khoản ngân hàng</p>
                </div>
            </div>
            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700">Ghi chú đơn hàng:</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full border border-gray-300 px-3 py-2 rounded-lg focus:ring focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ghi chú về đơn hàng của bạn."></textarea>
            </div>
            <div class="flex justify-between items-center mt-6">
                <a href="/cart.php" class="text-blue-600 hover:text-blue-500 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l14 0M5 12l4-4M5 12l4 4"></path>
                    </svg>
                    <span>Quay lại giỏ hàng</span>
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all">Tiến
                    hành thanh toán</button>
            </div>
        </div>
    </div>

</body>

</html>