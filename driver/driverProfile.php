<?php
session_start();
require '../config/config.php';

// Check if driver is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: ../login.php");
    exit();
}

$driver_id = $_SESSION['user_id'];

// Fetch driver data
$query = "SELECT full_name, email, phone, vehicle_number FROM drivers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
$driver = $result->fetch_assoc();
$fullName = $driver['full_name']; // Needed for sidebar display

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $vehicle_number = $_POST['vehicle_number'];

    $updateQuery = "UPDATE drivers SET full_name = ?, phone = ?, vehicle_number = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $full_name, $phone, $vehicle_number, $driver_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: driverProfile.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Online Taxi Booking - Driver Profile</h1>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <?php include '../components/sidebar.php'; ?>

        <!-- Profile Form -->
        <main class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <p class="bg-green-500 text-white px-4 py-2 rounded mb-4"><?= $_SESSION['success']; ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="bg-red-500 text-white px-4 py-2 rounded mb-4"><?= $_SESSION['error']; ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Profile Update Form -->
            <form method="POST" class="bg-white p-6 shadow-lg rounded-lg max-w-lg">
                <div class="mb-4">
                    <label class="block font-semibold">Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($driver['full_name']); ?>" class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($driver['phone']); ?>" class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Vehicle Number</label>
                    <input type="text" name="vehicle_number" value="<?= htmlspecialchars($driver['vehicle_number']); ?>" class="w-full p-2 border rounded" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Profile</button>
            </form>
        </main>
    </div>

</body>

</html>
