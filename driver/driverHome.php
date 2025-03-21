<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: ../login.php");
    exit();
}

include '../config/config.php';

$driver_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch available ride requests (only if no driver has accepted them yet)
$sql = "SELECT r.* FROM rides r 
        LEFT JOIN ride_requests rr ON r.id = rr.ride_id AND rr.status = 'Accepted'
        WHERE r.status = 'Pending' AND rr.ride_id IS NULL";

$rideRequests = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Driver Dashboard</h1>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Welcome, <?php echo htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="driverHome.php" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">🚖 Available Rides</a></li>
                <li><a href="acceptedRides.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">✅ Accepted Rides</a></li>
                <li><a href="profile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit Profile</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Available Ride Requests</h2>

            <?php if (count($rideRequests) > 0): ?>
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <table class="w-full border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-3">Pickup</th>
                                <th class="border p-3">Dropoff</th>
                                <th class="border p-3">Fare</th>
                                <th class="border p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rideRequests as $ride): ?>
                                <tr class="text-center">
                                    <td class="border p-3"><?php echo htmlspecialchars($ride['pickup_location']); ?></td>
                                    <td class="border p-3"><?php echo htmlspecialchars($ride['dropoff_location']); ?></td>
                                    <td class="border p-3">₹<?php echo htmlspecialchars($ride['fare']); ?></td>
                                    <td class="border p-3">
                                        <form action="process_ride.php" method="POST">
                                            <input type="hidden" name="ride_id" value="<?php echo $ride['id']; ?>">
                                            <button type="submit" name="accept" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Accept</button>
                                            <button type="submit" name="reject" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">No new ride requests at the moment.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
