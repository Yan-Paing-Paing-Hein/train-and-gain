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

// Verify that this client belongs to this coach (via client_plan or other)
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

// Update both tables to set status = 'Planned' for that client and coach
// Use transactions (optional if your DB supports InnoDB)
$conn->begin_transaction();

try {
    $upd1 = $conn->prepare("UPDATE created_workout_plans SET status = 'Planned' WHERE client_id = ? AND coach_id = ?");
    $upd1->bind_param("ii", $client_id, $coach_id);
    $upd1->execute();
    $upd1->close();

    $upd2 = $conn->prepare("UPDATE created_diet_plans SET status = 'Planned' WHERE client_id = ? AND coach_id = ?");
    $upd2->bind_param("ii", $client_id, $coach_id);
    $upd2->execute();
    $upd2->close();

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    die("<h1 style='text-align:center; margin-top:50px;'>Failed to submit plans. Please try again.</h1>");
}

// Redirect back to client detail page
header("Location: client_detail.php?id=" . urlencode($client_id));
exit();
