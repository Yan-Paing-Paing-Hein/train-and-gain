<?php
// Use the same session name for consistency across coach site
session_name("coach_session");
session_start();

// Retrieve and clear any error message
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : "";
unset($_SESSION['error_message']);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in Form</title>
    <link href="../css/templatemo-nexus-style.css" rel="stylesheet">
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
            <!-- <ul class="nav-links">
                <li><a href="../client/blogpost.php">BlogPost</a></li>
                <li><a href="../client/coach.php">Coach</a></li>
            </ul> -->
            <div class="nav-bottom">
                <a href="../coach/register_form.php" class="cyber-button">Register</a>
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
                <h2 class="section-title">Log in to your account</h2>
                <p class="section-subtitle">Come train and come gain!</p>
            </div>

            <div class="contact-form-wrapper">
                <form class="contact-form" method="POST" action="login_action.php">

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email"
                            placeholder="useremail@gmail.com"
                            required autocomplete="email">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password"
                                placeholder="Enter your password"
                                required autocomplete="current-password">
                            <button type="button" id="togglePassword" class="toggle-password">Show</button>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <?php if (!empty($error_message)) : ?>
                        <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($error_message); ?>
                            <br>
                            <a href="forgot_password.php" class="forgot-link">
                                Forgot your password?
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success'])): ?>
                        <div class="success-message" style="color: #00ff33; text-align: center; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($_GET['success']); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-create btn-upload">Log in</button>

                </form>
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

    <script src="../js/templatemo-nexus-scripts.js"></script>

    <!-- Password show/hide button -->
    <script>
        // First password
        const passwordField = document.getElementById("password");
        const toggleBtn = document.getElementById("togglePassword");

        toggleBtn.addEventListener("click", () => {
            const isPassword = passwordField.type === "password";
            passwordField.type = isPassword ? "text" : "password";
            toggleBtn.textContent = isPassword ? "Hide" : "Show";
        });
    </script>
</body>

</html>

<!-- http://localhost/train&gain/coach/login_form.php -->