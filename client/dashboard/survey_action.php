<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id      = $_SESSION['client_id'];
    $phone          = trim($_POST['phone']);
    $gender         = $_POST['gender'];
    $dob            = $_POST['dob'];
    $height_cm      = $_POST['height_cm'];
    $weight_kg      = $_POST['weight_kg'];
    $medical_notes  = $_POST['medical_notes'] ?? null;
    $diet_preference = $_POST['diet_other'] ?: $_POST['diet_preference'];
    $free_time      = json_encode($_POST['free_time']);

    // Handle file upload
    $uploadDir = "../../uploads/profile_pictures/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
        $profile_picture = "uploads/profile_pictures/" . $fileName; // save relative path
    } else {
        die("Error uploading file");
    }

    // Insert into client_survey
    $stmt = $conn->prepare("INSERT INTO client_survey 
        (client_id, phone, gender, dob, height_cm, weight_kg, profile_picture, medical_notes, diet_preference, free_time) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssiissss", $client_id, $phone, $gender, $dob, $height_cm, $weight_kg, $profile_picture, $medical_notes, $diet_preference, $free_time);

    if ($stmt->execute()) {
        // Update process table
        $update = $conn->prepare("UPDATE client_process SET survey_completed = 1 WHERE client_id = ?");
        $update->bind_param("i", $client_id);
        $update->execute();

        header("Location: home.php?success=Survey submitted successfully!");
        exit();
    } else {
        die("Database error: " . $stmt->error);
    }
}
