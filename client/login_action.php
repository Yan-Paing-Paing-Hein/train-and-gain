<?php
// Start session at the very beginning
session_start();

// Include DB connection
require_once("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // 1. Check if email exists
    $stmt = $conn->prepare("SELECT id, name, email, password FROM client_registered WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email found
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // 2. Verify password
        if (password_verify($password, $row['password'])) {
            // Password correct → Set session
            $_SESSION['client_id']    = $row['id'];
            $_SESSION['client_name']  = $row['name'];
            $_SESSION['client_email'] = $row['email'];

            // Optional: regenerate session ID for security
            session_regenerate_id(true);

            // 3. Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Wrong password
            header("Location: login_form.php?error=Invalid email or password.");
            exit();
        }
    } else {
        // Email not found
        header("Location: login_form.php?error=Invalid email or password.");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If request not POST, block direct access
    header("Location: login_form.php?error=Invalid request.");
    exit();
}
