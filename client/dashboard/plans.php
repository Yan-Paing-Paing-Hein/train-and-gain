<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Fetch process status
$stmt = $conn->prepare("SELECT survey_completed, plan_selected, payment_done FROM client_process WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$process = $result->fetch_assoc();
$stmt->close();

// Restrict access
if (!$process || $process['survey_completed'] == 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>Please complete Step 1 first.</h1>");
}

// NEW restriction: block if payment already submitted
if ($process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>You've already finished final step 3 and made payment. Cannot change options anymore.</h1>");
}

// Fetch current selected plan and coach (if any)
$stmt = $conn->prepare("
    SELECT cp.plan, c.full_name, c.profile_picture
    FROM client_plan cp
    JOIN coach c ON cp.coach_id = c.id
    WHERE cp.client_id = ?
    ORDER BY cp.selected_at DESC
    LIMIT 1
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$current_plan = $stmt->get_result()->fetch_assoc();
$stmt->close();

$plans = ['Weight Loss', 'Muscle Gain', 'Yoga', 'Strength Training', 'HIIT', 'Endurance'];
?>

<?php if ($current_plan): ?>
    <section class="current-plan">
        <h2>Your Currently Selected Plan & Coach</h2>
        <div class="coach-card">
            <img src="../../admin/coach/<?php echo htmlspecialchars($current_plan['profile_picture']); ?>" alt="Coach" width="120" height="120">
            <div>
                <h3><?php echo htmlspecialchars($current_plan['plan']); ?></h3>
                <p><strong>Coach:</strong> <?php echo htmlspecialchars($current_plan['full_name']); ?></p>
            </div>
        </div>
    </section>
<?php endif; ?>













<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <!--

TemplateMo 594 nexus flow

https://templatemo.com/tm-594-nexus-flow

-->
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
                <a href="../dashboard/welcome.php" class="cyber-button">Dashboard</a>
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

    <!-- Mobile Menu -->
    <!-- <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <a href="#top" class="mobile-menu-logo">Train & Gain</a>
            <button class="mobile-menu-close" id="mobileMenuClose">✕</button>
        </div>
        <div class="mobile-menu-cta">
            <a href="#" class="cyber-button">Blogpost Table</a>
        </div>
        <nav class="mobile-menu-nav">
            <ul>
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../client/index.php">Client</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
            </ul>
        </nav>
    </div> -->



    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title3">Select Your Workout Plan</h2>
                <p class="section-subtitle">Get Started with Train&Gain</p>
            </div>

            <div class="plans-grid">
                <?php foreach ($plans as $plan): ?>
                    <div class="plan-card">
                        <h3><?php echo $plan; ?></h3>
                        <a href="choose_coach.php?plan=<?php echo urlencode($plan); ?>" class="cyber-button">Choose Plan</a>
                    </div>
                <?php endforeach; ?>
            </div>

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
        document.addEventListener("DOMContentLoaded", function() {
            const title = document.querySelector(".section-title3");
            const text = title.textContent.trim();
            title.textContent = ""; // clear original text

            text.split("").forEach((char, i) => {
                const span = document.createElement("span");
                span.textContent = char === " " ? "\u00A0" : char; // preserve spaces
                span.style.setProperty("--delay", `${i * 0.05}s`);
                title.appendChild(span);
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const cards = document.querySelectorAll(".feature-card2");
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add("animate");
                    setTimeout(() => card.classList.remove("animate"), 1000); // remove after animation ends
                }, index * 300); // stagger
            });
        });
    </script>

</body>

</html>