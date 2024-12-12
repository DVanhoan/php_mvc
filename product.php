<?php
require_once "admin/controller/Productcontroller.php";
require_once "admin/controller/CartController.php";
require_once "lib/session.php";

Session::init();

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
if (isset($action) && $action == "buy") {
    if ($_SESSION['login'] == false) {
        header("Location:login.php");
        exit();
    }
    $cartController = new CartController();
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING);
    $cartController->addCart($quantity, $id);
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Page</title>
    <link rel="shortcut icon" type="image/x-icon" href="">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Custom styling for the image gallery */
        .thumbnail {
            cursor: pointer;
        }

        .active-thumbnail {
            border: 2px solid #3b82f6;
            /* Tailwind's blue-500 */
        }

        .products-carousel__prev,
        .products-carousel__next {
            background-color: rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <?php include "inc/header.php";

    $productController = new Productcontroller();
    $product = $productController->get($id);
    ?>

    <main class="min-h-screen py-12">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Main Image Section -->
                <div class="col-span-1">
                    <div class="sticky top-12">
                        <div class="overflow-hidden bg-gray-100 dark:bg-gray-800 rounded-lg">
                            <div class="f-carousel__slide">
                                <?php if (!empty($product['image'])) {
                                    $imageNames = explode(',', $product['image']);
                                    if (!empty($imageNames[0])) { ?>
                                        <img id="main-image" src="admin/uploads/products/<?= $imageNames[0] ?>"
                                            alt="<?= $product['name'] ?>" class="w-full h-full object-cover rounded-lg">
                                <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-span-1">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100"><?= $product['name'] ?></h1>

                    <div class="mt-4">
                        <div class="mb-4">
                            <span class="text-3xl font-semibold text-gray-900 dark:text-gray-200">
                                <?= number_format($product['price'], 0, ',', '.') ?> đ
                            </span>
                        </div>

                        <!-- Quantity and Buy Form -->
                        <form action="" method="post" class="mt-6" x-data="{
                                price: <?= $product['price'] ?>,
                                max: 1000,
                                quantity: 1,
                                formatter: new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND',
                                }),
                                decrement() {
                                    if (this.quantity > 1) {
                                        this.quantity--;
                                    }
                                },
                                increment() {
                                    if (this.quantity < this.max) {
                                        this.quantity++
                                    }
                                }
                            }">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>" />
                            <input type="hidden" name="checkout" value="1">

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <!-- Quantity Input -->
                                <div
                                    class="flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                    <div class="grow">
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Nhập số
                                            lượng</span>
                                        <input type="number" name="quantity" x-model="quantity" min="1" :max="max"
                                            value="1"
                                            class="w-full p-0 text-gray-800 dark:text-gray-200 bg-transparent border-0 appearance-none focus:ring-0">
                                    </div>
                                    <div class="flex gap-x-2">
                                        <!-- Decrease Button -->
                                        <button type="button"
                                            class="inline-flex items-center justify-center text-sm text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-full p-2"
                                            :disabled="quantity === 1" @click="decrement">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Increase Button -->
                                        <button type="button"
                                            class="inline-flex items-center justify-center text-sm text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-full p-2"
                                            :disabled="quantity === max" @click="increment">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 5v14M5 12h14"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Buy Button -->
                                <input type="hidden" name="action" value="buy">
                                <button type="submit"
                                    class="flex items-center justify-center w-full py-3 text-lg font-bold text-white bg-blue-500 dark:bg-blue-600 rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700 transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0M17 17H6V3H4M6 5l14 1-1 7H6">
                                        </path>
                                    </svg>
                                    Mua ngay với <span x-text="formatter.format(quantity * price)"></span>
                                </button>
                            </div>

                            <!-- Thumbnails -->
                            <div class="mt-4 flex space-x-4">
                                <?php if (!empty($product['image'])) {
                                    $imageNames = explode(',', $product['image']);
                                    foreach ($imageNames as $imageName) { ?>
                                        <img src="admin/uploads/products/<?= $imageName ?>" alt="<?= $product['name'] ?>"
                                            class="w-20 h-20 object-cover rounded-lg thumbnail active-thumbnail"
                                            onclick="changeImage(this)">
                                <?php }
                                } ?>
                            </div>
                        </form>

                        <!-- Product Description -->
                        <div class="mt-8 prose text-gray-700 dark:text-gray-300">
                            <?= $product['description']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        function changeImage(element) {
            const mainImage = document.getElementById('main-image');
            mainImage.src = element.src;

            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(thumbnail => thumbnail.classList.remove('active-thumbnail'));

            element.classList.add('active-thumbnail');
        }

        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 4,
            spaceBetween: 30,
            navigation: {
                nextEl: '.products-carousel__next',
                prevEl: '.products-carousel__prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 2,
                    spaceBetween: 10
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 4,
                    spaceBetween: 30
                },
            }
        });
    </script>
</body>

</html>