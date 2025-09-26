<?php
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
            </ul>
            <!-- <div class="nav-bottom">
                <a href="../blogpost/index.php" class="cyber-button">View All Blogposts</a>
            </div> -->
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



    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($blog = $result->fetch_assoc()): ?>
            <?php
            $postIndex++;
            // Ensure only filename is used
            $blogImagePath = '../admin/blogpost/uploads/' . basename($blog['blog_image']);
            ?>
            <!-- Contact Section -->
            <section class="contact fade-up" id="contact">

                <div class="features-container2">
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
                                        <span class="blog-status published">Status: Published</span>
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
                                        <span class="blog-status published">Status: Published</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </section>

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
        <h1 style="text-align:center; color:#f900e0;">No blogposts found.</h1>
    <?php endif; ?>



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

<!-- http://localhost/train&gain/client/blogpost.php -->