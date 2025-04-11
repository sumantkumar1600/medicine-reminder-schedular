<?php
session_start();
require_once __DIR__ . '/../includes/db_connect.php';
require_once __DIR__ . '/../includes/auth_functions.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    
    if (isset($_POST['register'])) {
        if (registerUser($email, $password)) {
            loginUser($email, $password);
            header("Location: dashboard.php");
        }
    } elseif (isset($_POST['login'])) {
        if (loginUser($email, $password)) {
            header("Location: dashboard.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Reminder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto mt-20 bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Medicine Reminder</h2>
        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <div class="flex gap-4">
                <button type="submit" name="register" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Register
                </button>
                <button type="submit" name="login" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Login
                </button>
            </div>
        </form>
    </div>
</body>
</html>