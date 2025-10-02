<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Check step access
$stmt = $conn->prepare("SELECT survey_completed, plan_selected, payment_done FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$process = $res->fetch_assoc();
$stmt->close();

if (!$process || $process['survey_completed'] == 0 || $process['plan_selected'] == 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>Please complete Step 1 and Step 2 first.</h1>");
}

// Prevent re-access if payment already submitted
if ($process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>Payment already submitted. Your payment is under review.</h1>");
}
?>

<section class="payment-plan">
    <h2>Select Your Payment Plan</h2>
    <form method="POST" action="payment_method.php">
        <label>
            <input type="radio" name="plan_type" value="Monthly" required>
            $50 / month
        </label><br>
        <label>
            <input type="radio" name="plan_type" value="Six-Months">
            $240 / 6 months (20% off)
        </label><br>
        <label>
            <input type="radio" name="plan_type" value="Yearly">
            $360 / year (40% off)
        </label><br>

        <button type="submit" class="cyber-button">Continue</button>
    </form>
</section>