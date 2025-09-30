<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}
?>

<h2>Step 1: Survey Form</h2>
<form action="survey_action.php" method="POST" enctype="multipart/form-data">
    <label>Phone Number:</label>
    <input type="text" name="phone" required><br>

    <label>Gender:</label>
    <select name="gender" required>
        <option value="">-- Select --</option>
        <option>Male</option>
        <option>Female</option>
        <option>Other</option>
    </select><br>

    <label>Date of Birth:</label>
    <input type="date" name="dob" required><br>

    <label>Height (cm):</label>
    <input type="number" name="height_cm" required><br>

    <label>Weight (kg):</label>
    <input type="number" name="weight_kg" required><br>

    <label>Profile Picture:</label>
    <input type="file" name="profile_picture" accept="image/*" required><br>

    <label>Medical Notes / Restrictions:</label>
    <textarea name="medical_notes"></textarea><br>

    <label>Diet Preference:</label>
    <select name="diet_preference">
        <option value="None">None</option>
        <option value="Vegetarian">Vegetarian</option>
        <option value="Vegan">Vegan</option>
        <option value="Keto">Keto</option>
        <option value="Halal">Halal</option>
        <option value="Other">Other</option>
    </select>
    <input type="text" name="diet_other" placeholder="If Other, specify"><br>

    <h3>Weekly Free Time (hours per day)</h3>
    <?php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($days as $day) {
        echo "<label>$day:</label> <input type='number' name='free_time[$day]' min='0' max='24' value='0'><br>";
    }
    ?>

    <button type="submit">Submit Survey</button>
</form>