<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1490650404312-a2175773bbf5?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center/cover;
            background-size: cover;
        }

        .overlay {
            background: rgba(0, 0, 0, 0.5);
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
    </style>
</head>

<body class="flex items-center justify-center flex-col h-screen relative">

    <a href="admin/admin_login.php"
        class="absolute top-4 right-4 bg-blue-800 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition z-50">
        Admin Login
    </a>

    <div class="overlay"></div>

    <div class="bg-white p-8 rounded-lg shadow-lg z-10 relative w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

        <form action="process_login.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Login as</label>
                <select name="role"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                    <option value="user">User</option>
                    <option value="driver">Driver</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">Login</button>
        </form>

        <p class="text-center text-gray-600 mt-4">
            Don't have an account?
            <a href="signUp.php" class="text-blue-600 hover:underline">Sign up</a>
        </p>
    </div>

</body>

</html>