<?php
session_start();
require '../config/config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pickup_location = trim($_POST['pickup_location']);
    $dropoff_location = trim($_POST['dropoff_location']);

    // Static distance for now (Change this once Google API is integrated)
    $distance = 5; // 5 KM

    // Fare Calculation (₹50 base fare + ₹15 per KM)
    $base_fare = 50;
    $per_km_rate = 15;
    $total_fare = $base_fare + ($distance * $per_km_rate);

    // Insert into rides table
    $stmt = $conn->prepare("INSERT INTO rides (user_id, pickup_location, dropoff_location, distance, fare, status, ride_date) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
    $stmt->bind_param("issdd", $user_id, $pickup_location, $dropoff_location, $distance, $total_fare);

    if ($stmt->execute()) {
        $ride_id = $stmt->insert_id;

        // Fetch all available drivers
        $driver_query = "SELECT user_id FROM users WHERE role = 'driver'";
        $drivers = $conn->query($driver_query);

        while ($driver = $drivers->fetch_assoc()) {
            $driver_id = $driver['user_id'];

            // Insert ride request for each driver
            $request_stmt = $conn->prepare("INSERT INTO ride_requests (ride_id, driver_id, status) VALUES (?, ?, 'Pending')");
            $request_stmt->bind_param("ii", $ride_id, $driver_id);
            $request_stmt->execute();
        }

        $_SESSION['booking_success'] = "Ride booked successfully! Fare: ₹$total_fare";
        header("Location: userHome.php");
        exit();
    } else {
        $_SESSION['booking_error'] = "Error booking ride. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Ride</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Online Taxi Booking - Book a Ride</h1>
        <a href="userHome.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Dashboard</a>
    </nav>

    <div class="flex">
        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Hello, <?= htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="userHome.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">🏠 Home</a></li>
                <li><a href="rideHistory.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">📜 Ride History</a></li>
                <li><a href="profile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit Profile</a></li>
            </ul>
        </aside>

        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Book a Ride 🚖</h2>

            <?php if (isset($_SESSION['booking_success'])): ?>
                <p class="text-green-600"><?= $_SESSION['booking_success']; ?></p>
                <?php unset($_SESSION['booking_success']); ?>
            <?php elseif (isset($_SESSION['booking_error'])): ?>
                <p class="text-red-600"><?= $_SESSION['booking_error']; ?></p>
                <?php unset($_SESSION['booking_error']); ?>
            <?php endif; ?>

            <div class="bg-white p-6 shadow-lg rounded-lg">
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Pickup Location</label>
                        <input type="text" name="pickup_location" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Drop-off Location</label>
                        <input type="text" name="dropoff_location" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        Confirm Ride
                    </button>
                </form>
            </div>
        </main>

    </div>

</body>
</html>
