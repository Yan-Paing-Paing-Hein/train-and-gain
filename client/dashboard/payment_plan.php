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
    <style>
        /* Existing styles remain same as before */
        .contact {
            padding: 6rem 2rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .contact-container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
        }

        .section-header {
            margin-bottom: 3rem;
        }

        .section-title3 {
            font-size: 2.5rem;
            color: #00ffff;
            text-shadow: 0 0 25px #00ffff;
            letter-spacing: 2px;
        }

        .section-subtitle {
            color: #f900e0;
            font-size: 1.2rem;
            text-shadow: 0 0 10px #f900e0;
        }

        /* Grid Layout */
        .plan-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            justify-items: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .plan-option {
            display: block;
            width: 100%;
            cursor: pointer;
        }

        .plan-option input {
            display: none;
        }

        .plan-card {
            display: block;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(0, 255, 255, 0.3);
            padding: 2rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            text-align: center;
            position: relative;
            animation: pulseGlow 2.5s infinite;
            clip-path: polygon(20px 0%, 100% 0%, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0% 100%, 0% 20px);
        }

        .plan-option:hover .plan-card {
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.4);
            transform: translateY(-5px);
        }

        .plan-option input:checked+.plan-card {
            background: rgba(0, 255, 255, 0.1);
            border-color: #f900e0;
            box-shadow: 0 0 35px rgba(249, 0, 224, 0.6);
            transform: scale(1.05);
            animation: pulseGlowStrong 1.5s infinite;
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
            }

            50% {
                box-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
            }
        }

        @keyframes pulseGlowStrong {

            0%,
            100% {
                box-shadow: 0 0 25px rgba(249, 0, 224, 0.4);
            }

            50% {
                box-shadow: 0 0 50px rgba(249, 0, 224, 0.8);
            }
        }

        .plan-name {
            font-size: 1.5rem;
            color: #00ffff;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 0 10px #00ffff;
        }

        .plan-price {
            display: block;
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin: 0.5rem 0;
            text-shadow: 0 0 20px #00ffff;
        }

        .plan-period {
            color: #ccc;
            font-size: 1rem;
        }

        .discount {
            color: #f900e0;
            font-weight: bold;
            text-shadow: 0 0 8px #f900e0;
        }

        /* Continue Button */
        .cyber-button2 {
            background: linear-gradient(90deg, #00ffff, #f900e0);
            color: #000;
            padding: 1rem 3rem;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            text-transform: uppercase;
            transition: 0.4s ease;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.4);
            position: relative;
            z-index: 2;
        }

        .cyber-button2:hover {
            transform: scale(1.1);
            box-shadow: 0 0 35px rgba(249, 0, 224, 0.6);
        }

        /* Neon line connecting animation */
        .neon-line {
            position: absolute;
            height: 3px;
            background: linear-gradient(90deg, #00ffff, #f900e0);
            border-radius: 10px;
            box-shadow: 0 0 15px #f900e0, 0 0 25px #00ffff;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            pointer-events: none;
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

            <form method="POST" action="payment_method.php" class="pricing-form">
                <div class="plan-grid">
                    <label class="plan-option">
                        <input type="radio" name="plan_type" value="Monthly" required>
                        <span class="plan-card">
                            <span class="plan-name">Monthly</span>
                            <span class="plan-price">$50</span>
                            <span class="plan-period">per month</span>
                        </span>
                    </label>

                    <label class="plan-option">
                        <input type="radio" name="plan_type" value="Six-Months">
                        <span class="plan-card">
                            <span class="plan-name">Six-Months</span>
                            <span class="plan-price">$240</span>
                            <span class="plan-period">/ 6 months <span class="discount">(20% off)</span></span>
                        </span>
                    </label>

                    <label class="plan-option">
                        <input type="radio" name="plan_type" value="Yearly">
                        <span class="plan-card featured">
                            <span class="plan-name">Yearly</span>
                            <span class="plan-price">$360</span>
                            <span class="plan-period">per year <span class="discount">(40% off)</span></span>
                        </span>
                    </label>
                </div>

                <!-- Neon line placeholder -->
                <div class="neon-line"></div>

                <button type="submit" class="cyber-button2">Continue</button>
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

    <script>
        const fadeUps = document.querySelectorAll('.fade-up');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, index * 200);
                }
            });
        }, {
            threshold: 0.2
        });
        fadeUps.forEach((el) => observer.observe(el));

        // Neon line effect
        const planOptions = document.querySelectorAll('.plan-option input');
        const neonLine = document.querySelector('.neon-line');
        const continueBtn = document.querySelector('.cyber-button2');

        planOptions.forEach(input => {
            input.addEventListener('change', () => {
                const card = input.nextElementSibling;
                const rect1 = card.getBoundingClientRect();
                const rect2 = continueBtn.getBoundingClientRect();

                const parentRect = document.querySelector('.contact-container').getBoundingClientRect();

                // Calculate positions relative to container
                const startX = rect1.left + rect1.width / 2 - parentRect.left;
                const startY = rect1.bottom - parentRect.top;
                const endX = rect2.left + rect2.width / 2 - parentRect.left;
                const endY = rect2.top - parentRect.top;

                neonLine.style.left = `${startX}px`;
                neonLine.style.top = `${startY}px`;
                neonLine.style.width = `0px`;
                neonLine.style.opacity = 1;

                // Animate the line connecting
                requestAnimationFrame(() => {
                    neonLine.style.transform = `rotate(${Math.atan2(endY - startY, endX - startX)}rad)`;
                    neonLine.style.width = `${Math.hypot(endX - startX, endY - startY)}px`;
                });
            });
        });
    </script>

</body>

</html>