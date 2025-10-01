<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id       = $_SESSION['client_id'];
    $action          = $_POST['action']; // 'insert' or 'update'
    $phone           = trim($_POST['phone']);
    $gender          = $_POST['gender'];
    $dob             = $_POST['dob'];
    $height_cm       = $_POST['height_cm'];
    $weight_kg       = $_POST['weight_kg'];
    $medical_notes   = $_POST['medical_notes'] ?? null;
    $diet_pref_dropdown = $_POST['diet_preference'] ?? '';
    $diet_pref_other    = trim($_POST['diet_other'] ?? '');
    if ($diet_pref_dropdown && $diet_pref_other) {
        $diet_preference = $diet_pref_dropdown . " | " . $diet_pref_other;
    } else {
        $diet_preference = $diet_pref_dropdown ?: $diet_pref_other;
    }
    $free_time       = json_encode($_POST['free_time']);

    // File upload (to profiles folder)
    $profile_picture = null;
    if (!empty($_FILES["profile_picture"]["name"])) {
        $uploadDir = "../../client/dashboard/profiles/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]);
        $targetFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
            // Save relative path for DB
            $profile_picture = "client/dashboard/profiles/" . $fileName;
        }
    }

    if ($action === 'insert') {
        // INSERT new survey
        $stmt = $conn->prepare("INSERT INTO client_survey 
            (client_id, phone, gender, dob, height_cm, weight_kg, profile_picture, medical_notes, diet_preference, free_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssiissss", $client_id, $phone, $gender, $dob, $height_cm, $weight_kg, $profile_picture, $medical_notes, $diet_preference, $free_time);
        $stmt->execute();
        $stmt->close();

        // Make sure client_process row exists
        $check = $conn->prepare("SELECT id FROM client_process WHERE client_id = ?");
        $check->bind_param("i", $client_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows == 0) {
            // Create new row for this client
            $insert = $conn->prepare("INSERT INTO client_process (client_id, survey_completed) VALUES (?, 1)");
            $insert->bind_param("i", $client_id);
            $insert->execute();
            $insert->close();
        } else {
            // Update existing row
            $update = $conn->prepare("UPDATE client_process SET survey_completed = 1 WHERE client_id = ?");
            $update->bind_param("i", $client_id);
            $update->execute();
            $update->close();
        }
        $check->close();

        header("Location: home.php?success=Survey submitted successfully!");
        exit();
    } elseif ($action === 'update') {
        // UPDATE existing survey
        if ($profile_picture) {
            // Fetch old picture path
            $oldPicQuery = $conn->prepare("SELECT profile_picture FROM client_survey WHERE client_id = ?");
            $oldPicQuery->bind_param("i", $client_id);
            $oldPicQuery->execute();
            $oldPicResult = $oldPicQuery->get_result();
            if ($oldPicResult->num_rows > 0) {
                $oldPic = $oldPicResult->fetch_assoc()['profile_picture'];
                $oldPath = "../../" . $oldPic;
                if ($oldPic && file_exists($oldPath)) {
                    unlink($oldPath); // delete old picture
                }
            }
            $oldPicQuery->close();

            // Update with new picture
            $stmt = $conn->prepare("UPDATE client_survey 
                SET phone=?, gender=?, dob=?, height_cm=?, weight_kg=?, profile_picture=?, medical_notes=?, diet_preference=?, free_time=? 
                WHERE client_id=?");
            $stmt->bind_param("sssiissssi", $phone, $gender, $dob, $height_cm, $weight_kg, $profile_picture, $medical_notes, $diet_preference, $free_time, $client_id);
        } else {
            // Update without changing picture
            $stmt = $conn->prepare("UPDATE client_survey 
                SET phone=?, gender=?, dob=?, height_cm=?, weight_kg=?, medical_notes=?, diet_preference=?, free_time=? 
                WHERE client_id=?");
            $stmt->bind_param("sssiisssi", $phone, $gender, $dob, $height_cm, $weight_kg, $medical_notes, $diet_preference, $free_time, $client_id);
        }
        $stmt->execute();
        $stmt->close();

        // Ensure survey_completed stays 1
        $update = $conn->prepare("UPDATE client_process SET survey_completed = 1 WHERE client_id = ?");
        $update->bind_param("i", $client_id);
        $update->execute();
        $update->close();

        header("Location: home.php?success=Survey updated successfully!");
        exit();
    }
}
