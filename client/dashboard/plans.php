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



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        /* Grid Layout */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 40px;
        }

        /* Plan Cards */
        .plan-card {
            position: relative;
            background: rgba(0, 0, 0, 0.7);
            border: 2px solid #f900e0;
            border-radius: 20px;
            overflow: hidden;
            transform: skew(-5deg);
            transition: all 0.4s ease-in-out;
            box-shadow: 0 0 15px rgba(249, 0, 224, 0.3);
            cursor: pointer;
        }

        /* Hover Glow */
        .plan-card:hover {
            box-shadow: 0 0 40px #f900e0, 0 0 80px #f900e0;
            border-color: #f900e0;
        }

        /* Video Background */
        .plan-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* slightly dark by default */
            opacity: 0.6;
            /* darken video */
            filter: brightness(1);
            transition: opacity 0.5s ease-in-out, filter 0.5s ease-in-out;
            pointer-events: none;
            z-index: 1;
        }

        .plan-card:hover .plan-video {
            /* fully visible */
            opacity: 1;
            /* brighten video on hover */
            filter: brightness(1.2);
        }

        /* Plan Content */
        .plan-content {
            position: relative;
            padding: 40px;
            text-align: center;
            z-index: 2;
            /* reverse skew to keep text straight */
            transform: skew(5deg);
        }

        .plan-content h3 {
            font-size: 1.6rem;
            color: #fff;
            margin-bottom: 20px;
            text-shadow: 0 0 10px #f900e0;
        }

        /* Current Plan Section */
        .current-plan {
            margin-bottom: 50px;
            text-align: center;
        }

        .current-plan h2 {
            font-size: 1.8rem;
            color: #fff;
            text-shadow: 0 0 15px #f900e0, 0 0 25px #00ffe5;
            margin-bottom: 40px;
        }

        /* Flex Layout */
        .current-plan .coach-card {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 60px;
            background: rgba(0, 0, 0, 0.6);
            border: 2px solid #f900e0;
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 0 30px rgba(249, 0, 224, 0.6);
            position: relative;
            overflow: hidden;
        }

        /* Neon Gradient Profile */
        .neon-border {
            padding: 6px;
            border-radius: 50%;
            background: linear-gradient(270deg, #f900e0, #00ffe5, #f900e0);
            background-size: 600% 600%;
            animation: gradientRotate 6s ease infinite;
        }

        .neon-border img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            border: 4px solid rgba(255, 255, 255, 0.15);
        }

        /* Gradient rotation animation */
        @keyframes gradientRotate {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Left & Right Sides */
        .plan-side,
        .coach-side {
            display: flex;
            align-items: center;
        }

        /* Arrow Lines */
        .arrow-line {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Arrows expanding effect */
        .arrow {
            display: inline-block;
            height: 3px;
            background: linear-gradient(90deg, #f900e0, #00ffe5);
            position: relative;
            flex-shrink: 0;
            animation: expandLine 2s infinite;
        }

        .left-arrow .arrow {
            width: 0;
            animation: expandLineLeft 2s infinite;
        }

        .right-arrow .arrow {
            width: 0;
            animation: expandLineRight 2s infinite;
        }

        /* Expanding line animations */
        @keyframes expandLineLeft {
            0% {
                width: 0;
                opacity: 0;
            }

            40% {
                width: 60px;
                opacity: 1;
            }

            100% {
                width: 0;
                opacity: 0;
            }
        }

        @keyframes expandLineRight {
            0% {
                width: 0;
                opacity: 0;
            }

            40% {
                width: 60px;
                opacity: 1;
            }

            100% {
                width: 0;
                opacity: 0;
            }
        }

        /* Arrowheads */
        .left-arrow .arrow::before,
        .right-arrow .arrow::after {
            content: "";
            position: absolute;
            top: -6px;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
        }

        .left-arrow .arrow::before {
            left: -10px;
            border-right: 10px solid #f900e0;
        }

        .right-arrow .arrow::after {
            right: -10px;
            border-left: 10px solid #00ffe5;
        }

        /* Text Styling */
        .plan-name,
        .coach-name {
            font-size: 1.3rem;
            color: #fff;
            text-shadow: 0 0 10px #f900e0, 0 0 15px #00ffe5;
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

            <?php if ($current_plan): ?>
                <section class="current-plan">
                    <h2>Your Currently Selected Plan & Coach</h2>

                    <div class="coach-card">
                        <!-- Workout Plan (Left) -->
                        <div class="plan-side">
                            <div class="arrow-line left-arrow">
                                <h3 class="plan-name"><?php echo htmlspecialchars($current_plan['plan']); ?></h3>
                                <span class="arrow"></span>
                            </div>
                        </div>

                        <!-- Coach Profile (Center) -->
                        <div class="coach-profile">
                            <div class="neon-border">
                                <img src="../../admin/coach/<?php echo htmlspecialchars($current_plan['profile_picture']); ?>" alt="Coach">
                            </div>
                        </div>

                        <!-- Coach Name (Right) -->
                        <div class="coach-side">
                            <div class="arrow-line right-arrow">
                                <span class="arrow"></span>
                                <p class="coach-name"><strong>Coach:</strong> <?php echo htmlspecialchars($current_plan['full_name']); ?></p>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <br><br><br>

            <div class="plans-grid">
                <?php foreach ($plans as $plan): ?>
                    <div class="plan-card">
                        <video autoplay muted loop playsinline class="plan-video">
                            <source src="videos/<?php echo strtolower(str_replace(' ', '_', $plan)); ?>.mp4" type="video/mp4">
                        </video>
                        <div class="plan-content">
                            <h3><?php echo $plan; ?></h3>
                            <a href="choose_coach.php?plan=<?php echo urlencode($plan); ?>" class="cyber-button">Choose Plan</a>
                        </div>
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

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const leftArrow = document.querySelector(".left-arrow .arrow");
            const rightArrow = document.querySelector(".right-arrow .arrow");

            // Stagger animations for a futuristic sync effect
            if (leftArrow && rightArrow) {
                leftArrow.style.animationDelay = "0s";
                rightArrow.style.animationDelay = "1s";
            }
        });
    </script>

</body>

</html>