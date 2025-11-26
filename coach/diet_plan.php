<?php
include("coach_protect.php");
require_once("../db_connect.php");

// Validate client ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid client ID.</h1>");
}

$client_id = (int)$_GET['id'];
$coach_id = $_SESSION['coach_id'];

// Ensure this client belongs to this coach AND payment approved
$query = $conn->prepare("
    SELECT 
        cr.id AS client_id,
        cr.name,
        cr.email,
        cs.phone,
        cs.gender,
        cs.dob,
        cs.height_cm,
        cs.weight_kg,
        cs.profile_picture,
        cs.medical_notes,
        cs.diet_preference,
        cs.free_time,
        cp.status AS payment_status
    FROM client_registered cr
    JOIN client_survey cs ON cr.id = cs.client_id
    JOIN client_payment cp ON cp.client_id = cr.id
    JOIN client_plan cplan ON cplan.client_id = cr.id
    WHERE cr.id = ?
      AND cplan.coach_id = ?
      AND cp.status = 'Approved'
    LIMIT 1
");

$query->bind_param("ii", $client_id, $coach_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>You are not allowed to view this client's details or the payment is not approved.</h1>");
}
