<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}
$client_id = $_SESSION['client_id'];

// Check process
$stmt = $conn->prepare("SELECT survey_completed, plan_selected, payment_done FROM client_process WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$process = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$process || $process['survey_completed'] == 0 || $process['plan_selected'] == 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>Please complete Step 1 and 2 first.</h1>");
}

if ($process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>You have already completed payment.</h1>");
}
?>

<section class="plans-section">
    <h2>Choose Your Subscription Plan</h2>
    <div class="plans-grid">
        <form method="POST" action="payment_method.php">
            <label class="plan-card">
                <input type="radio" name="plan_type" value="Monthly" required>
                <h3>Monthly Plan</h3>
                <p>$50 / month</p>
            </label>
            <label class="plan-card">
                <input type="radio" name="plan_type" value="Six-Month">
                <h3>6 Months Plan</h3>
                <p>$240 / 6 months (Save 20%)</p>
            </label>
            <label class="plan-card">
                <input type="radio" name="plan_type" value="Yearly">
                <h3>Yearly Plan</h3>
                <p>$360 / year (Save 40%)</p>
            </label>
            <button type="submit" class="cyber-button">Continue</button>
        </form>
    </div>
</section>