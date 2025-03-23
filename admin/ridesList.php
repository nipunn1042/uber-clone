<?php
session_start();
require '../config/config.php';


if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all rides with user and driver details
$query = "
    SELECT r.id AS ride_id, r.pickup_location, r.dropoff_location, r.distance, r.fare, 
           r.status, r.payment_status, r.ride_date, 
           u.full_name AS user_name, u.phone AS user_phone,
           d.full_name AS driver_name, d.phone AS driver_phone
    FROM rides r
    LEFT JOIN users u ON r.user_id = u.id
    LEFT JOIN drivers d ON r.driver_id = d.id
    ORDER BY r.ride_date DESC
";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Ride Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen bg-gray-100">


    <?php include '../components/sidebar.php'; ?>

    <!-- Main -->
    <main class="ml-64 flex-1 p-10">
        <h2 class="text-2xl font-bold mb-6">All Rides</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <table class="w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-3">Ride ID</th>
                            <th class="border p-3">User</th>
                            <th class="border p-3">Driver</th>
                            <th class="border p-3">Pickup</th>
                            <th class="border p-3">Dropoff</th>
                            <th class="border p-3">Distance</th>
                            <th class="border p-3">Fare</th>
                            <th class="border p-3">Status</th>
                            <th class="border p-3">Payment</th>
                            <th class="border p-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-b">
                                <td class="border p-3"><?= $row['ride_id']; ?></td>
                                <td class="border p-3"><?= htmlspecialchars($row['user_name']) . "<br>" . $row['user_phone']; ?>
                                </td>
                                <td class="border p-3">
                                    <?= $row['driver_name'] ? htmlspecialchars($row['driver_name']) . "<br>" . $row['driver_phone'] : "Not Assigned"; ?>
                                </td>
                                <td class="border p-3"><?= htmlspecialchars($row['pickup_location']); ?></td>
                                <td class="border p-3"><?= htmlspecialchars($row['dropoff_location']); ?></td>
                                <td class="border p-3"><?= $row['distance']; ?> KM</td>
                                <td class="border p-3">₹<?= $row['fare']; ?></td>
                                <td class="border p-3">
                                    <span
                                        class="px-2 py-1 rounded 
                                        <?= $row['status'] === 'Pending' ? 'bg-yellow-400' : ($row['status'] === 'Accepted' ? 'bg-green-400' : 'bg-red-400'); ?>">
                                        <?= $row['status']; ?>
                                    </span>
                                </td>
                                <td class="border p-3">
                                    <span class="px-2 py-1 rounded 
                                        <?= $row['payment_status'] === 'Paid' ? 'bg-green-400' : 'bg-red-400'; ?>">
                                        <?= $row['payment_status'] ?: 'Not Paid'; ?>
                                    </span>
                                </td>
                                <td class="border p-3"><?= date('d M Y, H:i', strtotime($row['ride_date'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-600">No rides available at the moment.</p>
        <?php endif; ?>
    </main>

</body>

</html>