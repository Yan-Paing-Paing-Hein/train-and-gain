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
if (!$process || $process['survey_completed'] == 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>Please complete Step 1 first.</h1>");
}


$stmt = $conn->prepare("SELECT survey_completed FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
if (!$row || $row['survey_completed'] == 0) {
    die("<h1 style='text-align:center;margin-top:50px;'>Please complete Step 1 first.</h1>");
}
$stmt->close();

$plans = ['Weight Loss', 'Muscle Gain', 'Yoga', 'Strength Training', 'HIIT', 'Endurance'];
?>

<section class="plans-section">
    <h2>Select Your Workout Plan</h2>
    <div class="plans-grid">
        <?php foreach ($plans as $plan): ?>
            <div class="plan-card">
                <h3><?php echo $plan; ?></h3>
                <a href="choose_coach.php?plan=<?php echo urlencode($plan); ?>" class="cyber-button">Choose Plan</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>