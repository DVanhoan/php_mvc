<?php
require_once "lib/session.php";
require_once "admin/controller/Productcontroller.php";
require_once "helper/format.php";
Session::init();

$format = new Format();
$productController = new ProductController();
$products = $productController->list();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ben Dev</title>
    <link rel="shortcut icon" type="image/x-icon" href="">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"
        type="text/css">
</head>

<body class="bg-white text-gray-800 dark:bg-gray-900 dark:text-gray-200">
    <?php include "inc/header.php"; ?>
    <main class="min-h-screen bg-gray-100 dark:bg-gray-800 py-12">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <h2 class="mb-12 text-3xl font-bold text-center text-gray-900 dark:text-gray-100 sm:text-4xl">Danh sách sản
                phẩm</h2>

            <div class="grid gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4">
                <?php foreach ($products as $product) { ?>
                    <div
                        class="group bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 p-6 rounded-lg shadow-lg transition-all duration-300">
                        <div class="w-full h-72 overflow-hidden rounded-lg">
                            <a href="/product.php?id=<?= $product['id'] ?>" class="group">
                                <?php if (!empty($product['image'])) {
                                    $imageNames = explode(',', $product['image']);
                                    if (!empty($imageNames[0])) { ?>
                                        <img src="admin/uploads/products/<?= $imageNames[0] ?>" alt="<?= $product['name'] ?>"
                                            class="w-full h-full object-cover transition-all duration-300 group-hover:opacity-90">
                                <?php }
                                } ?>
                            </a>
                        </div>
                        <div class="mt-4">
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                <a href="/product.php?id=<?= $product['id'] ?>"><?= $product['name'] ?></a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <?php echo $format->textShorten($product['description']) ?>
                            </p>
                            <p class="text-lg font-semibold text-gray-800 dark:text-gray-300 mt-2">
                                <?= number_format($product['price'], 0) ?>đ</p>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </main>
</body>