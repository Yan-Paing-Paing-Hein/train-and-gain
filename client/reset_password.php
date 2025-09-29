<?php
require_once("../db_connect.php");

if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

// Check token in password_resets
$stmt = $conn->prepare("SELECT pr.id, pr.client_id, pr.requested_at, pr.is_used, c.email 
                        FROM password_resets pr
                        JOIN client_registered c ON pr.client_id = c.id
                        WHERE pr.token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$reset = $result->fetch_assoc();
$stmt->close();

if (!$reset) {
    die("Invalid or expired token.");
}

if ($reset['is_used'] == 1) {
    die("This reset link has already been used.");
}

// Optional: 1-hour expiry
if (time() - strtotime($reset['requested_at']) > 3600) {
    die("This reset link has expired. Please request a new one.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <h2>Reset Your Password</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="reset_password_action.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <label for="password">New Password:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required><br><br>

        <button type="submit">Reset Password</button>
    </form>
</body>

</html>