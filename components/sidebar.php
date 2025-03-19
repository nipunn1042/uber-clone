<?php
// Check if a session is already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Admin Panel</title>
</head>
<body class="flex">

    <!-- Sidebar -->
    <div class="fixed top-0 left-0 h-full w-64 bg-gray-800 text-white p-5 shadow-lg">
    <h2 class="text-xl font-bold mb-6">Admin Panel</h2>
    
    <ul class="space-y-4">
        <li><a href="dashboard.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Dashboard</a></li>
        <li><a href="usersList.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Users</a></li>
        <li><a href="driversList.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Drivers</a></li>
        <li><a href="ridesList.php" class="block py-2 px-4 hover:bg-gray-700 rounded">Rides</a></li>
        <li><a href="../logout.php" class="block py-2 px-4 bg-red-600 rounded hover:bg-red-700">Logout</a></li>
    </ul>
</div>

</body>
</html>