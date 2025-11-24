<?php
require_once("../db_connect.php");

if (!isset($_GET['token'])) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid request!</h1>");
}

$token = $_GET['token'];

// Check token
$stmt = $conn->prepare("
    SELECT pr.id, pr.coach_id, pr.is_used, pr.requested_at, c.email
    FROM coach_password_resets pr
    JOIN coach c ON pr.coach_id = c.id
    WHERE pr.token = ?
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$reset = $result->fetch_assoc();
$stmt->close();

if (!$reset) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid or expired token!</h1>");
}

if ($reset['is_used'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>This reset link has already been used!</h1>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Reset Password</title>
    <link href="../css/templatemo-nexus-style.css" rel="stylesheet">
</head>

<body id="top">

    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title">Reset Your Password</h2>
            </div>

            <div class="contact-form-wrapper">

                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <form class="contact-form" action="reset_password_action.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div class="form-group">
                        <label for="password">New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" required>
                            <button type="button" id="togglePassword" class="toggle-password">Show</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" required>
                            <button type="button" id="toggleConfirmPassword" class="toggle-password">Show</button>
                        </div>
                    </div>

                    <button type="submit" class="btn-create btn-upload">Reset Password</button>
                </form>

            </div>
        </div>
    </section>

    <script>
        const p = document.getElementById("password");
        const b = document.getElementById("togglePassword");
        b.onclick = () => {
            let isPass = p.type === "password";
            p.type = isPass ? "text" : "password";
            b.textContent = isPass ? "Hide" : "Show";
        };

        const cp = document.getElementById("confirm_password");
        const cb = document.getElementById("toggleConfirmPassword");
        cb.onclick = () => {
            let isPass = cp.type === "password";
            cp.type = isPass ? "text" : "password";
            cb.textContent = isPass ? "Hide" : "Show";
        };
    </script>

</body>

</html>