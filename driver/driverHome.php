<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    header("Location: ../login.php");
    exit();
}

include '../config/config.php';

$driver_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];

// Fetch available ride requests for the driver
$query = "
    SELECT rr.ride_id, rr.status, r.pickup_location, r.dropoff_location, r.distance, r.fare
    FROM ride_requests rr
    JOIN rides r ON rr.ride_id = r.id
    WHERE rr.driver_id = ?";
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
    <title>Driver Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Driver Dashboard</h1>
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
                <li><a href="driverProfile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️
                        Edit
                        Profile</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <main class="w-4/5 p-8">
            <h2 class="text-2xl font-bold mb-6">Available Ride Requests</h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="bg-white p-6 shadow-lg rounded-lg">
                    <table class="w-full border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-3">Pickup</th>
                                <th class="border p-3">Dropoff</th>
                                <th class="border p-3">Distance</th>
                                <th class="border p-3">Fare</th>
                                <th class="border p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="border-b" id="ride-<?= $row['ride_id']; ?>">
                                    <td class="border p-3"><?= htmlspecialchars($row['pickup_location']); ?></td>
                                    <td class="border p-3"><?= htmlspecialchars($row['dropoff_location']); ?></td>
                                    <td class="border p-3"><?= $row['distance']; ?> KM</td>
                                    <td class="border p-3">₹<?= $row['fare']; ?></td>
                                    <td class="border p-3">
                                        <?php if ($row['status'] == 'Accepted'): ?>
                                            <span class="text-green-500 font-bold">Accepted ✅</span>
                                        <?php elseif ($row['status'] == 'Rejected'): ?>
                                            <span class="text-red-500 font-bold">Rejected ❌</span>
                                        <?php else: ?>
                                            <span class="text-gray-500 font-bold">Pending ⏳</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border p-3">
                                        <?php if ($row['status'] == 'Pending'): ?>
                                            <button class="accept bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                                                data-ride-id="<?= $row['ride_id']; ?>">Accept</button>
                                            <button class="reject bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                                                data-ride-id="<?= $row['ride_id']; ?>">Reject</button>
                                        <?php else: ?>
                                            <span class="font-bold"><?= htmlspecialchars($row['status']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">No new ride requests at the moment.</p>
            <?php endif; ?>
        </main>
    </div>

    <script>
        $(document).ready(function () {
            $(".accept, .reject").click(function () {
                var rideId = $(this).data("ride-id");
                var action = $(this).hasClass("accept") ? "accept" : "reject";
                var buttonRow = $("#ride-" + rideId); // Get the row for the ride

                $.ajax({
                    type: "POST",
                    url: "handleRide.php",
                    data: { ride_id: rideId, action: action },
                    success: function (response) {
                        try {
                            var json = JSON.parse(response);

                            if (json.success) {
                                if (action === "accept") {
                                    // Remove ride from all driver dashboards
                                    $(".ride-row[data-ride-id='" + rideId + "']").remove();
                                } else {
                                    // Update status text if rejected
                                    buttonRow.find(".status").text(json.status);
                                    buttonRow.find(".accept, .reject").remove();
                                }
                            } else {
                                alert(json.message);
                            }
                        } catch (e) {
                            console.error("Invalid JSON Response", response);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            });

            // Polling to check for ride updates (every 5 seconds)
            setInterval(function () {
                location.reload();
            }, 5000);
        });

    </script>

</body>


</html>