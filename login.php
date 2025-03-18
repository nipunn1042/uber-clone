<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="relative h-screen w-full">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1490650404312-a2175773bbf5?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-cover bg-center"></div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Login Form -->
    <div class="relative flex justify-center items-center h-screen">
        <div class="bg-white p-6 shadow-lg rounded-lg w-96 z-10">
            <h2 class="text-2xl font-bold text-center mb-4">Login</h2>

            <form action="" method="POST" class="space-y-4">
                <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
                <input type="password" name="password" placeholder="Password" required class="w-full border p-2 rounded">

                <select name="role" required class="w-full border p-2 rounded">
                    <option value="user">Login as User</option>
                    <option value="driver">Login as Driver</option>
                </select>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Login
                </button>
            </form>

            <p class="text-sm text-center mt-3">Don't have an account? 
                <a href="signUp.html" class="text-blue-600">Sign Up</a>
            </p>
        </div>
    </div>
</body>
</html>