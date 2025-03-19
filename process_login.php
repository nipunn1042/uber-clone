<?php
session_start();
include 'config/config.php';  // Database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href='login.php';</script>";
        exit();
    }

    // Check in users table
    $stmt = $conn->prepare("SELECT id, full_name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $email, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = 'user';

            header("Location: user/userHome.php");
            exit();
        }
    }
    $stmt->close();

    // Check in drivers table
    $stmt = $conn->prepare("SELECT id, full_name, email, password FROM drivers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $email, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = 'driver';

            header("Location: driver/driverHome.php");
            exit();
        }
    }
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'user') {
            header("Location: user/userHome.php");
            exit();
            } elseif ($_SESSION['role'] === 'driver') {
                header("Location: driver/driverHome.php");
                exit();
            }
        }
        $stmt->close();

        echo "<script>alert('Invalid email or password!'); window.location.href='login.php';</script>";
        exit();
    }
    ?>
