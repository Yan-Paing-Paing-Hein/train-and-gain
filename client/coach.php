<?php
// Include DB connection
include '../db_connect.php';

// Number of coaches per page
$coachesPerPage = 10;

// Get current page from URL, default = 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

// Calculate offset
$offset = ($page - 1) * $coachesPerPage;

// Fetch total number of active coaches
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM coach WHERE status = 'Active'");
$totalCoaches = $totalResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalCoaches / $coachesPerPage);

// Fetch coaches with LIMIT + OFFSET
$sql = "SELECT * FROM coach WHERE status = 'Active' ORDER BY id ASC LIMIT $coachesPerPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our coaches</title>
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
            <ul class="nav-links">
                <li><a href="../client/blogpost.php">BlogPost</a></li>
                <li><a href="../client/coach.php">Coach</a></li>
                <li><a href="../client/register.php">Register</a></li>
            </ul>

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
            <a href="#" class="cyber-button">View All Coaches</a>
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



    <!-- All Coaches Section -->
    <section class="contact fade-up" id="contact">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($coach = $result->fetch_assoc()): ?>

                <div class="about-container fade-up" style="margin-bottom: 60px;">
                    <div class="about-image">
                        <div class="profile-img-wrapper">
                            <div class="profile-img">
                                <img src="../admin/coach/profiles/<?php echo htmlspecialchars(basename($coach['profile_picture'])); ?>"
                                    alt="Profile Picture">
                            </div>
                        </div>
                    </div>

                    <div class="about-content">
                        <div class="about-header">
                            <h3><?php echo htmlspecialchars($coach['full_name']); ?></h3>
                            <div class="about-tags">
                                <span class="tag-specialty">Specialty: <?php echo htmlspecialchars($coach['specialty']); ?></span>
                            </div>
                        </div>

                        <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 20px;">
                            <?php echo nl2br(htmlspecialchars($coach['about'])); ?>
                        </p>

                        <div class="about-stats">
                            <div class="stat-card">
                                <div class="stat-number2"><?php echo (int) $coach['experience']; ?></div>
                                <div class="stat-label2">Years Exp</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number2"><?php echo htmlspecialchars(str_replace('@gmail.com', '', $coach['email'])); ?></div>
                                <div class="stat-label2">@gmail.com</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number2"><?php echo htmlspecialchars($coach['phone_number']); ?></div>
                                <div class="stat-label2">Contact No.</div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

            <!-- Pagination -->
            <div class="pagination" style="text-align:center; margin:20px;">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="btn-prev">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $page): ?>
                        <strong>[<?php echo $i; ?>]</strong>
                    <?php else: ?>
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="btn-next">Next</a>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <h1 style="text-align:center; color:#f900e0;">No coach available right now.</h1>
        <?php endif; ?>
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

            fadeEls.forEach(el => {
                observer.observe(el);

                // If already visible on load → show immediately
                if (el.getBoundingClientRect().top < window.innerHeight) {
                    el.classList.add("show");
                    observer.unobserve(el);
                }
            });
        });
    </script>

</body>

</html>

<!-- http://localhost/train&gain/client/coach.php -->