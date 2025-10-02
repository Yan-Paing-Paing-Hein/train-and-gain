<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}
$client_id = $_SESSION['client_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_type = $_POST['plan_type'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $card_number = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvc = $_POST['cvc'] ?? '';
    $agree = $_POST['agree'] ?? '';

    if (!$agree) {
        die("You must agree to the terms.");
    }

    // Determine amount
    switch ($plan_type) {
        case 'Monthly':
            $amount = 50.00;
            break;
        case 'Six-Month':
            $amount = 240.00;
            break;
        case 'Yearly':
            $amount = 360.00;
            break;
        default:
            die("Invalid plan selected.");
    }

    // Simulate payment
    $transaction_id = "SIM" . time();
    $status = "Completed";

    // Insert payment
    $stmt = $conn->prepare("INSERT INTO client_payment (client_id, plan_type, amount, payment_method, transaction_id, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsss", $client_id, $plan_type, $amount, $payment_method, $transaction_id, $status);
    $stmt->execute();
    $stmt->close();

    // Update process
    $stmt2 = $conn->prepare("UPDATE client_process SET payment_done = 1 WHERE client_id=?");
    $stmt2->bind_param("i", $client_id);
    $stmt2->execute();
    $stmt2->close();

    echo "<h1 style='text-align:center; margin-top:50px;'>Payment Successful 🎉 Welcome to Train&Gain!</h1>";
    exit();
} else {
    die("Invalid request.");
}
