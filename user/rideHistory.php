<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../config/config.php';

$user_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch user's ride history
$query = "
    SELECT r.id AS ride_id, r.pickup_location, r.dropoff_location,r.payment_status, r.distance, r.fare, r.status, 
           d.full_name AS driver_name, d.phone AS driver_phone
    FROM rides r
    LEFT JOIN drivers d ON r.driver_id = d.id
    WHERE r.user_id = ?
    ORDER BY r.ride_date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Ride History</h1>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Welcome, <?php echo htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="userHome.php" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">🏠
                        Home</a></li>
                <li><a href="rideHistory.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">📜
                        Ride History</a></li>
                <li><a href="profile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit
                        Profile</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Your Ride History</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <table class="w-full border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-3">Pickup</th>
                                <th class="border p-3">Dropoff</th>
                                <th class="border p-3">Distance</th>
                                <th class="border p-3">Fare</th>
                                <th class="border p-3">Driver</th>
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
                                    <td class="border p-3">
                                        <?= $row['driver_name'] ? htmlspecialchars($row['driver_name']) . " (📞 " . $row['driver_phone'] . ")" : "Not Assigned"; ?>
                                    </td>
                                    <td class="border p-3 font-bold 
                                        <?php if ($row['status'] == 'Accepted')
                                            echo 'text-green-600'; ?>
                                        <?php if ($row['status'] == 'Pending')
                                            echo 'text-yellow-500'; ?>
                                        <?php if ($row['status'] == 'Rejected')
                                            echo 'text-red-600'; ?>
                                        <?php if ($row['status'] == 'Completed')
                                            echo 'text-blue-600'; ?>">
                                        <?= $row['status']; ?>
                                    </td>
                                    <td class="border p-3">
                                        <?php if ($row['status'] == 'Accepted' && $row['payment_status'] == 'Pending'): ?>
                                            <form method="POST" action="processPayment.php">
                                                <input type="hidden" name="ride_id"
                                                    value="<?= htmlspecialchars($row['ride_id']); ?>">

                                                <button type="submit"
                                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Pay
                                                    Now</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-gray-600"><?= $row['payment_status']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">No ride history available.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>