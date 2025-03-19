<?php
session_start();
require '../config/config.php'; // Database connection

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $ride_date = date("Y-m-d H:i:s"); // Corrected format usage
    $status = "Pending"; // Default status

    // Insert ride request into database
    $query = "INSERT INTO rides (user_id, pickup_location, dropoff_location, status, ride_date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $pickup, $dropoff, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Ride booked successfully!'); window.location.href='userHome.php';</script>";
    } else {
        echo "<script>alert('Error booking ride. Try again!');</script>";
    }
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

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Online Taxi Booking - Book a Ride</h1>
        <a href="userHome.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Dashboard</a>
    </nav>

    <div class="flex">

        <!-- Sidebar -->
        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Hello, <?php echo htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="userHome.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">🏠
                        Home</a></li>
                <li><a href="rideHistory.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">📜
                        Ride History</a></li>
                <li><a href="profile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit
                        Profile</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Book a Ride 🚖</h2>

            <div class="bg-white p-6 shadow-lg rounded-lg">
                <form method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Pickup Location</label>
                        <input type="text" name="pickup" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Drop-off Location</label>
                        <input type="text" name="dropoff" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                    </div>

                        <!-- <div class="mb-4">
                            <label class="block text-gray-700 font-bold">Ride Type</label>
                            <select name="ride_type" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300">
                                <option value="Economy">🚗 Economy</option>
                                <option value="Premium">🚘 Premium</option>
                                <option value="Luxury">🚖 Luxury</option>
                            </select>
                        </div> -->

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