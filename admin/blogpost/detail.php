<?php
// Protect admin access
require_once "../admin_protect.php";
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogpost Detail</title>
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
                <li><a href="../client/index.php">Client</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
                <li><a href="../request/index.php">Request</a></li>
            </ul>
            <div class="nav-bottom">
                <a href="../logout.php" class="cyber-button">Log out</a>
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
        <div class="nav-bottom">
            <a href="../logout.php" class="cyber-button">Log out</a>
        </div>
        <nav class="mobile-menu-nav">
            <ul>
                <li><a href="../admin_home.php">Home</a></li>
                <li><a href="../blogpost/index.php">BlogPost</a></li>
                <li><a href="../coach/index.php">Coach</a></li>
                <li><a href="../client/index.php">Client</a></li>
                <li><a href="../payment/index.php">Payment</a></li>
                <li><a href="../review/index.php">Review</a></li>
                <li><a href="../request/index.php">Request</a></li>
            </ul>
        </nav>
    </div>



    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">


        <?php
        // Include DB connection
        include '../../db_connect.php';

        // Get blogpost id from URL
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        // Fetch blogpost by id
        $sql = "SELECT * FROM blogpost WHERE id = $id LIMIT 1";
        $result = $conn->query($sql);
        $blog = $result->fetch_assoc();
        ?>

        <?php if ($blog): ?>
            <div class="contact-container">
                <div class="section-header">
                    <h2 class="section-title">ID.<?php echo $blog['id']; ?> Blogpost</h2>
                </div>
            </div>

            <div class="features-container2">
                <div class="diagonal-grid">
                    <div class="feature-row">
                        <div class="feature-content glass">

                            <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                            <p><?php echo nl2br(htmlspecialchars($blog['content'])); ?></p>

                            <div class="blog-meta">
                                <span class="blog-date">Published Date: <?php echo $blog['publish_date']; ?></span>
                                <span class="blog-category">Category: <?php echo htmlspecialchars($blog['category']); ?></span>
                                <?php if ($blog['status'] === 'Published'): ?>
                                    <span class="blog-status published">Status: Published</span>
                                <?php else: ?>
                                    <span class="blog-status draft">Status: Draft</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="feature-visual glass">
                            <img src="<?php echo htmlspecialchars($blog['blog_image']); ?>" alt="Blog Image">
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <h1 style="text-align:center; color:#f900e0;">Blogpost not found.</h1>
        <?php endif; ?>

        <br><br><br><br><br><br><br>

        <div class="action-bar fade-up">

            <div class="action-left">
                <a href="../blogpost/edit.php?id=<?php echo $blog['id']; ?>">
                    <button type="button" class="btn-edit">Edit</button>
                </a>
            </div>

            <div class="action-right">
                <button type="button" class="btn-delete" onclick="openDeleteModal(<?php echo $blog['id']; ?>)">
                    Delete
                </button>
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

    <!-- Delete Confirmation Box Pop Up -->
    <script>
        function openDeleteModal(id) {
            document.getElementById("deleteLink").href = "delete.php?id=" + id;
            document.getElementById("deleteModal").style.display = "flex";
        }

        function closeDeleteModal() {
            document.getElementById("deleteModal").style.display = "none";
        }
    </script>

    <div id="deleteModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete this blogpost? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
                <a id="deleteLink" href="#">
                    <button class="btn-confirm">Delete</button>
                </a>
            </div>
        </div>
    </div>
</body>

</html>

<!-- http://localhost/train&gain/admin/blogpost/detail.php -->