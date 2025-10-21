<?php
// Protect admin access
require_once "../admin_protect.php";

include '../../db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name   = $_POST['name'];
    $specialty   = $_POST['specialty'];
    $experience  = $_POST['experience'];
    $about       = $_POST['bio'];
    $email       = $_POST['email'];
    $phone       = $_POST['phone'];
    $status      = $_POST['status'];



    // Handle image upload
    $imagePath = "";
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "profiles/"; // store in admin/coach/profiles/
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName   = time() . "_" . basename($_FILES["profile_picture"]["name"]);
        $targetFile = $targetDir . $fileName;

        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (in_array($fileType, $allowedTypes) && $_FILES["profile_picture"]["size"] <= 50000000) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                $imagePath = $targetDir . $fileName; // relative path for DB
            }
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO coach (full_name, profile_picture, specialty, experience, about, email, phone_number, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $full_name, $imagePath, $specialty, $experience, $about, $email, $phone, $status);

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
    <title>Create Coach</title>
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
                <h2 class="section-title">Create Coach</h2>
                <p class="section-subtitle">Come train and come gain!</p>
            </div>



            <div class="contact-form-wrapper">
                <form class="contact-form" method="POST" action="create.php" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter coach's name" required>
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                        <small>Allowed formats: JPG, PNG, JPEG, GIF. Max size: 50MB.</small>
                    </div>

                    <div class="form-group">
                        <label for="specialty">Specialty</label>
                        <select id="specialty" name="specialty" required>
                            <option value="" disabled selected>-- Select a Specialty --</option>
                            <option value="Weight Loss">Weight Loss</option>
                            <option value="Muscle Gain">Muscle Gain</option>
                            <option value="Yoga">Yoga</option>
                            <option value="Strength Training">Strength Training</option>
                            <option value="HIIT">HIIT</option>
                            <option value="Endurance">Endurance</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="experience">Experience (Years)</label>
                        <input type="number" id="experience" name="experience" placeholder="e.g., 5" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio / About</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Write something about the coach..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="coach@example.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="e.g., +959123456789" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="" disabled selected>-- Select Status --</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-create btn-upload">Create Coach</button>
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
</body>

</html>

<!-- http://localhost/train&gain/admin/coach/create.php -->