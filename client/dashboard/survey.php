<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];
$survey = null;

$stmt = $conn->prepare("SELECT * FROM client_survey WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $survey = $result->fetch_assoc();
}
$stmt->close();

?>


<h2>Step 1: Survey Form</h2>
<form action="survey_action.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo $survey ? 'update' : 'insert'; ?>">

    <label>Phone Number:</label>
    <input type="text" name="phone" value="<?php echo $survey['phone'] ?? ''; ?>" required><br>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="">-- Select --</option>
        <option value="Male" <?php if (($survey['gender'] ?? '') === 'Male') echo 'selected'; ?>>Male</option>
        <option value="Female" <?php if (($survey['gender'] ?? '') === 'Female') echo 'selected'; ?>>Female</option>
        <option value="Other" <?php if (($survey['gender'] ?? '') === 'Other') echo 'selected'; ?>>Other</option>
    </select><br>

    <label>Date of Birth:</label>
    <input type="date" name="dob" value="<?php echo $survey['dob'] ?? ''; ?>" required><br>

    <label>Height (cm):</label>
    <input type="number" name="height_cm" value="<?php echo $survey['height_cm'] ?? ''; ?>" required><br>

    <label>Weight (kg):</label>
    <input type="number" name="weight_kg" value="<?php echo $survey['weight_kg'] ?? ''; ?>" required><br>

    <label>Profile Picture:</label>
    <?php if (!empty($survey['profile_picture'])): ?>
        <img src="../../<?php echo htmlspecialchars($survey['profile_picture']); ?>" alt="Profile" width="100"><br>
        <small>Upload new to replace:</small><br>
    <?php endif; ?>
    <input type="file" name="profile_picture" accept="image/*" <?php echo $survey ? '' : 'required'; ?>><br>

    <label>Medical Notes / Restrictions:</label>
    <textarea name="medical_notes"><?php echo $survey['medical_notes'] ?? ''; ?></textarea><br>

    <label>Diet Preference:</label>
    <select name="diet_preference">
        <?php
        $prefs = ['None', 'Vegetarian', 'Vegan', 'Keto', 'Halal', 'Other'];
        foreach ($prefs as $p) {
            $selected = ($survey['diet_preference'] ?? '') === $p ? 'selected' : '';
            echo "<option value='$p' $selected>$p</option>";
        }
        ?>
    </select>
    <input type="text" name="diet_other" placeholder="If Other, specify"
        value="<?php echo (!in_array($survey['diet_preference'] ?? '', $prefs) ? htmlspecialchars($survey['diet_preference']) : ''); ?>"><br>

    <h3>Weekly Free Time (hours per day)</h3>
    <?php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $freeTimeData = $survey ? json_decode($survey['free_time'], true) : [];
    foreach ($days as $day) {
        $val = $freeTimeData[$day] ?? 0;
        echo "<label>$day:</label> <input type='number' name='free_time[$day]' min='0' max='24' value='$val'><br>";
    }
    ?>

    <button type="submit"><?php echo $survey ? 'Update Survey' : 'Submit Survey'; ?></button>
</form>