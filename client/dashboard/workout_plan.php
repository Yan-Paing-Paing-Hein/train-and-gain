<?php
require_once("../../db_connect.php");

session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// --- NEW: Validate GET id and ownership ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid client ID.</h1>");
}

$requested_id = intval($_GET['id']);

if ($requested_id !== $client_id) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid client ID.</h1>");
}

// Verify that this client’s latest payment is approved
$check_status = $conn->prepare("
    SELECT status 
    FROM client_payment 
    WHERE client_id = ? 
    ORDER BY id DESC 
    LIMIT 1
");
$check_status->bind_param("i", $client_id);
$check_status->execute();
$result_status = $check_status->get_result();
$payment_status = $result_status->fetch_assoc();
$check_status->close();

// If not approved, redirect back to welcome.php
if (!$payment_status || $payment_status['status'] !== 'Approved') {
    header("Location: welcome.php");
    exit();
}

// Fetch workout plan status
$workout_sql = "SELECT status FROM created_workout_plans WHERE client_id = ? LIMIT 1";
$stmt1 = $conn->prepare($workout_sql);
$stmt1->bind_param("i", $client_id);
$stmt1->execute();
$workout_result = $stmt1->get_result();
$workout = $workout_result->fetch_assoc();
$stmt1->close();

// Fetch diet plan status
$diet_sql = "SELECT status FROM created_diet_plans WHERE client_id = ? LIMIT 1";
$stmt2 = $conn->prepare($diet_sql);
$stmt2->bind_param("i", $client_id);
$stmt2->execute();
$diet_result = $stmt2->get_result();
$diet = $diet_result->fetch_assoc();
$stmt2->close();

// Treat missing rows as Not Planned
$workout_status = $workout['status'] ?? 'Not Planned';
$diet_status    = $diet['status'] ?? 'Not Planned';

// If BOTH are not 'Planned' → block access
if (!($workout_status === 'Planned' && $diet_status === 'Planned')) {
    header("Location: home.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Detail</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        .client-plan-container {
            background: var(--card-bg);
            border: 3px solid #f900e0;
            padding: 2rem;
            margin: 2rem auto;
            max-width: 80%;
            backdrop-filter: blur(10px);
            clip-path: polygon(30px 0%, 100% 0%, 100% calc(100% - 30px), calc(100% - 30px) 100%, 0% 100%, 0% 30px);
            box-shadow: 0 0 30px rgba(249, 0, 224, 0.7);
            overflow-x: auto;
        }
    </style>
</head>

<body id="top">
    <!-- Enhanced Background Elements -->
    <div class="cyber-bg">
        <div class="cyber-gradient"></div>
        <div class="matrix-rain" id="matrixRain"></div>
    </div>

    <div class="particles" id="particlesContainer"></div>

    <div class="data-streams" id="dataStreams"></div>

    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

    <div class="grid-overlay">
        <div class="grid-lines"></div>
        <div class="grid-glow"></div>
    </div>

    <div class="scanlines"></div>
    <div class="noise-overlay"></div>

    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <a href="#top" class="logo">Train & Gain</a>
            <ul class="nav-links">
                <li><a href="../../client/blogpost.php">BlogPost</a></li>
                <li><a href="../../client/coach.php">Coach</a></li>
            </ul>
            <div class="nav-bottom">
                <a href="../dashboard/home.php" class="cyber-button">Dashboard</a>
                <a href="../../client/logout.php" class="cyber-button">Log out</a>
            </div>
            <button class="mobile-menu-button" id="mobileMenuBtn">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>
    </nav>



    <section class="contact fade-up" id="contact">

        <div class="client-plan-container">

            <h2 class="client-detail-title">Current Workout Plans for Client ID.<?php echo htmlspecialchars($client['client_id']); ?></h2>

            <table class="client-detail-table">
                <tbody>

                    <tr>
                        <th>For Monday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['monday'] ?? 'No plan yet')); ?></td>
                    </tr>

                    <tr>
                        <th>For Tuesday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['tuesday'] ?? 'No plan yet')); ?></td>
                    </tr>

                    <tr>
                        <th>For Wednesday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['wednesday'] ?? 'No plan yet')); ?></td>
                    </tr>

                    <tr>
                        <th>For Thursday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['thursday'] ?? 'No plan yet')); ?></td>
                    </tr>

                    <tr>
                        <th>For Friday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['friday'] ?? 'No plan yet')); ?></td>
                    </tr>

                    <tr>
                        <th>For Saturday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['saturday'] ?? 'No plan yet')); ?></td>
                    </tr>

                    <tr>
                        <th>For Sunday</th>
                        <td><?php echo nl2br(htmlspecialchars($workout_plan['sunday'] ?? 'No plan yet')); ?></td>
                    </tr>

                </tbody>
            </table>

        </div>

    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <span class="footer-separator">•</span>
                <a href="#">Terms of Service</a>
                <span class="footer-separator">•</span>
                <a href="#">Documentation</a>
                <span class="footer-separator">•</span>
                <a href="#">Contact Support</a>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 NexusFlow Systems. All realities reserved.</p>
                <p class="footer-credit">Brought to you by <a href="https://templatemo.com" target="_blank"
                        rel="noopener nofollow">TemplateMo</a></p>
            </div>
        </div>
    </footer>

    <script src="../../js/templatemo-nexus-scripts.js"></script>

    <script>
        document.querySelectorAll(".client-detail-table tr").forEach((row, i) => {
            row.style.opacity = 0;
            row.style.transform = "translateX(-20px)";
            setTimeout(() => {
                row.style.transition = "all 0.6s ease";
                row.style.opacity = 1;
                row.style.transform = "translateX(0)";
            }, i * 200);
        });
    </script>

</body>

</html>