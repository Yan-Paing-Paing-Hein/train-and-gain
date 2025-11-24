<?php
session_name("coach_session");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coach Forgot Password</title>
    <link href="../css/templatemo-nexus-style.css" rel="stylesheet">
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
            <div class="nav-bottom">
                <a href="../coach/register_form.php" class="cyber-button">Register</a>
                <a href="../coach/login_form.php" class="cyber-button">Log in</a>
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
                <h2 class="section-title">Coach Forgot Password?</h2>
            </div>

            <div class="contact-form-wrapper">

                <form class="contact-form" action="forgot_password_action.php" method="POST">

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Enter your registered coach email</label>
                        <input type="email" name="email" id="email" placeholder="coach@example.com" required>
                    </div>

                    <!-- Error / Success Messages -->
                    <?php if (isset($_GET['error'])): ?>
                        <div class="error-message" style="color: red; text-align: center; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php elseif (isset($_GET['success'])): ?>
                        <div class="success-message" style="color: #00ff33; text-align: center; margin-bottom: 15px;">
                            <?php echo htmlspecialchars($_GET['success']); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="btn-create btn-upload">Send Reset Request</button>
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
</body>

</html>