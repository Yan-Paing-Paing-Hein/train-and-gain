<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Fetch process info first
$stmt = $conn->prepare("SELECT payment_done FROM client_process WHERE client_id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$res = $stmt->get_result();
$process = $res->fetch_assoc();
$stmt->close();

// Restrict if payment is already done
if ($process && $process['payment_done'] == 1) {
    die("<h1 style='text-align:center; margin-top:50px;'>You've already finished final step 3 and made payment. Cannot change options anymore.</h1>");
}

// Then check for plan param
if (!isset($_GET['plan'])) {
    die("<h1 style='text-align:center;margin-top:50px;'>No plan selected.</h1>");
}
$plan = $_GET['plan'];

// Fetch available coaches for this plan (specialty)
$stmt = $conn->prepare("SELECT * FROM coach WHERE specialty = ? AND status = 'Active'");
$stmt->bind_param("s", $plan);
$stmt->execute();
$coaches = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        /* --- Grid Layout --- */
        .coach-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 40px;
        }

        /* --- Coach Card --- */
        .coach-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 2px solid #00ffff;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transform: translateY(50px) scale(0.9);
            opacity: 0;
            transition: transform 0.6s ease, opacity 0.6s ease, box-shadow 0.4s ease;
            box-shadow: 0 0 15px rgba(0, 255, 229, 0.2);
        }

        .coach-card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px #f900e0, 0 0 45px #00ffff;
            border-color: #f900e0;
        }

        /* Profile Picture with Neon Glow */
        .coach-card img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 4px solid transparent;
            background: linear-gradient(270deg, #f900e0, #00ffff, #f900e0);
            background-size: 300% 300%;
            animation: neon-border 4s linear infinite;
            padding: 4px;
            margin-bottom: 20px;
            box-shadow: 0 0 20px #00ffff, 0 0 35px #f900e0;
        }

        /* Text */
        .coach-card h3 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            color: #f900e0;
            text-shadow: 0 0 8px #f900e0, 0 0 15px rgba(249, 0, 224, 0.6);
        }

        .coach-card p {
            font-size: 0.95rem;
            margin: 6px 0;
            color: #00ffff;
            text-shadow: 0 0 8px #00ffff;
        }

        /* Button */
        .cyber-button2 {
            margin-top: 15px;
            padding: 12px 25px;
            border: 2px solid #00ffff;
            border-radius: 10px;
            background: transparent;
            color: #00ffff;
            font-size: 1rem;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
        }

        .cyber-button2::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .cyber-button2:hover {
            color: #f900e0;
            border-color: #f900e0;
            box-shadow: 0 0 15px #f900e0, 0 0 30px #00ffff;
        }

        .cyber-button2:hover::before {
            left: 100%;
        }

        /* Animations */
        @keyframes neon-border {
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

        @keyframes hologramFlyIn {
            from {
                transform: translateY(80px) scale(0.85);
                opacity: 0;
                filter: blur(8px);
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
                filter: blur(0);
            }
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



    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title3">Available Coaches for <?php echo htmlspecialchars($plan); ?></h2>
                <p class="section-subtitle">Get Started with Train&Gain</p>
            </div>

            <div class="coach-grid">
                <?php while ($coach = $coaches->fetch_assoc()): ?>
                    <div class="coach-card">
                        <img src="../../admin/coach/<?php echo htmlspecialchars($coach['profile_picture']); ?>" alt="Coach">
                        <h3><?php echo htmlspecialchars($coach['full_name']); ?></h3>
                        <p><?php echo htmlspecialchars($coach['about']); ?></p>
                        <p>Experience: <?php echo htmlspecialchars($coach['experience']); ?> years</p>
                        <p>Email: <?php echo htmlspecialchars($coach['email']); ?></p>
                        <p>Contact: <?php echo htmlspecialchars($coach['phone_number']); ?></p>
                        <form method="POST" action="save_plan.php">
                            <input type="hidden" name="plan" value="<?php echo htmlspecialchars($plan); ?>">
                            <input type="hidden" name="coach_id" value="<?php echo $coach['id']; ?>">
                            <button type="submit" class="cyber-button2">Choose This Coach</button>
                        </form>
                    </div>
                <?php endwhile; ?>
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
        // JS entrance animation for hologram effect
        document.addEventListener("DOMContentLoaded", () => {
            const cards = document.querySelectorAll(".coach-card");
            cards.forEach((card, i) => {
                setTimeout(() => {
                    card.style.animation = "hologramFlyIn 0.9s ease forwards";
                }, i * 300); // delay each card
            });
        });
    </script>

</body>

</html>