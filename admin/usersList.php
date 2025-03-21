<?php
session_start();
require '../config/config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT id, full_name, email, phone, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Users List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">

    <?php include '../components/sidebar.php'; ?>

    <div class="flex-1 p-8 ml-64">
        <h2 class="text-2xl font-bold mb-4">Registered Users</h2>
        
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Full Name</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $query = "SELECT id, full_name, email, phone FROM users";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td class='border border-gray-300 px-4 py-2'>{$row['id']}</td>
                        <td class='border border-gray-300 px-4 py-2'>{$row['full_name']}</td>
                        <td class='border border-gray-300 px-4 py-2'>{$row['email']}</td>
                        <td class='border border-gray-300 px-4 py-2'>{$row['phone']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
<?php
$conn->close();
?>