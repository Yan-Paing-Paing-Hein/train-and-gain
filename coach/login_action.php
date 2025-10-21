<?php
session_start();
include("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if fields are filled
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Please fill in all fields.";
        header("Location: login_form.php");
        exit();
    }

    // Check if email exists in coach table
    $stmt = $conn->prepare("SELECT * FROM coach WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error_message'] = "This email is not listed by admin.";
        header("Location: login_form.php");
        exit();
    }

    $coach = $result->fetch_assoc();

    // Check if coach has registered before (password not empty)
    if (empty($coach['password'])) {
        $_SESSION['error_message'] = "This coach has not completed registration yet.";
        header("Location: login_form.php");
        exit();
    }

    // Verify password
    if (!password_verify($password, $coach['password'])) {
        $_SESSION['error_message'] = "Incorrect password. Please try again.";
        header("Location: login_form.php");
        exit();
    }

    // Check account status
    if ($coach['status'] !== 'Active') {
        $_SESSION['error_message'] = "Your account is currently inactive. Please contact admin.";
        header("Location: login_form.php");
        exit();
    }

    // Store session data for logged-in coach
    $_SESSION['coach_id'] = $coach['id'];
    $_SESSION['coach_name'] = $coach['full_name'];
    $_SESSION['coach_email'] = $coach['email'];
    $_SESSION['coach_profile'] = $coach['profile_picture'];
    $_SESSION['coach_specialty'] = $coach['specialty'];
    $_SESSION['coach_experience'] = $coach['experience'];
    $_SESSION['coach_about'] = $coach['about'];
    $_SESSION['coach_phone'] = $coach['phone_number'];
    $_SESSION['coach_status'] = $coach['status'];

    // Redirect to coach home
    header("Location: home.php");
    exit();
} else {
    header("Location: login_form.php");
    exit();
}
