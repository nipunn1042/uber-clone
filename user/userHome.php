<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex flex-col items-center justify-center h-screen bg-gray-100">
    <h1 class="text-3xl font-bold">Welcome, User!</h1>
    <a href="../logout.php" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Logout</a>
</body>
</html>
