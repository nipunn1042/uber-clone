<?php
session_start();

// If admin is already logged in, redirect to the dashboard
if (isset($_SESSION['admin'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('https://img.freepik.com/free-photo/makeup-brush-eyeglasses-cactus-plant-white-flower-bouquet-with-laptop-blue-background_23-2148178672.jpg?t=st=1742357059~exp=1742360659~hmac=42588f1c3dd70d53245f86db7a172606de213dcc6a46c9e7a1bd4fa1fea7f7f1&w=1380') no-repeat center center/cover;
            background-size: cover;
        }
        .overlay {
            background: rgba(0, 0, 0, 0.7); 
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1; /* Keep background behind content */
        }
    </style>
</head>
<body class="flex items-center justify-center h-screen relative">
    
    <!-- Background Overlay -->
    <div class="overlay"></div>

    <!-- Login Form -->
    <div class="bg-white p-8 rounded-lg shadow-lg z-10 relative w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Admin Login</h2>
        <form action="admin_login_process.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Login</button>
        </form>
    </div>
</body>
</html>
