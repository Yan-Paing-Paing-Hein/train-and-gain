<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusFlow - The Future of Team Collaboration</title>
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
                <a href="../coach/index.php" class="cyber-button">View All Coaches</a>
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
            <a href="#" class="cyber-button">Access Terminal</a>
        </div>
        <nav class="mobile-menu-nav">
            <ul>
                <li><a href="#features">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#stats">Stats</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </div>



    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">

        <h2 class="section-title2 fade-up">ID.6 Coach</h2>
        <div class="about-container fade-up">
            <div class="about-image">
                <div class="profile-img-wrapper">
                    <div class="profile-img">
                        <img src="images/coach.jpg" alt="Image">
                    </div>
                </div>
            </div>

            <div class="about-content">
                <div class="about-header">
                    <h3>Sam Sulek</h3>
                    <div class="about-tags">
                        <span class="tag-specialty">Specialty: Weight Gain</span>
                        <span class="tag-status active">Status: Active</span>
                        <!-- <span class="tag-status inactive">Status: Inactive</span> -->
                    </div>
                </div>

                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px;">
                    Sam Sulek (born February 7, 2002) is an American YouTuber, fitness influencer, and professional bodybuilder who gained fame in 2023 for his "Spring Bulk" YouTube series. He is known for his no-nonsense bodybuilding content, showing his workouts and journey to becoming an IFBB Pro, which he achieved at the 2025 Arnold Amateur finals. Sulek is also a popular social media personality, with a large following on YouTube and Instagram, and he sells his own merchandise.
                </p>

                <div class="about-stats">
                    <div class="stat-card">
                        <div class="stat-number2">5</div>
                        <div class="stat-label2">Years Exp</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number2">samsulek</div>
                        <div class="stat-label2">@gmail.com</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number2">+1 (617) 555-0145</div>
                        <div class="stat-label2">Contact No.</div>
                    </div>
                </div>
            </div>
        </div>
        <br><br><br>



        <br><br><br><br><br>

        <div class="action-bar fade-up">

            <div class="action-left">
                <a href="../coach/edit.php">
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



    <!-- For fade-up scroll -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const fadeEls = document.querySelectorAll(".fade-up");

            const observer = new IntersectionObserver(
                (entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add("show");
                            obs.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.2
                }
            );

            fadeEls.forEach(el => observer.observe(el));
        });
    </script>

</body>

</html>

<!-- http://localhost/train&gain/admin/coach/detail.php -->