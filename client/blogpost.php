<?php
session_start();

// Include database connection
include '../db_connect.php';

// Number of posts per page
$postsPerPage = 10;

// Get current page from URL, default = 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;

// Calculate offset
$offset = ($page - 1) * $postsPerPage;

// Fetch total number of published posts
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM blogpost WHERE status = 'Published'");
$totalPosts = $totalResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalPosts / $postsPerPage);

// Fetch only Published blogposts with LIMIT + OFFSET
$sql = "SELECT id, category, title, content, blog_image, publish_date, status 
        FROM blogpost 
        WHERE status = 'Published'
        ORDER BY id DESC 
        LIMIT $postsPerPage OFFSET $offset";
$result = $conn->query($sql);

$postIndex = $offset; // counter for alternating layout
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogpost Contents</title>
    <link href="../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        /* Sticky footer & main content wrapper */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            /* ensures footer sticks to bottom */
        }

        /* Restore spacing between blogposts */
        .features-container2 {
            max-width: 1240px;
            margin: 0 auto;
            padding: 0 20px;
            margin-bottom: 80px;
            /* restored vertical gap */
        }

        .diagonal-grid {
            display: flex;
            flex-direction: column;
            gap: 60px;
            /* restored spacing inside */
        }

        .feature-row {
            display: flex;
            align-items: center;
            gap: 80px;
            position: relative;
        }

        .feature-row:nth-child(even) {
            flex-direction: row-reverse;
        }
    </style>
</head>

<body id="top">
    <!-- Background Effects -->
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
            </ul>
            <div class="nav-bottom">

                <?php if (isset($_SESSION['client_id'])): ?>
                    <!-- Show these if logged in -->
                    <a href="../client/dashboard/home.php" class="cyber-button">Dashboard</a>
                    <a href="../client/logout.php" class="cyber-button">Log out</a>
                <?php else: ?>
                    <!-- Show these if not logged in -->
                    <a href="../client/register_form.php" class="cyber-button">Register</a>
                    <a href="../client/login_form.php" class="cyber-button">Log in</a>
                <?php endif; ?>
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
            <a href="#" class="cyber-button">View All Blogposts</a>
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

    <!-- Main Content -->
    <section class="contact" id="contact">
        <div class="main-content">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($blog = $result->fetch_assoc()): ?>
                    <?php
                    $postIndex++;
                    $blogImagePath = '../admin/blogpost/uploads/' . basename($blog['blog_image']);
                    ?>
                    <div class="features-container2 fade-up">
                        <div class="diagonal-grid">
                            <div class="feature-row">
                                <?php if ($postIndex % 2 != 0): ?>
                                    <!-- Odd Post: Content Left, Image Right -->
                                    <div class="feature-content glass">
                                        <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                                        <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                                        <div class="blog-meta">
                                            <span class="blog-date">Published Date: <?php echo $blog['publish_date']; ?></span>
                                            <span class="blog-category">Category: <?php echo htmlspecialchars($blog['category']); ?></span>
                                            <!-- <span class="blog-status published">Status: Published</span> -->
                                        </div>
                                    </div>
                                    <div class="feature-visual glass">
                                        <img src="<?php echo htmlspecialchars($blogImagePath); ?>" alt="Blog Image">
                                    </div>
                                <?php else: ?>
                                    <!-- Even Post: Image Left, Content Right -->
                                    <div class="feature-visual glass">
                                        <img src="<?php echo htmlspecialchars($blogImagePath); ?>" alt="Blog Image">
                                    </div>
                                    <div class="feature-content glass">
                                        <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                                        <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>
                                        <div class="blog-meta">
                                            <span class="blog-date">Published Date: <?php echo $blog['publish_date']; ?></span>
                                            <span class="blog-category">Category: <?php echo htmlspecialchars($blog['category']); ?></span>
                                            <!-- <span class="blog-status published">Status: Published</span> -->
                                        </div>
                                    </div>
                                <?php endif; ?>
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
                <h1 class="fade-up" style="text-align:center; color:#f900e0;">
                    No blogpost available right now.
                </h1>
            <?php endif; ?>

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
                <p class="footer-credit">Brought to you by <a href="https://templatemo.com" target="_blank" rel="noopener nofollow">TemplateMo</a></p>
            </div>
        </div>
    </footer>

    <script src="../js/templatemo-nexus-scripts.js"></script>
</body>

</html>

<!-- http://localhost/train&gain/client/blogpost.php -->