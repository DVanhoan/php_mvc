<?php
require_once "lib/session.php";
require_once "admin/controller/CartController.php";


Session::init();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (isset($action) && $action == "logout") {
    Session::destroy();
}

$cartController = new CartController();
$carts = $cartController->listCart();

$countCart = $cartController->countCart();
?>


<header class="bg-white dark:bg-gray-800 shadow">
    <nav class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/">
                <img src="" alt="" class="w-auto h-8">
            </a>

            <div class="flex items-center justify-end gap-4 sm:gap-6 lg:gap-8">

                <!-- themes light and dark -->
                <div>
                    <button id="toggleTheme"
                        class="px-4 py-2 bg-gray-200 rounded text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                        Chuyển sang chế độ tối
                    </button>
                </div>

                <!-- User Icon -->
                <div>
                    <?php if (Session::get('login')): ?>
                    <div class="relative inline-block text-left">
                        <button id="menu-button" class="flex items-center gap-2 text-sm group focus:outline-none">
                            <?php echo Session::get('username'); ?>
                            <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </button>
                        <div id="menu-dropdown"
                            class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                <a href="/profile.php"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trang cá nhân</a>
                                <a href="/edit-profile.php"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Chỉnh sửa tài
                                    khoản</a>
                                <a href="?action=logout"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng Xuất</a>
                            </div>
                        </div>
                    </div>
                    <script>
                    document.getElementById('menu-button').addEventListener('click', function(event) {
                        const dropdown = document.getElementById('menu-dropdown');
                        dropdown.classList.toggle('hidden');
                        event.stopPropagation(); // Ngăn sự kiện lan ra ngoài
                    });


                    document.addEventListener('click', function(event) {
                        const dropdown = document.getElementById('menu-dropdown');
                        const menuButton = document.getElementById('menu-button');

                        if (!menuButton.contains(event.target) && !dropdown.contains(event.target)) {
                            dropdown.classList.add('hidden');
                        }
                    });
                    </script>

                    <?php else: ?>
                    <a href="/login.php" class="flex items-center gap-2 text-sm group">
                        <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>




                <!-- Cart Icon -->
                <div>
                    <button onclick="toggleCart()" class="group flex items-center gap-1.5 text-sm">
                        <svg class="w-6 h-6 text-gray-500 hover:text-gray-700" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-600"><?php echo $countCart ?></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>




<div id="cart-sidebar" class="hidden fixed inset-y-0 right-0 w-1/3 bg-white shadow-lg z-50">
    <div class="flex flex-col h-full">

        <div class="flex justify-between items-center p-4 border-b">
            <h2 class="text-lg font-medium text-gray-900">Giỏ hàng</h2>
            <button onclick="toggleCart()" class="p-2 text-gray-400 hover:text-gray-500">
                <span class="sr-only">Đóng</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>


        <div class="flex-1 p-4 overflow-y-auto">
            <?php if (isset($carts)): ?>
            <ul class="divide-y border-y">
                <?php foreach ($carts as $cart) { ?>
                <li class="flex py-6 sm:py-8">
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
                            <p class="text-sm mt-1 font-medium"><?= number_format($product['price'], 0) ?>đ</p>
                        </div>


                        <div class="flex items-center mt-4 sm:mt-0 space-x-4">
                            <input type="number" name="qty"
                                class="block w-16 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                value="<?= $cart['quantity'] ?>">

                            <p class="text-sm font-medium"><?= number_format($cart['total'], 0) ?>đ</p>

                            <a href="?action=remove" class="text-gray-400 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </li>
                <?php } ?>
            </ul>
            <?php else: ?>
            <p class="text-center text-sm text-gray-600">Giỏ hàng của bạn trống.</p>
            <?php endif; ?>
        </div>


        <?php if (isset($carts)) : ?>

        <div class="p-4 border-t">
            <button onclick="toggleCart()"
                class="w-full py-2 bg-blue-600 text-white font-medium text-center hover:bg-blue-500 rounded-md">
                Tiếp tục mua sắm
            </button>
        </div>
    </div>
</div>

<script>
function toggleCart() {
    var cart = document.getElementById("cart-sidebar");
    cart.classList.toggle("hidden");
}

document.addEventListener("DOMContentLoaded", function() {
    const themeToggle = document.getElementById("toggleTheme");

    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
        themeToggle.innerText = "Chuyển sang chế độ sáng";
    } else {
        document.body.classList.remove("dark");
        themeToggle.innerText = "Chuyển sang chế độ tối";
    }

    themeToggle.addEventListener("click", function() {
        document.body.classList.toggle("dark");

        if (document.body.classList.contains("dark")) {
            themeToggle.innerText = "Chuyển sang chế độ sáng";
            localStorage.setItem("theme", "dark");
        } else {
            themeToggle.innerText = "Chuyển sang chế độ tối";
            localStorage.setItem("theme", "light");
        }
    });
});
</script>