<?php
require_once("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // Check if user exists
    $stmt = $conn->prepare("SELECT id, name FROM client_registered WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Generate token
        $token = bin2hex(random_bytes(32));

        // Insert into password_resets
        $stmt2 = $conn->prepare("INSERT INTO password_resets (client_id, token) VALUES (?, ?)");
        $stmt2->bind_param("is", $user['id'], $token);
        $stmt2->execute();
        $stmt2->close();

        // Notify user to wait for admin
        header("Location: forgot_password.php?success=Your request has been sent to admin. Please wait for the reset link.");
        exit();
    } else {
        header("Location: forgot_password.php?error=No account found with that email.");
        exit();
    }
}
