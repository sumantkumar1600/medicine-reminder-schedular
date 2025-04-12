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
<body class="bg-gradient-to-br from-slate-900 to-blue-900 min-h-screen">
    <div class="max-w-md mx-auto min-h-screen flex items-center justify-center p-4">
        <div class="w-full bg-white/5 backdrop-blur-sm rounded-xl p-8 shadow-2xl border border-white/10">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-2">Medicine Reminder</h2>
                <p class="text-slate-300 text-sm">Your Health Companion</p>
            </div>
            
            <form method="post" class="space-y-6">
                <div>
                    <label class="block text-slate-200 text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" 
                           class="w-full px-4 py-3 bg-white/10 text-slate-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all"
                           required>
                </div>
                
                <div>
                    <label class="block text-slate-200 text-sm font-medium mb-2">Password</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-3 bg-white/10 text-slate-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition-all"
                           required>
                </div>

                <div class="flex gap-4">
                    <button type="submit" name="register" 
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 rounded-lg transition-colors">
                        Create Account
                    </button>
                    
                    <button type="submit" name="login" 
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 rounded-lg transition-colors">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>