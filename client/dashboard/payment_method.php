<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];
$plan_type = $_POST['plan_type'] ?? '';

if (!in_array($plan_type, ['Monthly', 'Six-Months', 'Yearly'])) {
    die("Invalid plan selected.");
}

// Fetch email from client_registered
$stmt = $conn->prepare("SELECT email FROM client_registered WHERE id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$client = $res->fetch_assoc();
$stmt->close();
?>

<section class="payment-method">
    <h2>Payment Method</h2>
    <form method="POST" action="save_payment.php" enctype="multipart/form-data">
        <p><strong>Contact Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
        <input type="hidden" name="plan_type" value="<?php echo htmlspecialchars($plan_type); ?>">

        <label>
            <input type="radio" name="payment_method" value="PayPal" required> PayPal
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="Venmo"> Venmo
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="CashApp"> CashApp
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="GooglePay"> Google Pay
        </label><br>
        <label>
            <input type="radio" name="payment_method" value="ApplePay"> Apple Pay
        </label><br><br>

        <label>Upload Transaction Screenshot (JPG/PNG):</label>
        <input type="file" name="screenshot" accept="image/*" required><br><br>

        <label>
            <input type="checkbox" required> I agree to Train&Gain's Terms and authorize subscription charges.
        </label><br><br>

        <button type="submit" class="cyber-button">Submit Payment</button>
    </form>
</section>