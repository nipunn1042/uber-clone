<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-gray-100">

    <!-- Sidebar (Fixed) -->
    <aside class="w-64 bg-gray-800 text-white h-screen p-5 fixed">
        <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>
        <nav>
            <ul class="space-y-3">
                <li><a href="dashboard.html" class="block py-2 px-4 rounded bg-gray-700">Dashboard</a></li>
                <li><a href="users.html" class="block py-2 px-4 rounded bg-gray-700">View Users</a></li>
                <li><a href="drivers.html" class="block py-2 px-4 rounded bg-gray-700">View Drivers</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 flex-1 p-10">
        <!-- This will change per page -->
        <div id="content">
            <h1 class="text-3xl font-bold">Welcome, Admin</h1>
            <p class="mt-2 text-gray-600">Use the sidebar to manage users and drivers.</p>
        </div>
    </main>

</body>
</html>
