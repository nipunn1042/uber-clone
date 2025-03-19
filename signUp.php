<?php
include 'config/config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ? UNION SELECT id FROM drivers WHERE email = ?");
    $checkEmail->bind_param("ss", $email, $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already exists!'); window.location.href='signup.html';</script>";
        exit();
    }

    if ($role === "user") {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $password);
    } elseif ($role === "driver") {
        $license_number = trim($_POST['license_number']);
        $car_number = trim($_POST['car_number']);
        $stmt = $conn->prepare("INSERT INTO drivers (full_name, email, password, license_number, vehicle_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $full_name, $email, $password, $license_number, $car_number);
    } else {
        echo "<script>alert('Invalid role selection!'); window.location.href='signup.html';</script>";
        exit();
    }

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! Please log in.'); window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Something went wrong. Try again!'); window.location.href='signup.html';</script>";
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative h-screen w-full">
    <!-- Background Image -->
    <div
        class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1490650404312-a2175773bbf5?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')] bg-cover bg-center">
    </div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Signup Form -->
    <div class="relative flex justify-center items-center h-screen">
        <div class="bg-white p-6 shadow-lg rounded-lg w-96 z-10">
            <h2 class="text-2xl font-bold text-center mb-4">Sign Up</h2>

            <form action="" method="POST" class="space-y-4">
                <input type="text" name="name" placeholder="Full Name" required class="w-full border p-2 rounded">
                <input type="email" name="email" placeholder="Email" required class="w-full border p-2 rounded">
                <input type="password" name="password" placeholder="Password" required
                    class="w-full border p-2 rounded">

                <!-- Role Selection -->
                <select name="role" id="roleSelect" required class="w-full border p-2 rounded"
                    onchange="toggleDriverFields()">
                    <option value="user">Sign up as User</option>
                    <option value="driver">Sign up as Driver</option>
                </select>

                <!-- Driver-Only Fields (Hidden by Default) -->
                <div id="driverFields" class="hidden space-y-4">
                    <input type="text" name="license_number" placeholder="License Number"
                        class="w-full border p-2 rounded">
                    <input type="text" name="car_number" placeholder="Car Number" class="w-full border p-2 rounded">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Register
                </button>
            </form>

            <p class="text-sm text-center mt-3">Already have an account?
                <a href="login.php" class="text-blue-600">Login</a>
            </p>
        </div>
    </div>

    <!-- JavaScript for Showing/Hiding Driver Fields -->
    <script>
        function toggleDriverFields() {
            const roleSelect = document.getElementById("roleSelect");
            const driverFields = document.getElementById("driverFields");

            if (roleSelect.value === "driver") {
                driverFields.classList.remove("hidden");
            } else {
                driverFields.classList.add("hidden");
            }
        }
    </script>
</body>

</html>