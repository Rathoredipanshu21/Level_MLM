<?php
session_start();
include 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain text check

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Redirect based on role
        if ($user['role'] == 'master') {
            header("Location: admin/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit;
    } else {
        $error = "Invalid Username or Password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLM Software Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-green-50 h-screen flex items-center justify-center font-sans">

    <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-md border-t-8 border-green-600" data-aos="zoom-in" data-aos-duration="1000">
        <div class="text-center mb-8">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600 text-2xl">
                <i class="fas fa-users"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-500 mt-2">Sign in to manage your network</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="w-full pl-10 px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition duration-200" placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="w-full pl-10 px-4 py-3 rounded-lg bg-gray-50 border border-gray-300 focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 transition duration-200" placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-700 text-white font-bold py-3 rounded-lg hover:from-green-600 hover:to-green-800 transition duration-300 transform hover:scale-[1.02] shadow-lg">
                SECURE LOGIN
            </button>
        </form>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>