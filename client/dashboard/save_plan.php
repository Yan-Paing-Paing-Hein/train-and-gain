<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Fetch process info first
$stmt = $conn->prepare("SELECT payment_done FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$process = $res->fetch_assoc();
$stmt->close();

if ($process && $process['payment_done'] == 1) {
    // Restrict after payment
    header("Location: welcome.php?error=You've already finished final step 3 and made payment. Cannot change options anymore.");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan = $_POST['plan'];
    $coach_id = $_POST['coach_id'];

    // Check if client already has a plan
    $check = $conn->prepare("SELECT id FROM client_plan WHERE client_id = ?");
    $check->bind_param("i", $client_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Update existing choice
        $update = $conn->prepare("UPDATE client_plan SET plan=?, coach_id=?, selected_at=NOW() WHERE client_id=?");
        $update->bind_param("sii", $plan, $coach_id, $client_id);
        $update->execute();
        $update->close();
    } else {
        // Insert new
        $insert = $conn->prepare("INSERT INTO client_plan (client_id, plan, coach_id) VALUES (?, ?, ?)");
        $insert->bind_param("isi", $client_id, $plan, $coach_id);
        $insert->execute();
        $insert->close();
    }
    $check->close();

    // Update process tracker
    $updateProcess = $conn->prepare("UPDATE client_process SET plan_selected = 1 WHERE client_id=?");
    $updateProcess->bind_param("i", $client_id);
    $updateProcess->execute();
    $updateProcess->close();

    header("Location: welcome.php?success=Plan and coach selected successfully!");
    exit();
}
