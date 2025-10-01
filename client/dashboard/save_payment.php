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

    if ($plan_type === 'Monthly') {
        $amount = 50.00;
    } elseif ($plan_type === 'Yearly') {
        $amount = 480.00;
    } else {
        die("Invalid plan selected.");
    }

    // For now, we simulate payment success
    $status = 'Completed';
    $transaction_id = 'SIM' . time(); // fake transaction ID

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO client_payment (client_id, plan_type, amount, payment_method, transaction_id, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsss", $client_id, $plan_type, $amount, $payment_method, $transaction_id, $status);
    $stmt->execute();
    $stmt->close();

    // Update client_process
    $stmt2 = $conn->prepare("UPDATE client_process SET payment_done = 1 WHERE client_id = ?");
    $stmt2->bind_param("i", $client_id);
    $stmt2->execute();
    $stmt2->close();

    echo "<h1 style='text-align:center;margin-top:50px;'>Payment Successful! Welcome to Train&Gain 🎉</h1>";
    exit();
} else {
    die("Invalid request.");
}
