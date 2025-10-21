<?php
// ==========================
// Coach Access Protection
// ==========================
session_start();
require_once("../db_connect.php");

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Check if coach is logged in
if (!isset($_SESSION['coach_id'])) {
    header("Location: login_form.php");
    exit();
}

// Fetch logged-in coach info
$coach_id = $_SESSION['coach_id'];
$sql = "SELECT * FROM coach WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $coach_id);
$stmt->execute();
$result = $stmt->get_result();
$coach = $result->fetch_assoc();

if (!$coach) {
    session_destroy();
    header("Location: login_form.php");
    exit();
}

// Optional convenience variable for display
$full_name = htmlspecialchars($coach['full_name']);
