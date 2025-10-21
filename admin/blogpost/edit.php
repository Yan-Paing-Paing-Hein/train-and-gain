<?php
// Protect admin access
require_once "../admin_protect.php";

include '../../db_connect.php';

// Get blogpost ID from query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p style='color:red;text-align:center;'>Invalid blogpost ID.</p>");
}

$id = intval($_GET['id']);

// Fetch existing blogpost
$stmt = $conn->prepare("SELECT * FROM blogpost WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$blogpost = $result->fetch_assoc();

if (!$blogpost) {
    die("<p style='color:red;text-align:center;'>Blogpost not found.</p>");
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category     = $_POST['category'];
    $title        = $_POST['title'];
    $content      = $_POST['content'];
    $publish_date = $_POST['publish_date'];
    $status       = $_POST['status'];

    // Image handling (robust: uses absolute paths and deletes old file after successful upload)
    $imagePath = $blogpost['blog_image']; // keep DB-stored relative path by default (e.g. "uploads/old.jpg")

    if (isset($_FILES['image']) && isset($_FILES['image']['error']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Relative upload dir (stored in DB / used in HTML) and absolute filesystem dir
        $uploadsDirRel = 'uploads/';                       // relative path stored in DB and used as img src
        $uploadsDirAbs = __DIR__ . '/' . $uploadsDirRel;   // absolute filesystem path to uploads folder

        // Ensure uploads folder exists
        if (!is_dir($uploadsDirAbs)) {
            mkdir($uploadsDirAbs, 0777, true);
        }

        // Original filename and extension
        $originalName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Validate file type & size
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedTypes)) {
            echo "<p style='color:red;text-align:center;'>Invalid image format. Allowed: JPG, PNG, GIF.</p>";
        } elseif ($_FILES['image']['size'] > 50000000) {
            echo "<p style='color:red;text-align:center;'>Image too large. Max size: 50MB.</p>";
        } else {
            // Sanitize filename and prepare target paths
            $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
            $fileName = time() . '_' . $safeName;
            $targetFileAbs = $uploadsDirAbs . $fileName;   // absolute path where file will be saved
            $targetFileRel = $uploadsDirRel . $fileName;   // relative path saved to DB and used in img src

            // Move uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFileAbs)) {
                // Delete old image if it exists and is not the same file
                if (!empty($blogpost['blog_image'])) {
                    $oldRel = $blogpost['blog_image']; // could be relative (uploads/old.jpg) or absolute
                    // Build absolute path for old file safely:
                    if (preg_match('#^(?:[A-Za-z]:\\\\|/)#', $oldRel)) {
                        // Absolute path (starts with C:\ or /)
                        $oldAbs = $oldRel;
                    } else {
                        // Treat as relative to this script folder (admin/blogpost)
                        $oldAbs = __DIR__ . '/' . ltrim($oldRel, '/');
                    }

                    // Ensure we don't accidentally unlink current uploading file and that file exists
                    if (file_exists($oldAbs) && is_file($oldAbs) && realpath($oldAbs) !== realpath($targetFileAbs)) {
                        @unlink($oldAbs); // suppress warning; will fail silently if permission issue
                    }
                }

                // Use the new relative path for DB update
                $imagePath = $targetFileRel;
            } else {
                echo "<p style='color:red;text-align:center;'>Failed to move uploaded file.</p>";
            }
        }
    }

    // Update query
    $stmt = $conn->prepare("UPDATE blogpost SET category=?, title=?, content=?, blog_image=?, publish_date=?, status=? WHERE id=?");
    $stmt->bind_param("ssssssi", $category, $title, $content, $imagePath, $publish_date, $status, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;text-align:center;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blogpost</title>
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
            </ul>
            <div class="nav-bottom">
                <a href="../blogpost/index.php" class="cyber-button">Blogpost Table</a>
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
                <h2 class="section-title">Edit Blogpost</h2>
                <p class="section-subtitle">Come train and come gain!</p>
            </div>



            <div class="contact-form-wrapper">
                <form class="contact-form" method="POST" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <option value="Weight Loss" <?php if ($blogpost['category'] == "Weight Loss") echo "selected"; ?>>Weight Loss</option>
                            <option value="Muscle Gain" <?php if ($blogpost['category'] == "Muscle Gain") echo "selected"; ?>>Muscle Gain</option>
                            <option value="Yoga" <?php if ($blogpost['category'] == "Yoga") echo "selected"; ?>>Yoga</option>
                            <option value="Strength Training" <?php if ($blogpost['category'] == "Strength Training") echo "selected"; ?>>Strength Training</option>
                            <option value="HIIT" <?php if ($blogpost['category'] == "HIIT") echo "selected"; ?>>HIIT</option>
                            <option value="Endurance" <?php if ($blogpost['category'] == "Endurance") echo "selected"; ?>>Endurance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($blogpost['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="content">Content</label>
                        <textarea id="content" name="content" rows="5" required><?php echo htmlspecialchars($blogpost['content']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="image">Upload Blog Image (Leave empty to keep current)</label>
                        <input type="file" id="image" name="image" accept="image/*">

                        <?php if (!empty($blogpost['blog_image'])): ?>
                            <div class="current-image-box">
                                <p class="current-image-label">Current Image</p>
                                <img src="<?php echo $blogpost['blog_image']; ?>"
                                    alt="Current Image"
                                    class="current-image-thumb"
                                    onclick="openImageModal(this.src)">
                            </div>
                        <?php endif; ?>

                        <small>Allowed formats: JPG, PNG, JPEG, GIF. Max size: 50MB.</small>
                    </div>

                    <div class="form-group">
                        <label for="publish_date">Publish Date</label>
                        <input type="date" id="publish_date" name="publish_date" value="<?php echo $blogpost['publish_date']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Draft" <?php if ($blogpost['status'] == "Draft") echo "selected"; ?>>Draft</option>
                            <option value="Published" <?php if ($blogpost['status'] == "Published") echo "selected"; ?>>Published</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-create btn-upload">Update Blogpost</button>

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

    <script src="../../js/templatemo-nexus-scripts.js"></script>

    <script>
        function openImageModal(src) {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");

            if (modal && modal.parentNode !== document.body) {
                document.body.appendChild(modal);
            }

            modalImg.src = src;
            modal.style.display = "flex";

            // Force reflow to restart animation
            modal.classList.remove("show");
            void modal.offsetWidth;
            modal.classList.add("show");

            document.documentElement.style.overflow = "hidden";
            document.body.style.overflow = "hidden";

            modal.addEventListener('click', function onOverlayClick(e) {
                if (e.target === modal) {
                    modal.removeEventListener('click', onOverlayClick);
                    closeImageModal();
                }
            }, {
                once: true
            });

            document.addEventListener('keydown', escHandler);

            function escHandler(e) {
                if (e.key === 'Escape') {
                    document.removeEventListener('keydown', escHandler);
                    closeImageModal();
                }
            }
        }

        function closeImageModal() {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");
            if (!modal) return;

            modal.style.display = "none";
            modalImg.src = "";

            // Restore scrolling
            document.documentElement.style.overflow = "";
            document.body.style.overflow = "";
        }
    </script>

    <!-- Image Modal (hidden by default) -->
    <div id="imageModal" class="image-modal">
        <span class="close-btn" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage" alt="Full size image">
    </div>
</body>

</html>

<!-- http://localhost/train&gain/admin/blogpost/edit.php -->