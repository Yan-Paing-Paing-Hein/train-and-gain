<?php
require_once("../../db_connect.php");

session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];

// Fetch process status for Step 1 & Step 2
$stmt = $conn->prepare("SELECT survey_completed, plan_selected, payment_done FROM client_process WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$process = $result->fetch_assoc();
$stmt->close();

// Convert to integers
$survey_completed = $process ? (int)$process['survey_completed'] : 0;
$plan_selected = $process ? (int)$process['plan_selected'] : 0;
$payment_done = $process ? (int)$process['payment_done'] : 0;
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-box {
            background: #1a1a1a;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            max-width: 90%;
            text-align: center;
            border: 2px solid #f900e0;
            color: #00ffff;
            box-shadow: 0 0 15px #f900e0aa,
                0 0 30px #f900e080,
                0 0 45px #f900e060;
            animation: fadeIn 0.3s ease, neonGlow 2s infinite alternate;
        }

        .modal-box h3 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #f900e0;
            /* Neon pink */
        }

        .modal-box p {
            color: #00ffff;
            /* Neon cyan */
            font-size: 1rem;
            line-height: 1.4;
        }

        /* Got it button */
        .btn-gotit {
            margin-top: 20px;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            background: #00ffff;
            color: #1a1a1a;
            box-shadow: 0 0 8px #00ffffaa,
                0 0 16px #00ffff88,
                0 0 24px #00ffff66;
            transition: 0.2s ease;
        }

        .btn-gotit:hover {
            background: #00ffff;
            box-shadow: 0 0 15px #00ffffff,
                0 0 30px #00ffffcc,
                0 0 45px #00ffff99;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes neonGlow {
            0% {
                box-shadow: 0 0 10px #f900e080, 0 0 20px #f900e060, 0 0 30px #f900e040;
            }

            100% {
                box-shadow: 0 0 20px #f900e0ff, 0 0 40px #f900e0cc, 0 0 60px #f900e099;
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
                <p class="section-subtitle">Get Started with Train&Gain</p>
            </div>

            <div>
                <?php
                // Check if payment is rejected for this client
                $reject_stmt = $conn->prepare("
                SELECT admin_message, status 
                FROM client_payment 
                WHERE client_id = ? 
                ORDER BY id DESC 
                LIMIT 1
                ");
                $reject_stmt->bind_param("i", $client_id);
                $reject_stmt->execute();
                $reject_result = $reject_stmt->get_result();
                $latest_payment = $reject_result->fetch_assoc();
                $reject_stmt->close();

                // Display rejection message if applicable
                if ($latest_payment && $latest_payment['status'] === 'Rejected' && $payment_done == 0) {
                    echo '<p style="color:#ff0033; font-size:1.2rem; text-align:center; text-shadow:0 0 8px #ff0033;"><strong>Payment Rejected:</strong> ' . htmlspecialchars($latest_payment['admin_message']) . '</p>';
                }
                ?>
            </div>

            <br><br>


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
                            <a href="plans.php" id="choosePlanBtn" class="cyber-button">Choose Plan</a>
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
                            <a href="payment_plan.php" id="makePaymentBtn" class="cyber-button">Make Payment</a>
                        </div>
                    </div>
                </div>

                <div id="completion-message" style="display:none; text-align:center; margin-top:40px;">
                    <p style="color:#00ffff; font-size:1.3rem; text-shadow:0 0 8px #00ffff;">
                        All steps are completed. But please wait for admin approval.<br>
                        If some of your information are missing, you may need to fill the required step again.
                    </p>
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
        document.addEventListener("DOMContentLoaded", function() {
            const choosePlanBtn = document.getElementById("choosePlanBtn");
            const makePaymentBtn = document.getElementById("makePaymentBtn");
            const step1Modal = document.getElementById("step1Modal");
            const step3Modal = document.getElementById("step3Modal");
            const closeStep1Btn = document.getElementById("closeModalBtn");
            const closeStep3Btn = document.getElementById("closeModalBtnStep3");

            const surveyCompleted = <?php echo $survey_completed; ?>;
            const planSelected = <?php echo $plan_selected; ?>;

            // Step 2 button
            choosePlanBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (surveyCompleted === 0) {
                    step1Modal.style.display = "flex";
                } else {
                    window.location.href = "plans.php";
                }
            });

            // Step 3 button
            makePaymentBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (surveyCompleted === 0 || planSelected === 0) {
                    step3Modal.style.display = "flex";
                } else {
                    window.location.href = "payment_plan.php";
                }
            });

            // Close modals
            closeStep1Btn.addEventListener("click", () => step1Modal.style.display = "none");
            closeStep3Btn.addEventListener("click", () => step3Modal.style.display = "none");
        });
    </script>

    <!-- Step 1 Incomplete Modal -->
    <div id="step1Modal" class="modal-overlay">
        <div class="modal-box">
            <h3>Complete Step 1 First!</h3>
            <p>You need to fill out the survey form before selecting your workout plan.</p>
            <button id="closeModalBtn" class="btn-gotit">Got it</button>
        </div>
    </div>

    <!-- Step 3 Incomplete Modal -->
    <div id="step3Modal" class="modal-overlay">
        <div class="modal-box">
            <h3>Complete Steps 1 & 2 First!</h3>
            <p>You need to complete the survey and choose a workout plan before making payment.</p>
            <button id="closeModalBtnStep3" class="btn-gotit">Got it</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const featuresGrid = document.querySelector(".features-grid2");
            const completionMessage = document.getElementById("completion-message"); // ← this line was missing
            const surveyCompleted = <?php echo $survey_completed; ?>;
            const planSelected = <?php echo $plan_selected; ?>;
            const paymentDone = <?php echo $payment_done; ?>;

            // Hide the steps section if all 3 are completed
            if (surveyCompleted === 1 && planSelected === 1 && paymentDone === 1) {
                // Hide only the step cards, not the outer container
                document.querySelectorAll(".feature-card2").forEach(card => {
                    card.style.display = "none";
                });

                // Show neon message
                completionMessage.style.display = "block";
            }
        });
    </script>

</body>

</html>

<!-- http://localhost/train&gain/client/dashboard/welcome.php -->