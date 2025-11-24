<?php
require_once("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    // Check if coach exists
    $stmt = $conn->prepare("SELECT id, full_name FROM coach WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $coach = $result->fetch_assoc();

        // 1. Check if pending request already exists
        $stmtCheck = $conn->prepare("
            SELECT id FROM coach_password_resets 
            WHERE coach_id = ? AND is_used = 0
        ");
        $stmtCheck->bind_param("i", $coach['id']);
        $stmtCheck->execute();
        $checkResult = $stmtCheck->get_result();

        if ($checkResult->num_rows > 0) {
            header("Location: forgot_password.php?error=You already have a pending reset request. Please wait for admin.");
            exit();
        }
        $stmtCheck->close();

        // 2. Generate new token
        $token = bin2hex(random_bytes(32));

        $stmt2 = $conn->prepare("INSERT INTO coach_password_resets (coach_id, token) VALUES (?, ?)");
        $stmt2->bind_param("is", $coach['id'], $token);
        $stmt2->execute();
        $stmt2->close();

        header("Location: forgot_password.php?success=Your request has been sent to admin. Please wait for the reset link.");
        exit();
    } else {
        header("Location: forgot_password.php?error=No coach found with that email.");
        exit();
    }
}
