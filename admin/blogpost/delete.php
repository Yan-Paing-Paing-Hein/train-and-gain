<?php
// Protect admin access
require_once "../admin_protect.php";

include '../../db_connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p style='color:red;text-align:center;'>Invalid blogpost ID.</p>");
}

$id = intval($_GET['id']);

// Get image path first
$stmt = $conn->prepare("SELECT blog_image FROM blogpost WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blog = $result->fetch_assoc();
$stmt->close();

if ($blog) {
    // Delete image if exists
    if (!empty($blog['blog_image']) && file_exists($blog['blog_image'])) {
        unlink($blog['blog_image']);
    }

    // Delete database row
    $stmt = $conn->prepare("DELETE FROM blogpost WHERE id=?");
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
    echo "<p style='color:red;text-align:center;'>Blogpost not found.</p>";
}
