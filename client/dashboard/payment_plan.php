<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Check step access
$stmt = $conn->prepare("SELECT survey_completed, plan_selected, payment_done FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$process = $res->fetch_assoc();
$stmt->close();

if (!$process || $process['survey_completed'] == 0 || $process['plan_selected'] == 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>Please complete Step 1 and Step 2 first.</h1>");
}

// Prevent re-access if payment already submitted
if ($process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>Payment already submitted. Your payment is under review.</h1>");
}
?>



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
                <h2 class="section-title3">Select Your Payment Plan</h2>
                <p class="section-subtitle">Get Started with Train&Gain</p>
            </div>



            <form method="POST" action="payment_method.php">
                <label>
                    <input type="radio" name="plan_type" value="Monthly" required>
                    $50 / month
                </label><br>
                <label>
                    <input type="radio" name="plan_type" value="Six-Months">
                    $240 / 6 months (20% off)
                </label><br>
                <label>
                    <input type="radio" name="plan_type" value="Yearly">
                    $360 / year (40% off)
                </label><br>

                <button type="submit" class="cyber-button">Continue</button>
            </form>



            <div class="pricing-grid">
                <div class="pricing-card">
                    <div class="plan-name">Initiate</div>
                    <div class="plan-price">$49</div>
                    <div class="plan-period">per neural link</div>
                    <!-- <ul class="plan-features">
                        <li>Basic quantum processing</li>
                        <li>5 holographic workspaces</li>
                        <li>Standard encryption</li>
                        <li>Community support matrix</li>
                        <li>Reality sync enabled</li>
                    </ul> -->
                    <a href="#" class="btn-secondary">Enter System</a>
                </div>

                <div class="pricing-card featured">
                    <div class="plan-name">Nexus</div>
                    <div class="plan-price">$149</div>
                    <div class="plan-period">per neural link</div>
                    <!-- <ul class="plan-features">
                        <li>Advanced quantum algorithms</li>
                        <li>Unlimited holo-workspaces</li>
                        <li>Quantum encryption fortress</li>
                        <li>Priority neural support</li>
                        <li>Mind-reading analytics</li>
                        <li>Hyperdrive sync protocol</li>
                    </ul> -->
                    <a href="#" class="btn-primary">Jack In</a>
                </div>

                <div class="pricing-card">
                    <div class="plan-name">Transcend</div>
                    <div class="plan-price">$399</div>
                    <div class="plan-period">per neural link</div>
                    <!-- <ul class="plan-features">
                        <li>Infinite processing power</li>
                        <li>Custom reality matrices</li>
                        <li>Temporal encryption layers</li>
                        <li>Direct neural interface</li>
                        <li>Predictive consciousness</li>
                        <li>Quantum entanglement sync</li>
                    </ul> -->
                    <a href="#" class="btn-secondary">Ascend</a>
                </div>
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