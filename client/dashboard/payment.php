<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Fetch process status
$stmt = $conn->prepare("SELECT survey_completed, plan_selected, payment_done FROM client_process WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$process = $result->fetch_assoc();
$stmt->close();

// Restrict access
if (!$process || $process['plan_selected'] == 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>Please complete Step 1 and 2 first.</h1>");
}



// If already paid
if ($process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>You have already completed payment.</h1>");
}
?>

<section class="payment-section">
    <h2>Choose Your Payment Plan</h2>

    <form method="POST" action="save_payment.php" class="payment-form">
        <div class="plan-options">
            <label>
                <input type="radio" name="plan_type" value="Monthly" required>
                Monthly Plan - $50 / month
            </label>
            <label>
                <input type="radio" name="plan_type" value="Yearly">
                Yearly Plan - $480 / year (Save 20%)
            </label>
        </div>

        <h3>Select Payment Method</h3>
        <div class="payment-methods">
            <label>
                <input type="radio" name="payment_method" value="Credit Card" required>
                Credit Card
            </label>
            <label>
                <input type="radio" name="payment_method" value="Debit Card">
                Debit Card
            </label>
            <label>
                <input type="radio" name="payment_method" value="PayPal">
                PayPal
            </label>
            <label>
                <input type="radio" name="payment_method" value="Stripe">
                Stripe
            </label>
        </div>

        <button type="submit" class="cyber-button">Proceed to Pay</button>
    </form>
</section>