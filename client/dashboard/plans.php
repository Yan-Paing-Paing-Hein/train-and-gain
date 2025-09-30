<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];
$result = $conn->prepare("SELECT survey_completed, plan_selected FROM client_process WHERE client_id = ?");
$result->bind_param("i", $client_id);
$result->execute();
$progress = $result->get_result()->fetch_assoc();

// Block if survey not completed
if (basename($_SERVER['PHP_SELF']) == "plans.php" && !$progress['survey_completed']) {
    die("Please complete Step 1 (Survey) first.");
}

// Block if payment.php and steps not finished
if (basename($_SERVER['PHP_SELF']) == "payment.php" && (!$progress['survey_completed'] || !$progress['plan_selected'])) {
    die("Please complete Step 1 and Step 2 first.");
}
