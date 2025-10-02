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

// Restrict if payment is already done
if ($process && $process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>You've already finished final step 3 and made payment. Cannot change options anymore.</h1>");
}

// Then check for plan param
if (!isset($_GET['plan'])) {
    die("<h1 style='text-align:center;margin-top:50px;'>No plan selected.</h1>");
}
$plan = $_GET['plan'];

// Fetch available coaches for this plan (specialty)
$stmt = $conn->prepare("SELECT * FROM coach WHERE specialty = ? AND status = 'Active'");
$stmt->bind_param("s", $plan);
$stmt->execute();
$coaches = $stmt->get_result();
?>

<section class="coach-section">
    <h2>Available Coaches for <?php echo htmlspecialchars($plan); ?></h2>
    <div class="coach-grid">
        <?php while ($coach = $coaches->fetch_assoc()): ?>
            <div class="coach-card">
                <img src="../../admin/coach/<?php echo htmlspecialchars($coach['profile_picture']); ?>" alt="Coach">
                <h3><?php echo htmlspecialchars($coach['full_name']); ?></h3>
                <p><?php echo htmlspecialchars($coach['about']); ?></p>
                <form method="POST" action="save_plan.php">
                    <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan); ?>">
                    <input type="hidden" name="coach_id" value="<?php echo $coach['id']; ?>">
                    <button type="submit" class="cyber-button">Choose This Coach</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>