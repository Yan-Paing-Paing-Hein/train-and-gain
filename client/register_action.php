<?php
// Start session at the very beginning
session_start();

// Include DB connection
require_once("../db_connect.php");

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name             = trim($_POST['name']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validate password match
    if ($password !== $confirm_password) {
        header("Location: register_form.php?error=Passwords do not match!");
        exit();
    }

    // 2. Check if email already exists
    $check = $conn->prepare("SELECT id FROM client_registered WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        header("Location: register_form.php?error=This email is already registered. Please log in.");
        exit();
    }
    $check->close();

    // 3. Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 4. Insert into DB
    $stmt = $conn->prepare("INSERT INTO client_registered (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Registration success → auto-login
        $newUserId = $stmt->insert_id; // get newly created user ID

        $_SESSION['client_id']    = $newUserId;
        $_SESSION['client_name']  = $name;
        $_SESSION['client_email'] = $email;

        // Optional: regenerate session ID
        session_regenerate_id(true);

        // Redirect to dashboard
        header("Location: dashboard/home.php");
        exit();
    } else {
        header("Location: register_form.php?error=Something went wrong. Please try again.");
        exit();
    }

    $stmt->close();
    $conn->close();
}
