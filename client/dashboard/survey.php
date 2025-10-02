<?php
session_start();
require_once("../../db_connect.php");

if (!isset($_SESSION['client_id'])) {
    header("Location: ../login_form.php?error=Please log in first.");
    exit();
}

$client_id = $_SESSION['client_id'];
$survey = null;

// Check if client has completed all 3 steps
$process = $conn->prepare("SELECT survey_completed, plan_selected, payment_done 
                           FROM client_process WHERE client_id = ?");
$process->bind_param("i", $client_id);
$process->execute();
$processResult = $process->get_result();
$processData = $processResult->fetch_assoc();
$process->close();

if (
    $processData && $processData['survey_completed'] == 1
    && $processData['plan_selected'] == 1
    && $processData['payment_done'] == 1
) {
    // All steps completed → restrict access
    echo "<h1 style='text-align:center; margin-top:50px;'>You have already completed all steps. Survey cannot be modified anymore.</h1>";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM client_survey WHERE client_id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $survey = $result->fetch_assoc();
}
$stmt->close();

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Form</title>
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
                <li><a href="../../client/blogpost.php">BlogPost</a></li>
                <li><a href="../../client/coach.php">Coach</a></li>
            </ul>
            <div class="nav-bottom">
                <a href="../dashboard/welcome.php" class="cyber-button">Dashboard</a>
                <a href="../../client/logout.php" class="cyber-button">Log out</a>
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
                <h2 class="section-title3">Step 1: Survey Form</h2>
                <p class="section-subtitle">Please fill your personal fitness information.</p>
            </div>



            <div class="contact-form-wrapper">
                <form class="contact-form" action="survey_action.php" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="action" value="<?php echo $survey ? 'update' : 'insert'; ?>">

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo $survey['phone'] ?? ''; ?>" placeholder="e.g., +959123456789" required>
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">-- Select --</option>
                            <option value="Male" <?php if (($survey['gender'] ?? '') === 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if (($survey['gender'] ?? '') === 'Female') echo 'selected'; ?>>Female</option>
                            <option value="Other" <?php if (($survey['gender'] ?? '') === 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo $survey['dob'] ?? ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Height (cm)</label>
                        <input type="number" name="height_cm" value="<?php echo $survey['height_cm'] ?? ''; ?>" placeholder="Enter your height" required>
                    </div>

                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input type="number" name="weight_kg" value="<?php echo $survey['weight_kg'] ?? ''; ?>" placeholder="Enter your weight" required>
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>

                        <?php if (!empty($survey['profile_picture'])): ?>
                            <div class="current-image-box">
                                <p class="current-image-label">Current Profile Picture</p>
                                <img src="../../<?php echo htmlspecialchars($survey['profile_picture']); ?>"
                                    alt="Current Image"
                                    class="current-image-thumb"
                                    onclick="openImageModal(this.src)">
                                <br>
                                <small>Upload new to replace:</small><br>
                            </div>
                        <?php endif; ?>
                        <br>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                            <?php echo $survey ? '' : 'required'; ?>>
                        <small>Allowed formats: JPG, PNG, JPEG, GIF. Max size: 50MB.</small>
                    </div>

                    <div class="form-group">
                        <label>Medical Notes / Restrictions</label>
                        <textarea name="medical_notes"><?php echo $survey['medical_notes'] ?? ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Diet Preference</label>
                        <?php
                        $prefs = ['None', 'Vegetarian', 'Vegan', 'Keto', 'Halal'];
                        $storedPref = $survey['diet_preference'] ?? '';
                        $dropdownValue = $storedPref;
                        $otherValue = "";

                        // If combined format exists (Dropdown | Other)
                        if (strpos($storedPref, '|') !== false) {
                            [$dropdownValue, $otherValue] = array_map('trim', explode('|', $storedPref, 2));
                        }
                        ?>
                        <select name="diet_preference">
                            <?php foreach ($prefs as $p): ?>
                                <option value="<?php echo $p; ?>" <?php if ($dropdownValue === $p) echo 'selected'; ?>>
                                    <?php echo $p; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <br><br>
                        <input type="text" name="diet_other" placeholder="If Other, specify"
                            value="<?php echo htmlspecialchars($otherValue); ?>">
                    </div>

                    <div class="form-group">
                        <h3>Weekly Free Time (hours per day)</h3>
                        <?php
                        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $freeTimeData = $survey ? json_decode($survey['free_time'], true) : [];
                        foreach ($days as $day) {
                            $val = $freeTimeData[$day] ?? 0;
                            echo "<label>$day:</label> <input type='number' name='free_time[$day]' min='0' max='24' value='$val'><br>";
                        }
                        ?>
                    </div>

                    <button type="submit" class="btn-create btn-upload"><?php echo $survey ? 'Update Survey' : 'Submit Survey'; ?></button>
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
        document.addEventListener("DOMContentLoaded", function() {
            const title = document.querySelector(".section-title3");
            const text = title.textContent.trim();
            title.textContent = ""; // clear original text

            text.split("").forEach((char, i) => {
                const span = document.createElement("span");
                span.textContent = char === " " ? "\u00A0" : char; // preserve spaces
                span.style.setProperty("--delay", `${i * 0.05}s`);
                title.appendChild(span);
            });
        });
    </script>

    <!-- Javascript for popup animation -->
    <script>
        function openImageModal(src) {
            var modal = document.getElementById("imageModal");
            var modalImg = document.getElementById("modalImage");

            if (modal && modal.parentNode !== document.body) {
                document.body.appendChild(modal);
            }

            modalImg.src = src;
            modal.style.display = "flex";

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

<!-- http://localhost/train&gain/client/dashboard/survey.php -->