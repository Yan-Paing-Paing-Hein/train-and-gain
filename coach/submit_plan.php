<?php
// coach/submit_plan.php
require_once("../db_connect.php");
include("coach_protect.php"); // ensures coach session is active

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: client_detail.php");
    exit();
}

// Validate client_id
if (!isset($_POST['client_id']) || !is_numeric($_POST['client_id'])) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid client ID.</h1>");
}

$client_id = (int) $_POST['client_id'];
$coach_id = $_SESSION['coach_id'] ?? null;

// Ensure coach is logged in
if (!$coach_id) {
    header("Location: ../login_form.php");
    exit();
}

// Verify that this client belongs to this coach (via client_plan)
$check = $conn->prepare("
    SELECT cplan.client_id
    FROM client_plan cplan
    WHERE cplan.client_id = ? AND cplan.coach_id = ?
    LIMIT 1
");
$check->bind_param("ii", $client_id, $coach_id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>You are not allowed to modify this client's plans.</h1>");
}
$check->close();

// Begin transaction
$conn->begin_transaction();

try {
    // --- Handle Workout Plan ---
    $stmt = $conn->prepare("SELECT id FROM created_workout_plans WHERE client_id = ? AND coach_id = ? LIMIT 1");
    $stmt->bind_param("ii", $client_id, $coach_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Update existing row
        $upd = $conn->prepare("UPDATE created_workout_plans SET status = 'Planned' WHERE client_id = ? AND coach_id = ?");
        $upd->bind_param("ii", $client_id, $coach_id);
        $upd->execute();
        $upd->close();
    } else {
        // Insert new row with status = Planned
        $ins = $conn->prepare("
            INSERT INTO created_workout_plans (client_id, coach_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday, status)
            VALUES (?, ?, '', '', '', '', '', '', '', 'Planned')
        ");
        $ins->bind_param("ii", $client_id, $coach_id);
        $ins->execute();
        $ins->close();
    }
    $stmt->close();

    // --- Handle Diet Plan ---
    $stmt = $conn->prepare("SELECT id FROM created_diet_plans WHERE client_id = ? AND coach_id = ? LIMIT 1");
    $stmt->bind_param("ii", $client_id, $coach_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Update existing row
        $upd = $conn->prepare("UPDATE created_diet_plans SET status = 'Planned' WHERE client_id = ? AND coach_id = ?");
        $upd->bind_param("ii", $client_id, $coach_id);
        $upd->execute();
        $upd->close();
    } else {
        // Insert new row with status = Planned
        $ins = $conn->prepare("
            INSERT INTO created_diet_plans (client_id, coach_id, monday, tuesday, wednesday, thursday, friday, saturday, sunday, status)
            VALUES (?, ?, '', '', '', '', '', '', '', 'Planned')
        ");
        $ins->bind_param("ii", $client_id, $coach_id);
        $ins->execute();
        $ins->close();
    }
    $stmt->close();

    // Commit transaction
    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    die("<h1 style='text-align:center; margin-top:50px;'>Failed to submit plans. Please try again.</h1>");
}

// Redirect back to client detail page
header("Location: client_detail.php?id=" . urlencode($client_id));
exit();
