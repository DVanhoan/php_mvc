<?php
require_once "lib/session.php";
require_once "admin/controller/CartController.php";

Session::init();

$cartController = new CartController();
$carts = $cartController->listCart();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);


    if (isset($_POST['action']) && $_POST['action'] === 'remove') {
        $cartController->deleteCart($product_id);
        header("Location: cart.php");
        exit();
    }

    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    $cartController->updateCart($product_id, $quantity);
    header("Location: cart.php");
    exit();
}


?>



<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Ben Dev </title>
    <link rel="shortcut icon" type="image/x-icon" href="">

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"
        type="text/css">
</head>

<body class="text-gray-900 bg-white">
    <?php include "inc/header.php"; ?>
    <div class="px-4 py-8 mx-auto mb-24 max-w-7xl sm:px-6 lg:px-8">
        <h1 class="font-bold text-3xl sm:text-4xl tracking-tight">Giỏ hàng</h1>

        <div class="mt-12 lg:grid lg:items-start lg:grid-cols-12 lg:gap-12 xl:gap-16">
            <!-- Cart Items -->
            <section class="lg:col-span-7">

                <ul class="divide-y border-y">
                    <?php foreach ($carts as $cart) { ?>
                        <li class="flex py-6 sm:py-8">
                            <!-- Checkbox chọn sản phẩm -->
                            <input type="checkbox" class="checkbox mx-4" onchange="updateTotal()">

                            <div class="flex-shrink-0">
                                <?php
                                $product = $cartController->getProduct($cart['product_id']);
                                $imageNames = explode(',', $product['image']);
                                if (!empty($imageNames[0])) { ?>
                                    <img src="admin/uploads/products/<?= $imageNames[0] ?>" alt="<?= $product['name'] ?>"
                                        class="h-20 w-20 sm:w-28 sm:h-28 rounded-lg object-center object-cover">
                                <?php }
                                ?>
                            </div>

                            <div class="ms-4 sm:ms-6 w-full flex flex-col sm:flex-row justify-between items-start">

                                <div>
                                    <h3 class="text-sm text-gray-700 hover:text-gray-900">
                                        <a href="" class="font-medium"><?= $product['name'] ?></a>
                                    </h3>
                                    <p id="price" class="text-sm mt-1 font-medium">
                                        <?= number_format($product['price'], 0) ?>đ</p>
                                </div>

                                <!-- Form cập nhật số lượng sản phẩm -->
                                <form action="" method="post" class="flex items-center mt-4 sm:mt-0 space-x-4">
                                    <input type="hidden" name="product_id" value="<?= $cart['product_id'] ?>">
                                    <div class="flex gap-x-2">
                                        <!-- Decrease Button -->
                                        <button type="button"
                                            class="inline-flex items-center justify-center text-sm text-gray-800 border border-gray-200 rounded-full p-2"
                                            onclick="updateQuantity(this, -1)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"></path>
                                            </svg>
                                        </button>

                                        <!-- Quantity Input -->
                                        <input type="number" name="quantity" min="1" value="<?= $cart['quantity'] ?>"
                                            class="w-12 p-0 text-gray-800 bg-transparent border-0 appearance-none focus:ring-0"
                                            onchange="this.form.submit()">

                                        <!-- Increase Button -->
                                        <button type="button"
                                            class="inline-flex items-center justify-center text-sm text-gray-800 border border-gray-200 rounded-full p-2"
                                            onclick="updateQuantity(this, 1)">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Tổng giá tiền -->
                                    <p id="total" class="text-sm font-medium"><?= number_format($cart['total'], 0) ?>đ</p>
                                </form>

                                <!-- Form xóa sản phẩm -->
                                <form action="" method="post">
                                    <input type="hidden" name="product_id" value="<?= $cart['product_id'] ?>">
                                    <input type="hidden" name="action" value="remove">
                                    <button type="submit" class="text-gray-400 hover:text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
            </section>



            <!-- Summary Section -->
            <form action="checkout.php" method="post" id="cart-form"
                class="lg:col-span-5 px-4 py-6 sm:p-6 lg:p-8 bg-gray-50 rounded-lg mt-16 lg:mt-0">
                <h2 class="font-medium text-gray-900 text-lg">Tóm tắt theo thứ tự</h2>
                <dl class="mt-6 space-y-4 divide-y">
                    <div class="text-sm flex justify-between items-center pt-4">
                        <dt class="text-gray-500">Tạm tính</dt>
                        <dl id="total-amount" class="font-medium">0đ</dl>
                    </div>
                    <div class="text-sm flex justify-between items-center pt-4">
                        <dt class="text-gray-500">Phí vận chuyển</dt>
                        <dl id="shipping" class="font-medium">25.000đ</dl>
                    </div>
                    <div class="flex justify-between items-center pt-4">
                        <dt class="font-semibold text-base">Tổng cộng</dt>
                        <dl id="grand-total" class="font-semibold">0đ</dl>
                    </div>
                </dl>

                <input type="hidden" name="action" value="checkout">
                <input type="hidden" name="selected_products[]" value="">


                <div class="mt-6">
                    <button type="submit"
                        class="w-full flex items-center justify-center rounded-md bg-blue-600 px-6 py-3 text-base font-medium text-white hover:bg-blue-700">
                        Tiến hành thanh toán
                    </button>
                </div>

                <div class="mt-6 flex justify-center text-sm text-center text-gray-500">
                    <p>
                        hoặc
                        <a href="/" type="button" class="font-medium text-blue-600 hover:text-blue-500">
                            Tiếp tục mua sắm &rarr;
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <?php include "inc/footer.php"; ?>


    <script>
        function updateQuantity(button, increment) {
            const form = button.closest('form');
            const quantityInput = form.querySelector('input[name="quantity"]');
            const newQuantity = parseInt(quantityInput.value) + increment;
            if (newQuantity > 0) {
                quantityInput.value = newQuantity;
                form.submit();
            }
        }

        function getData() {
            const checkboxes = document.querySelectorAll('.checkbox');
            const data = [];
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const productRow = checkbox.closest('li');
                    const productId = productRow.querySelector('input[name="product_id"]').value;
                    const quantity = parseInt(productRow.querySelector('input[name="quantity"]').value);
                    data.push({
                        id: productId,
                        quantity: quantity
                    });
                }
            });
            return data;
        }

        function updateData() {
            const data = getData();
            document.querySelector('input[name="selected_products[]"]').value = JSON.stringify(data);
        }

        function updateTotal() {
            const checkboxes = document.querySelectorAll('.checkbox');
            let total = 0;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    updateData();

                    const productRow = checkbox.closest('li');
                    const priceElement = productRow.querySelector('#price');
                    const quantityElement = productRow.querySelector('input[name="quantity"]');
                    const price = parseFloat(priceElement.innerText.replace(/[^0-9]/g, ''));
                    const quantity = parseInt(quantityElement.value);

                    total += price * quantity;
                }
            });

            document.getElementById('total-amount').innerText = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(total);
            document.getElementById('grand-total').innerText = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(total + 25000);
        }


        document.querySelectorAll('.checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });
    </script>
</body>

</html>