<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("<h1>Unauthorized access.</h1>");
}

$client_id = $_SESSION['client_id'];
$plan_type = $_POST['plan_type'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

// Check if payment already done
$stmt = $conn->prepare("SELECT payment_done FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$process = $res->fetch_assoc();
$stmt->close();

if ($process && $process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>Payment already submitted. Your payment is under review.</h1>");
}

if (
    !in_array($plan_type, ['Monthly', 'Six-Months', 'Yearly']) ||
    !in_array($payment_method, ['PayPal', 'Venmo', 'CashApp', 'GooglePay', 'ApplePay'])
) {
    die("Invalid data submitted.");
}

// Handle file upload
if (!isset($_FILES['screenshot']) || $_FILES['screenshot']['error'] !== UPLOAD_ERR_OK) {
    die("Screenshot upload failed.");
}

$upload_dir = __DIR__ . "/payments/";  // absolute path
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$filename = time() . "_" . basename($_FILES['screenshot']['name']);
$target_path = $upload_dir . $filename;
move_uploaded_file($_FILES['screenshot']['tmp_name'], $target_path);

// Relative path for DB
$db_path = "client/dashboard/payments/" . $filename;

// Insert into client_payment
$stmt = $conn->prepare("INSERT INTO client_payment (client_id, plan_type, payment_method, screenshot_path, status) VALUES (?,?,?,?, 'Pending')");
$stmt->bind_param("isss", $client_id, $plan_type, $payment_method, $db_path);
$stmt->execute();
$stmt->close();

// Mark payment_done = 1
$update = $conn->prepare("UPDATE client_process SET payment_done=1 WHERE client_id=?");
$update->bind_param("i", $client_id);
$update->execute();
$update->close();

echo "<h1 style='text-align:center; margin-top:50px;'>Your payment is under review. Please wait for admin approval.</h1>";
