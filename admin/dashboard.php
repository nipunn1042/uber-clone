<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <?php include '../components/sidebar.php'; ?>

    <main class="ml-64 flex-1 p-10">
        <div id="content">
            <h1 class="text-3xl font-bold">Welcome, Admin</h1>
            <p class="mt-2 text-gray-600">Use the sidebar to manage users and drivers.</p>
        </div>
    </main>

</body>
</html>
