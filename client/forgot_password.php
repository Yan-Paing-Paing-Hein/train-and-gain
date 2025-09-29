<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>

<body>
    <h2>Forgot Password</h2>

    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php elseif (isset($_GET['success'])): ?>
        <p style="color:green;"><?php echo htmlspecialchars($_GET['success']); ?></p>
    <?php endif; ?>

    <form action="forgot_password_action.php" method="POST">
        <label for="email">Enter your registered email:</label><br>
        <input type="email" name="email" id="email" required><br><br>
        <button type="submit">Send Reset Request</button>
    </form>

    <p><a href="../client/login_form.php">Back to Login</a></p>
</body>

</html>