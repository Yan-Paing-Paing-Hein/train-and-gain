<?php
include '../../db_connect.php';

// Get coach ID from query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<p style='color:red;text-align:center;'>Invalid coach ID.</p>");
}

$id = intval($_GET['id']);

// Fetch existing coach
$stmt = $conn->prepare("SELECT * FROM coach WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$coach = $result->fetch_assoc();

if (!$coach) {
    die("<p style='color:red;text-align:center;'>Coach not found.</p>");
}
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name     = $_POST['full_name'];
    $specialty     = $_POST['specialty'];
    $experience    = $_POST['experience'];
    $about         = $_POST['about'];
    $email         = $_POST['email'];
    $phone_number  = $_POST['phone_number'];
    $status        = $_POST['status'];

    // Image handling
    $imagePath = $coach['profile_picture']; // keep old image by default

    if (isset($_FILES['profile_picture']) && isset($_FILES['profile_picture']['error']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadsDirRel = 'profiles/';                     // relative path saved to DB
        $uploadsDirAbs = __DIR__ . '/' . $uploadsDirRel;  // absolute path on server

        if (!is_dir($uploadsDirAbs)) {
            mkdir($uploadsDirAbs, 0777, true);
        }

        $originalName = basename($_FILES['profile_picture']['name']);
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedTypes)) {
            echo "<p style='color:red;text-align:center;'>Invalid image format. Allowed: JPG, PNG, GIF.</p>";
        } elseif ($_FILES['profile_picture']['size'] > 50000000) {
            echo "<p style='color:red;text-align:center;'>Image too large. Max size: 50MB.</p>";
        } else {
            $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
            $fileName = time() . '_' . $safeName;
            $targetFileAbs = $uploadsDirAbs . $fileName;
            $targetFileRel = $uploadsDirRel . $fileName;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFileAbs)) {
                // Delete old image
                if (!empty($coach['profile_picture'])) {
                    $oldRel = $coach['profile_picture'];

                    if (preg_match('#^(?:[A-Za-z]:\\\\|/)#', $oldRel)) {
                        $oldAbs = $oldRel;
                    } else {
                        $oldAbs = __DIR__ . '/' . ltrim($oldRel, '/');
                    }

                    if (file_exists($oldAbs) && is_file($oldAbs) && realpath($oldAbs) !== realpath($targetFileAbs)) {
                        @unlink($oldAbs);
                    }
                }

                $imagePath = $targetFileRel;
            } else {
                echo "<p style='color:red;text-align:center;'>Failed to move uploaded file.</p>";
            }
        }
    }

    // Update query
    $stmt = $conn->prepare("UPDATE coach 
        SET full_name=?, profile_picture=?, specialty=?, experience=?, about=?, email=?, phone_number=?, status=? 
        WHERE id=?");
    $stmt->bind_param("sssissssi", $full_name, $imagePath, $specialty, $experience, $about, $email, $phone_number, $status, $id);

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
    <title>Edit Coach</title>
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
                <a href="../coach/index.php" class="cyber-button">Coach Table</a>
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
            <a href="#" class="cyber-button">Coach Table</a>
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
                <h2 class="section-title">Edit Coach</h2>
                <p class="section-subtitle">Come train and come gain!</p>
            </div>

            <div class="contact-form-wrapper">
                <form class="contact-form" method="POST" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($coach['full_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="specialty">Specialty</label>
                        <select id="specialty" name="specialty" required>
                            <option value="Weight Loss" <?php if ($coach['specialty'] == "Weight Loss") echo "selected"; ?>>Weight Loss</option>
                            <option value="Muscle Gain" <?php if ($coach['specialty'] == "Muscle Gain") echo "selected"; ?>>Muscle Gain</option>
                            <option value="Yoga" <?php if ($coach['specialty'] == "Yoga") echo "selected"; ?>>Yoga</option>
                            <option value="Strength Training" <?php if ($coach['specialty'] == "Strength Training") echo "selected"; ?>>Strength Training</option>
                            <option value="HIIT" <?php if ($coach['specialty'] == "HIIT") echo "selected"; ?>>HIIT</option>
                            <option value="Endurance" <?php if ($coach['specialty'] == "Endurance") echo "selected"; ?>>Endurance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience (Years)</label>
                        <input type="number" id="experience" name="experience" value="<?php echo htmlspecialchars($coach['experience']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="about">About</label>
                        <textarea id="about" name="about" rows="5" required><?php echo htmlspecialchars($coach['about']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($coach['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($coach['phone_number']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Upload Profile Picture (Leave empty to keep current)</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*">

                        <?php if (!empty($coach['profile_picture'])): ?>
                            <div class="current-image-box">
                                <p class="current-image-label">Current Profile Picture</p>
                                <img src="<?php echo $coach['profile_picture']; ?>"
                                    alt="Current Image"
                                    class="current-image-thumb"
                                    onclick="openImageModal(this.src)">
                            </div>
                        <?php endif; ?>

                        <small>Allowed formats: JPG, PNG, JPEG, GIF. Max size: 50MB.</small>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="Active" <?php if ($coach['status'] == "Active") echo "selected"; ?>>Active</option>
                            <option value="Inactive" <?php if ($coach['status'] == "Inactive") echo "selected"; ?>>Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-create btn-upload">Update Coach</button>
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

<!-- http://localhost/train&gain/admin/coach/edit.php -->