<?php
ob_start();  // ✅ Start output buffering
session_start();
include 'config/config.php';  

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // ✅ Get selected role from form

    if (empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href='login.php';</script>";
        exit();
    }

    if ($role === "user") {
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
    } elseif ($role === "driver") {
        $stmt = $conn->prepare("SELECT id, full_name, email, password FROM drivers WHERE email = ?");
    } else {
        echo "<script>alert('Invalid role selected!'); window.location.href='login.php';</script>";
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = $role; // ✅ Save role in session

            if ($role === "user") {
                header("Location: user/userHome.php");
            } elseif ($role === "driver") {
                header("Location: driver/driverHome.php");
            }
            exit();
        }
    }

    $stmt->close();

    echo "<script>alert('Invalid email or password!'); window.location.href='login.php';</script>";
    exit();
}

ob_end_flush(); // ✅ End output buffering
?>
