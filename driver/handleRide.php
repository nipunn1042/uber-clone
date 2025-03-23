<?php
session_start();
include '../config/config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'driver') {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ride_id'], $_POST['action'])) {
    $ride_id = intval($_POST['ride_id']);
    $driver_id = $_SESSION['user_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Check if the ride is already accepted by another driver
        $checkQuery = "SELECT driver_id FROM rides WHERE id = ? AND status = 'Accepted'";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("i", $ride_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "Ride already accepted by another driver."]);
            exit();
        }

        // Start transaction to avoid conflicts
        $conn->begin_transaction();

        try {
            // Update the ride_requests table (DO NOT INSERT, ONLY UPDATE)
            $updateRideRequests = "UPDATE ride_requests SET status = 'Accepted' WHERE ride_id = ? AND driver_id = ?";
            $stmt = $conn->prepare($updateRideRequests);
            $stmt->bind_param("ii", $ride_id, $driver_id);
            $stmt->execute();

            // Update rides table to reflect acceptance
            $updateRides = "UPDATE rides SET status = 'Accepted', driver_id = ? WHERE id = ?";
            $stmt = $conn->prepare($updateRides);
            $stmt->bind_param("ii", $driver_id, $ride_id);
            $stmt->execute();

            // Remove ride requests from other drivers (Ensures no duplication)
            $deleteOtherRequests = "DELETE FROM ride_requests WHERE ride_id = ? AND driver_id != ?";
            $stmt = $conn->prepare($deleteOtherRequests);
            $stmt->bind_param("ii", $ride_id, $driver_id);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            echo json_encode(["success" => true, "status" => "Accepted", "ride_id" => $ride_id]);

        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Failed to accept ride."]);
        }

    } elseif ($action === 'reject') {
        // If the driver rejects the ride, update the status instead of duplicating it
        $updateQuery = "UPDATE ride_requests SET status = 'Rejected' WHERE ride_id = ? AND driver_id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ii", $ride_id, $driver_id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "status" => "Rejected", "ride_id" => $ride_id]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to reject ride."]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
