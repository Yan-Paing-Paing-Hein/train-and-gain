<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Index</title>
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
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
            </ul>
            <div class="nav-bottom">
                <a href="../customer/create.php" class="cyber-button">Create Customer</a>
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
            <a href="#" class="cyber-button">Create Customer</a>
        </div>
        <nav class="mobile-menu-nav">
            <ul>
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../customer/index.php">Customer</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
            </ul>
        </nav>
    </div>

    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title">Customer Table</h2>
                <p class="section-subtitle">35 customers have joined!</p>
            </div>
        </div>

        <!-- CRUD Table Section -->
        <div class="crud-table-container">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Gender</th>
                        <th>Fitness Goal</th>
                        <th>Status</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>johndoe@gmail.com</td>
                        <td>+1 (617) 555-0145</td>
                        <td>Male</td>
                        <td>Muscle Gain</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../blogpost/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr> -->


                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>johndoe@gmail.com</td>
                        <td>+1 (617) 555-0145</td>
                        <td>Male</td>
                        <td>Muscle Gain</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>2</td>
                        <td>Emma Stone</td>
                        <td>emma.stone@gmail.com</td>
                        <td>+1 (202) 555-0174</td>
                        <td>Female</td>
                        <td>Weight Loss</td>
                        <td><span class="status inactive">Inactive</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>3</td>
                        <td>Michael Smith</td>
                        <td>m.smith@yahoo.com</td>
                        <td>+44 20 7946 1234</td>
                        <td>Male</td>
                        <td>Endurance</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>4</td>
                        <td>Sophia Johnson</td>
                        <td>sophia.j@gmail.com</td>
                        <td>+1 (303) 555-0190</td>
                        <td>Female</td>
                        <td>Yoga</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>5</td>
                        <td>David Lee</td>
                        <td>dlee@hotmail.com</td>
                        <td>+1 (415) 555-0102</td>
                        <td>Male</td>
                        <td>Strength Training</td>
                        <td><span class="status inactive">Inactive</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>6</td>
                        <td>Olivia Brown</td>
                        <td>olivia.brown@gmail.com</td>
                        <td>+1 (646) 555-0125</td>
                        <td>Female</td>
                        <td>HIIT</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>7</td>
                        <td>James Wilson</td>
                        <td>jwilson@yahoo.com</td>
                        <td>+61 2 5550 6789</td>
                        <td>Male</td>
                        <td>Muscle Gain</td>
                        <td><span class="status inactive">Inactive</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>8</td>
                        <td>Ava Martinez</td>
                        <td>ava.martinez@gmail.com</td>
                        <td>+1 (212) 555-0188</td>
                        <td>Female</td>
                        <td>Yoga</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>9</td>
                        <td>William Davis</td>
                        <td>will.davis@gmail.com</td>
                        <td>+1 (305) 555-0156</td>
                        <td>Male</td>
                        <td>Weight Loss</td>
                        <td><span class="status inactive">Inactive</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                    <tr>
                        <td>10</td>
                        <td>Isabella Taylor</td>
                        <td>isabella.taylor@gmail.com</td>
                        <td>+1 (917) 555-0137</td>
                        <td>Female</td>
                        <td>Endurance</td>
                        <td><span class="status active">Active</span></td>
                        <td>
                            <a href="../customer/detail.php" class="btn-view">View Detail</a>
                        </td>
                    </tr>

                </tbody>
            </table>
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

<!-- http://localhost/train&gain/admin/customer/index.php -->