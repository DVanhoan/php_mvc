<?php 
    include "admin/controller/UserController.php";

    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if(isset($action) && $action == "register") {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
        
        $userController = new UserController();
        $userController->register($email, $password, $username, "user");
    }
?>

<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng ký</title>
    <link rel="shortcut icon" type="image/x-icon" href="">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"
        type="text/css">
</head>

<body>
<?php include "inc/header.php" ?>

    <div class="max-w-md mx-auto my-16 p-8 bg-white shadow-lg rounded-lg">
        <div class="text-center">
            <img src="" alt="Blog của Ben dev" class="mx-auto w-auto h-12">
        </div>

        <h2 class="text-2xl font-bold text-center mt-6">Tạo tài khoản mới</h2>

        <form method="POST" action="" class="mt-8 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên</label>
                <input type="text" id="name" name="username" placeholder="Nhập họ và tên" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" id="email" name="email" placeholder="Nhập email" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật
                    khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    placeholder="Nhập lại mật khẩu" required
                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>

            <div class="flex items-start">
                <label class="flex items-center">
                    <input type="checkbox" name="terms" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-900">Tôi đồng ý với Điều khoản và Chính sách quyền riêng
                        tư</span>
                </label>
            </div>
            <input type="hidden" name="action" id="action" value="register">
            <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Đăng ký
            </button>

            <div class="text-center mt-4 text-sm text-gray-600">
                Đã có tài khoản? <a href="/login.php" class="text-blue-600 hover:text-blue-500">Đăng nhập</a>
            </div>
        </form>

        <p class="text-center mt-6">
            <a href="/" class="text-blue-600 hover:text-blue-500">Quay lại trang chủ</a>
        </p>
    </div>
    <?php include "inc/footer.php" ?>
</body>