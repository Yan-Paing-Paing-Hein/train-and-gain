<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
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
                <h2 class="section-title">Welcome, <?php echo htmlspecialchars($_SESSION['client_name']); ?>!</h2>
                <p class="section-subtitle">Get Started with Train&Gain</p>
            </div>



            <div class="features-grid2">
                <div class="feature-card2">
                    <div class="card-inner">
                        <div class="card-front">
                            <h3>Step 1: Fill Survey Form</h3>
                            <p>Tell us about your body stats and fitness goals.</p>
                        </div>
                        <div class="card-back">
                            <a href="survey.php" class="cyber-button">Fill Survey</a>
                        </div>
                    </div>
                </div>

                <div class="feature-card2">
                    <div class="card-inner">
                        <div class="card-front">
                            <h3>Step 2: Choose Workout Plan & Coach</h3>
                            <p>Select one of the 6 workout plans. Each plan will show available coaches.</p>
                        </div>
                        <div class="card-back">
                            <a href="plans.php" class="cyber-button">Choose Plan</a>
                        </div>
                    </div>
                </div>

                <div class="feature-card2">
                    <div class="card-inner">
                        <div class="card-front">
                            <h3>Step 3: Make Payment</h3>
                            <p>Complete your payment to activate your account.</p>
                        </div>
                        <div class="card-back">
                            <a href="payment.php" class="cyber-button">Make Payment</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- <h1 class="section-subtitle">You are currently <strong style="color:red;">Inactive</strong>. Please complete the steps above to activate your account.</h1> -->

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
            const cards = document.querySelectorAll(".feature-card2");
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add("animate");
                    // remove class after animation so hover still works
                    setTimeout(() => {
                        card.classList.remove("animate");
                    }, 1000); // equal to animation duration
                }, index * 300); // 1s delay per card
            });
        });
    </script>

</body>

</html>

<!-- http://localhost/train&gain/client/dashboard.php -->