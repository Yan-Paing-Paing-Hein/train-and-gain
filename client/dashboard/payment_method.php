<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}
$client_id = $_SESSION['client_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['plan_type'])) {
    die("Invalid request.");
}

$plan_type = $_POST['plan_type'];

// Fetch client email
$stmt = $conn->prepare("SELECT email FROM client_registered WHERE id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$email = $stmt->get_result()->fetch_assoc()['email'];
$stmt->close();
?>

<section class="payment-method-section">
    <h2>Payment Method</h2>
    <form method="POST" action="save_payment.php">
        <input type="hidden" name="plan_type" value="<?php echo htmlspecialchars($plan_type); ?>">

        <div class="contact-info">
            <h3>Contact Information</h3>
            <p>Email: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        </div>

        <div class="payment-methods">
            <h3>Card Information</h3>
            <label>Card Number</label>
            <input type="text" name="card_number" placeholder="1234 1234 1234 1234" required>

            <label>Expiration (MM/YY)</label>
            <input type="text" name="expiry" placeholder="MM/YY" required>

            <label>CVC</label>
            <input type="text" name="cvc" placeholder="CVC" required>

            <label>Payment Method</label>
            <select name="payment_method" required>
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="JCB">JCB</option>
                <option value="UnionPay">UnionPay</option>
            </select>
        </div>

        <div class="agreement">
            <label>
                <input type="checkbox" name="agree" required>
                By subscribing, you agree to Train&Gain's Terms of Use and Privacy Policy.
            </label>
        </div>

        <button type="submit" class="cyber-button">Make Payment</button>
    </form>
</section>