<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: ../login.php");
    exit();
}

include '../config/config.php';

$driver_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch only accepted rides
$query = "
    SELECT rr.ride_id, r.pickup_location, r.dropoff_location, r.distance, r.fare, rr.status,r.payment_status
    FROM ride_requests rr
    JOIN rides r ON rr.ride_id = r.id
    WHERE rr.driver_id = ? AND rr.status = 'Accepted'
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Rides</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Accepted Rides</h1>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>

    <div class="flex">

        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Welcome, <?php echo htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="driverHome.php" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">🚖
                        Available Rides</a></li>
                <li><a href="acceptedRides.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">✅
                        Accepted Rides</a></li>
                <li><a href="profile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit
                        Profile</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Your Accepted Rides</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <table class="w-full border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-3">Pickup</th>
                                <th class="border p-3">Dropoff</th>
                                <th class="border p-3">Distance</th>
                                <th class="border p-3">Fare</th>
                                <th class="border p-3">Status</th>
                                <th class="border p-3">Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="border-b">
                                    <td class="border p-3"><?= htmlspecialchars($row['pickup_location']); ?></td>
                                    <td class="border p-3"><?= htmlspecialchars($row['dropoff_location']); ?></td>
                                    <td class="border p-3"><?= $row['distance']; ?> KM</td>
                                    <td class="border p-3">₹<?= $row['fare']; ?></td>
                                    <td class="border p-3 text-green-600 font-bold"><?= $row['status']; ?></td>
                                    <td class="border p-3">
                                        <span
                                            class="text-<?php echo ($row['payment_status'] == 'Received') ? 'green' : 'red'; ?>-500 font-bold">
                                            <?= $row['payment_status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">No accepted rides at the moment.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>