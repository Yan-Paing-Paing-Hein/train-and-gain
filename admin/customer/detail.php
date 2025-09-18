<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Detail</title>
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
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../customer/index.php">Customer</a></li>
                <li><a href="../review/index.php">Review</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
            </ul>
            <div class="nav-bottom">
                <a href="../customer/index.php" class="cyber-button">View All Customers</a>
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
            <a href="../customer/index.php" class="cyber-button">View All Customers</a>
        </div>
        <nav class="mobile-menu-nav">
            <ul>
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../customer/index.php">Customer</a></li>
                <li><a href="../review/index.php">Review</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
            </ul>
        </nav>
    </div>

    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">

        <div class="customer-detail-container">
            <h2 class="customer-detail-title">Customer Detail</h2>
            <table class="customer-detail-table">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>001</td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td>John Doe</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>johndoe@gmail.com</td>
                    </tr>
                    <tr>
                        <th>Phone Number</th>
                        <td>+1 (617) 555-0145</td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td>••••••••</td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td>Male</td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td>1995-08-12</td>
                    </tr>
                    <tr>
                        <th>Height (cm)</th>
                        <td>178</td>
                    </tr>
                    <tr>
                        <th>Weight (kg)</th>
                        <td>72</td>
                    </tr>
                    <tr>
                        <th>Fitness Goal</th>
                        <td>Muscle Gain</td>
                    </tr>
                    <tr>
                        <th>Assigned Fitness Coach</th>
                        <td>Sam Sulek</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="customer-detail-status-inactive">Inactive</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><br><br><br><br>

        <div class="action-bar fade-up">

            <div class="action-left">
                <a href="../customer/edit.php">
                    <button class="btn-edit">Edit</button>
                </a>
            </div>

            <div class="action-right">
                <button class="btn-delete">Delete</button>
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
        document.querySelectorAll(".customer-detail-table tr").forEach(row => {
            row.addEventListener("mouseenter", () => {
                row.style.transition = "transform 0.3s ease";
                row.style.transform = "scale(1.02)";
            });
            row.addEventListener("mouseleave", () => {
                row.style.transform = "scale(1)";
            });
        });
    </script>


    <script>
        document.querySelectorAll(".customer-detail-table tr").forEach((row, i) => {
            row.style.opacity = 0;
            row.style.transform = "translateX(-20px)";
            setTimeout(() => {
                row.style.transition = "all 0.6s ease";
                row.style.opacity = 1;
                row.style.transform = "translateX(0)";
            }, i * 200);
        });
    </script>


</body>

</html>

<!-- http://localhost/train&gain/admin/customer/detail.php -->