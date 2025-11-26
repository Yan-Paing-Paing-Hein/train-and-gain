<?php
require_once("../../db_connect.php");

session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

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

// Fetch diet plan status
$diet_sql = "SELECT status FROM created_diet_plans WHERE client_id = ? LIMIT 1";
$stmt2 = $conn->prepare($diet_sql);
$stmt2->bind_param("i", $client_id);
$stmt2->execute();
$diet_result = $stmt2->get_result();
$diet = $diet_result->fetch_assoc();

// If rows do not exist yet → treat as Not Planned
$workout_status = $workout['status'] ?? 'Not Planned';
$diet_status    = $diet['status'] ?? 'Not Planned';

// Show message unless BOTH workout + diet are "Planned"
$show_message = !($workout_status === 'Planned' && $diet_status === 'Planned');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        .plan-wait-box {
            border: 2px solid #f900e0;
            color: #00ffff;
            padding: 20px;
            margin-top: 25px;
            border-radius: 12px;
            text-align: center;
            font-size: 1.1rem;
            width: 80%;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            background: rgba(0, 0, 0, 0.3);
            box-shadow: 0 0 15px #f900e0;
        }

        .client-category-container {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 40px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .client-category-box {
            flex: 1 1 300px;
            max-width: 400px;
            padding: 30px 20px;
            border: 2px solid #f900e0;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
            clip-path: polygon(20px 0%, 100% 0%, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0% 100%, 0% 20px);
            box-shadow: 0 0 15px rgba(249, 0, 224, 0.6);
            background: rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .client-category-box:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px rgba(249, 0, 224, 0.8);
        }

        .client-category-box h3 {
            color: #f900e0;
            font-size: 1.6rem;
            margin-bottom: 10px;
            text-shadow: 0 0 8px #f900e0;
        }

        .client-category-box p {
            color: #00ffff;
            font-size: 1rem;
            margin-bottom: 20px;
            text-shadow: 0 0 8px #00ffff;
        }
    </style>
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

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
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
    </div>



    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title3">Welcome, <?php echo htmlspecialchars($_SESSION['client_name']); ?>!</h2>
                <p class="section-subtitle">Let's train with us together!</p>
            </div>
        </div>


        <?php if ($show_message): ?>
            <div class="plan-wait-box fade-up">
                <p>Please wait for your coach to finish your workout and diet plans. Once it is done, they will be revealed here.</p>
            </div>
        <?php endif; ?>


        <div class="client-category-container">
            <!-- Create Workout Plan -->
            <div class="client-category-box">
                <h3>Your Workout Plans</h3>
                <p>Your coach has already created workout plans for your weekend days.</p>
                <a href="workout_plan.php?id=<?php echo $client_id; ?>" class="btn-view">View</a>
            </div>

            <!-- Create Diet Plan -->
            <div class="client-category-box">
                <h3>Your Diet Plans</h3>
                <p>Your coach has already created diet plans for your weekend days.</p>
                <a href="diet_plan.php?id=<?php echo $client_id; ?>" class="btn-view">View</a>
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


</body>

</html>

<!-- http://localhost/train&gain/client/dashboard/home.php -->