<?php
session_start();
require '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

$user_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch user's previous rides
$query = "SELECT pickup_location, dropoff_location, fare, status, ride_date FROM rides WHERE user_id = ? ORDER BY ride_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$rides = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Online Taxi Booking - User Dashboard</h1>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>

    <div class="flex">
        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Welcome, <?php echo htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="bookRide.php" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">🚖 Book a
                        Ride</a></li>
                <li><a href="rideHistory.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">📜
                        Ride History</a></li>
                <li><a href="userProfile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit
                        Profile</a></li>
            </ul>
        </aside>
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Your Previous Rides</h2>

            <?php if (count($rides) > 0): ?>
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-gray-300 p-3">Pickup Location</th>
                                <th class="border border-gray-300 p-3">Dropoff Location</th>
                                <th class="border border-gray-300 p-3">Fare (₹)</th>
                                <th class="border border-gray-300 p-3">Status</th>
                                <th class="border border-gray-300 p-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rides as $ride): ?>
                                <tr class="text-center">
                                    <td class="border border-gray-300 p-3">
                                        <?php echo htmlspecialchars($ride['pickup_location']); ?>
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                        <?php echo htmlspecialchars($ride['dropoff_location']); ?>
                                    </td>
                                    <td class="border border-gray-300 p-3">₹ <?php echo htmlspecialchars($ride['fare']); ?></td>
                                    <td class="border border-gray-300 p-3">
                                        <span
                                            class="px-2 py-1 rounded text-white
                                            <?php echo $ride['status'] === 'Completed' ? 'bg-green-500' : ($ride['status'] === 'Pending' ? 'bg-yellow-500' : 'bg-red-500'); ?>">
                                            <?php echo htmlspecialchars($ride['status']); ?>
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 p-3">
                                        <?php echo date("d M Y, H:i", strtotime($ride['ride_date'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="bg-white p-6 shadow-lg rounded-lg text-center">
                    <h3 class="text-xl font-bold">No rides yet! 🚖</h3>
                    <p class="text-gray-600 mt-2">Book a ride now and start your journey.</p>
                    <a href="bookRide.php"
                        class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Book a Ride</a>
                </div>
            <?php endif; ?>
        </main>

    </div>

    <script>
        (function () {
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.go(1); // Redirect forward to login if user presses back
            };
        })();
    </script>

</body>

</html>