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

// Fetch current selected plan and coach (if any)
$stmt = $conn->prepare("
    SELECT cp.plan, c.full_name, c.profile_picture
    FROM client_plan cp
    JOIN coach c ON cp.coach_id = c.id
    WHERE cp.client_id = ?
    ORDER BY cp.selected_at DESC
    LIMIT 1
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$current_plan = $stmt->get_result()->fetch_assoc();
$stmt->close();

$plans = ['Weight Loss', 'Muscle Gain', 'Yoga', 'Strength Training', 'HIIT', 'Endurance'];
?>

<?php if ($current_plan): ?>
    <section class="current-plan">
        <h2>Your Currently Selected Plan & Coach</h2>
        <div class="coach-card">
            <img src="../../admin/coach/<?php echo htmlspecialchars($current_plan['profile_picture']); ?>" alt="Coach" width="120" height="120">
            <div>
                <h3><?php echo htmlspecialchars($current_plan['plan']); ?></h3>
                <p><strong>Coach:</strong> <?php echo htmlspecialchars($current_plan['full_name']); ?></p>
            </div>
        </div>
    </section>
<?php endif; ?>

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