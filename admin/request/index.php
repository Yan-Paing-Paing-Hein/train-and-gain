<?php
// Protect admin access
require_once "../admin_protect.php";
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Index</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
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
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../client/index.php">Client</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
                <li><a href="../request/index.php">Request</a></li>
            </ul>
            <div class="nav-bottom">
                <a href="../logout.php" class="cyber-button">Log out</a>
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
            <a href="#" class="cyber-button">Create Client</a>
        </div>
        <nav class="mobile-menu-nav">
            <ul>
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../client/index.php">Client</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
                <li><a href="../request/index.php">Request</a></li>
            </ul>
        </nav>
    </div>

    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title">Password Reset Requests</h2>
                <p class="section-subtitle">3 requests pending!</p>
            </div>
        </div>



        <div class="client-category-container">
            <!-- Registered Clients -->
            <div class="client-category-box">
                <h3>Client Request</h3>
                <p>Clients who forgot password.</p>
                <a href="../request/client_reset_requests.php" class="btn-view">View</a>
            </div>

            <!-- Active Clients -->
            <div class="client-category-box">
                <h3>Coach Request</h3>
                <p>Coaches who forgot password.</p>
                <a href="../request/coach_reset_requests.php" class="btn-view">View</a>
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
</body>

</html>

<!-- http://localhost/train&gain/admin/request/index.php -->