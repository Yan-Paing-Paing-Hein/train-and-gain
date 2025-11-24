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
    $stmt = $conn->prepare("
        SELECT pr.id, pr.coach_id, pr.is_used, pr.requested_at
        FROM coach_password_resets pr
        WHERE pr.token = ?
    ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $reset = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$reset || $reset['is_used'] == 1) {
        die("<h1 style='text-align:center; margin-top:50px;'>Invalid or expired token!</h1>");
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update coach password
    $stmt = $conn->prepare("UPDATE coach SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $reset['coach_id']);
    $stmt->execute();
    $stmt->close();

    // Mark token as used
    $stmt = $conn->prepare("UPDATE coach_password_resets SET is_used = 1 WHERE id = ?");
    $stmt->bind_param("i", $reset['id']);
    $stmt->execute();
    $stmt->close();

    header("Location: login_form.php?success=Password has been reset successfully. Please log in.");
    exit();
}
