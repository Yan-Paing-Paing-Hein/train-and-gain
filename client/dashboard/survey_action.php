<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id      = $_SESSION['client_id'];
    $action         = $_POST['action']; // 'insert' or 'update'
    $phone          = trim($_POST['phone']);
    $gender         = $_POST['gender'];
    $dob            = $_POST['dob'];
    $height_cm      = $_POST['height_cm'];
    $weight_kg      = $_POST['weight_kg'];
    $medical_notes  = $_POST['medical_notes'] ?? null;
    $diet_preference = $_POST['diet_other'] ?: $_POST['diet_preference'];
    $free_time      = json_encode($_POST['free_time']);

    // File upload
    $profile_picture = null;
    if (!empty($_FILES["profile_picture"]["name"])) {
        $uploadDir = "../../uploads/profile_pictures/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            $profile_picture = "uploads/profile_pictures/" . $fileName;
        }
    }

    if ($action === 'insert') {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO client_survey 
            (client_id, phone, gender, dob, height_cm, weight_kg, profile_picture, medical_notes, diet_preference, free_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssiissss", $client_id, $phone, $gender, $dob, $height_cm, $weight_kg, $profile_picture, $medical_notes, $diet_preference, $free_time);
        $stmt->execute();
        $stmt->close();

        // Mark survey as completed
        $update = $conn->prepare("UPDATE client_process SET survey_completed = 1 WHERE client_id = ?");
        $update->bind_param("i", $client_id);
        $update->execute();
        $update->close();

        header("Location: home.php?success=Survey submitted successfully!");
        exit();
    } elseif ($action === 'update') {
        // UPDATE existing row
        if ($profile_picture) {
            $stmt = $conn->prepare("UPDATE client_survey 
                SET phone=?, gender=?, dob=?, height_cm=?, weight_kg=?, profile_picture=?, medical_notes=?, diet_preference=?, free_time=? 
                WHERE client_id=?");
            $stmt->bind_param("sssiissssi", $phone, $gender, $dob, $height_cm, $weight_kg, $profile_picture, $medical_notes, $diet_preference, $free_time, $client_id);
        } else {
            // keep old picture
            $stmt = $conn->prepare("UPDATE client_survey 
                SET phone=?, gender=?, dob=?, height_cm=?, weight_kg=?, medical_notes=?, diet_preference=?, free_time=? 
                WHERE client_id=?");
            $stmt->bind_param("sssiisssi", $phone, $gender, $dob, $height_cm, $weight_kg, $medical_notes, $diet_preference, $free_time, $client_id);
        }
        $stmt->execute();
        $stmt->close();

        header("Location: home.php?success=Survey updated successfully!");
        exit();
    }
}
