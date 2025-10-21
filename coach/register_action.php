<?php
session_start();
include("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate required fields
    if (empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error_message'] = "Please fill in all fields.";
        header("Location: register_form.php");
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match.";
        header("Location: register_form.php");
        exit();
    }

    // Check if email exists in coach table
    $stmt = $conn->prepare("SELECT * FROM coach WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = "This email is not listed by admin.";
        header("Location: register_form.php");
        exit();
    }

    $coach = $result->fetch_assoc();

    // Check if this coach already registered before
    if (!empty($coach['password'])) {
        $_SESSION['error_message'] = "This account has already been registered.";
        header("Location: register_form.php");
        exit();
    }

    // Securely hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update password for this coach
    $update = $conn->prepare("UPDATE coach SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashed_password, $email);

    if ($update->execute()) {
        // Store session data
        $_SESSION['coach_id'] = $coach['id'];
        $_SESSION['coach_name'] = $coach['full_name'];
        $_SESSION['coach_email'] = $coach['email'];
        $_SESSION['coach_profile'] = $coach['profile_picture'];
        $_SESSION['coach_specialty'] = $coach['specialty'];
        $_SESSION['coach_experience'] = $coach['experience'];
        $_SESSION['coach_about'] = $coach['about'];
        $_SESSION['coach_phone'] = $coach['phone_number'];
        $_SESSION['coach_status'] = $coach['status'];

        header("Location: home.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Something went wrong. Please try again.";
        header("Location: register_form.php");
        exit();
    }

    $stmt->close();
    $update->close();
    $conn->close();
} else {
    header("Location: register_form.php");
    exit();
}
