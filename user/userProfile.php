<?php
session_start();
require '../config/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT full_name, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$fullName = $user['full_name']; // Needed for sidebar display

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];

    $updateQuery = "UPDATE users SET full_name = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $full_name, $phone, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: userProfile.php");
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
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <h1 class="text-xl font-bold">Online Taxi Booking - User Profile</h1>
        <a href="../logout.php" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-1/5 bg-white h-screen shadow-lg p-6">
            <h2 class="text-lg font-bold mb-6">Welcome, <?php echo htmlspecialchars($fullName); ?> 👋</h2>
            <ul class="space-y-4">
                <li><a href="bookRide.php" class="block p-2 bg-blue-500 text-white rounded hover:bg-blue-600">🚖 Book a Ride</a></li>
                <li><a href="rideHistory.php" class="block p-2 bg-green-500 text-white rounded hover:bg-green-600">📜 Ride History</a></li>
                <li><a href="userProfile.php" class="block p-2 bg-gray-500 text-white rounded hover:bg-gray-600">⚙️ Edit Profile</a></li>
            </ul>
        </aside>

        <!-- Profile Form -->
        <main class="w-4/5 p-8">
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
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" class="w-full p-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Email (Read-only)</label>
                    <input type="email" value="<?= htmlspecialchars($user['email']); ?>" class="w-full p-2 border rounded bg-gray-200" readonly>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" class="w-full p-2 border rounded" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Profile</button>
            </form>
        </main>
    </div>

</body>

</html>
