<?php
session_start();
require '../config/config.php';

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

        <?php if (isset($_SESSION['success'])): ?>
            <p class="bg-green-500 text-white px-4 py-2 rounded mb-4"><?= $_SESSION['success']; ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="bg-red-500 text-white px-4 py-2 rounded mb-4"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Full Name</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Phone</th>
                    <th class="border border-gray-300 px-4 py-2">Action</th>
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
                        <td class='border border-gray-300 px-4 py-2'>
                <form action='deleteUser.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this user?\");'>
                    <input type='hidden' name='user_id' value='{$row['id']}'>
                    <button type='submit' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600'>Delete</button>
                </form>
            </td>
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