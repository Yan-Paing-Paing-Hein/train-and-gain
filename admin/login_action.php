<?php
session_start();
require_once "admin_config.php";

// Get input from form
$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Validate admin credentials
if ($email === $admin_email && password_verify($password, $admin_hashed_password)) {
    // Correct credentials
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = $admin_email;
    header("Location: admin_home.php");
    exit();
} else {
    // Invalid credentials
    header("Location: login_form.php?error=1");
    exit();
}
