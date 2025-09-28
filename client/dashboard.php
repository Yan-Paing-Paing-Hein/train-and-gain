<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: login_form.php?error=Please log in first.");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['client_name']); ?>!</h1>
</body>

</html>