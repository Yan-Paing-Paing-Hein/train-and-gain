<?php
// Protect admin access
require_once "../admin_protect.php";

include '../../db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p style='color:red;text-align:center;'>Invalid coach ID.</p>");
}

$id = intval($_GET['id']);

// Get profile path first
$stmt = $conn->prepare("SELECT profile_picture FROM coach WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$coach = $result->fetch_assoc();
$stmt->close();

if ($coach) {
    // Delete profile if exists
    if (!empty($coach['profile_picture']) && file_exists($coach['profile_picture'])) {
        unlink($coach['profile_picture']);
    }

    // Delete database row
    $stmt = $conn->prepare("DELETE FROM coach WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;text-align:center;'>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
} else {
    echo "<p style='color:red;text-align:center;'>Coach not found.</p>";
}
