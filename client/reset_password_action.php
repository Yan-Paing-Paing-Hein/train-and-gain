<?php
require_once("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header("Location: reset_password.php?token=" . urlencode($token) . "&error=Passwords do not match!");
        exit();
    }

    // Verify token
    $stmt = $conn->prepare("SELECT pr.id, pr.client_id, pr.requested_at, pr.is_used 
                            FROM password_resets pr
                            WHERE pr.token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();
    $stmt->close();

    if (!$reset || $reset['is_used'] == 1) {
        die("<h1>Invalid or expired token!</h1>");
    }

    if (time() - strtotime($reset['requested_at']) > 3600) {
        die("<h1>This reset link has expired. Please request again!</h1>");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update user's password
    $stmt = $conn->prepare("UPDATE client_registered SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $reset['client_id']);
    $stmt->execute();
    $stmt->close();

    // Mark token as used
    $stmt = $conn->prepare("UPDATE password_resets SET is_used = 1 WHERE id = ?");
    $stmt->bind_param("i", $reset['id']);
    $stmt->execute();
    $stmt->close();

    header("Location: login_form.php?success=Password has been reset successfully. Please log in.");
    exit();
}
