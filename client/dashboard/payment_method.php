<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];
$plan_type = $_POST['plan_type'] ?? '';

// Check if payment already done
$stmt = $conn->prepare("SELECT payment_done FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$process = $res->fetch_assoc();
$stmt->close();

if ($process && $process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>Payment already submitted. Your payment is under review.</h1>");
}

if (!in_array($plan_type, ['Monthly', 'Six-Months', 'Yearly'])) {
    die("Invalid plan selected.");
}

// Fetch email from client_registered
$stmt = $conn->prepare("SELECT email FROM client_registered WHERE id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$client = $res->fetch_assoc();
$stmt->close();
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
                <h2 class="section-title3">Select Your Payment Method</h2>
                <p class="section-subtitle">Get Started with Train&Gain</p>
            </div>

            <form method="POST" action="save_payment.php" enctype="multipart/form-data">
                <p><strong>Contact Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
                <input type="hidden" name="plan_type" value="<?php echo htmlspecialchars($plan_type); ?>">

                <label>
                    <input type="radio" name="payment_method" value="PayPal" required> PayPal
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="Venmo"> Venmo
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="CashApp"> CashApp
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="GooglePay"> Google Pay
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="ApplePay"> Apple Pay
                </label><br><br>

                <label>Upload Transaction Screenshot (JPG/PNG):</label>
                <input type="file" name="screenshot" accept="image/*" required><br><br>

                <label>
                    <input type="checkbox" required> I agree to Train&Gain's Terms and authorize subscription charges.
                </label><br><br>

                <button type="submit" class="cyber-button">Submit Payment</button>
            </form>

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