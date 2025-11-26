<?php
include("coach_protect.php");
require_once("../db_connect.php");

// Validate client ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h1 style='text-align:center; margin-top:50px;'>Invalid client ID.</h1>");
}

$client_id = (int)$_GET['id'];
$coach_id = $_SESSION['coach_id'];

// Ensure this client belongs to this coach AND payment approved
$query = $conn->prepare("
    SELECT 
        cr.id AS client_id,
        cr.name,
        cr.email,
        cs.phone,
        cs.gender,
        cs.dob,
        cs.height_cm,
        cs.weight_kg,
        cs.profile_picture,
        cs.medical_notes,
        cs.diet_preference,
        cs.free_time,
        cp.status AS payment_status
    FROM client_registered cr
    JOIN client_survey cs ON cr.id = cs.client_id
    JOIN client_payment cp ON cp.client_id = cr.id
    JOIN client_plan cplan ON cplan.client_id = cr.id
    WHERE cr.id = ? 
      AND cplan.coach_id = ?
      AND cp.status = 'Approved'
    LIMIT 1
");
$query->bind_param("ii", $client_id, $coach_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("<h1 style='text-align:center; margin-top:50px;'>You are not allowed to view this client's details or the payment is not approved.</h1>");
}

$client = $result->fetch_assoc();

// Decode free time JSON
$free_time = json_decode($client['free_time'], true);
$days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];



$storedPath = $client['profile_picture'];
// example stored: client/dashboard/profiles/img.png

// Correct public URL
$publicUrl = '../../train&gain/' . $storedPath;

// Correct server path
$serverPath = __DIR__ . '/../../train&gain/' . $storedPath;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Detail</title>
    <link href="../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        .profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
    </style>
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
            <!-- <ul class="nav-links">
                <li><a href="../../client/blogpost.php">BlogPost</a></li>
                <li><a href="../../client/coach.php">Coach</a></li>
            </ul> -->
            <div class="nav-bottom">
                <a href="home.php" class="cyber-button">Dashboard</a>
                <a href="logout.php" class="cyber-button">Log out</a>
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
            <a href="../payment/create.php" class="cyber-button">Create Payment</a>
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



    <section class="contact fade-up" id="contact">
        <div class="client-detail-container">

            <h2 class="client-detail-title">Client ID.<?php echo htmlspecialchars($client['client_id']); ?> Survey Details</h2>

            <table class="client-detail-table">
                <tbody>
                    <tr>
                        <th>Profile Picture</th>
                        <td style="display: flex; justify-content: center; align-items: center; height: 180px;">
                            <?php
                            $storedPath = $client['profile_picture'];
                            $publicUrl = '../../train&gain/' . $storedPath;
                            $serverPath = __DIR__ . '/../../train&gain/' . $storedPath;
                            ?>

                            <?php if (!empty($storedPath) && is_file($serverPath)): ?>
                                <a href="<?php echo htmlspecialchars($publicUrl); ?>" target="_blank">
                                    <img src="<?php echo htmlspecialchars($publicUrl); ?>" alt="Profile Picture" class="profile-img">
                                </a>
                            <?php else: ?>
                                <span style="color:#f900e0;">Profile picture not found.</span>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Name</th>
                        <td><?php echo htmlspecialchars($client['name']); ?></td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                    </tr>

                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($client['gender']); ?></td>
                    </tr>

                    <tr>
                        <th>Phone Number</th>
                        <td><?php echo htmlspecialchars($client['phone']); ?></td>
                    </tr>

                    <tr>
                        <th>Date of Birth</th>
                        <td><?php echo htmlspecialchars($client['dob']); ?></td>
                    </tr>

                    <tr>
                        <th>Height (cm)</th>
                        <td><?php echo htmlspecialchars($client['height_cm']); ?></td>
                    </tr>

                    <tr>
                        <th>Weight (kg)</th>
                        <td><?php echo htmlspecialchars($client['weight_kg']); ?></td>
                    </tr>

                    <tr>
                        <th>Medical Notes / Restrictions</th>
                        <td>
                            <?php
                            echo !empty($client['medical_notes'])
                                ? nl2br(htmlspecialchars($client['medical_notes']))
                                : "<span style='color:#999;'>No notes provided.</span>";
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <th>Diet Preference</th>
                        <td><?php echo htmlspecialchars($client['diet_preference']); ?></td>
                    </tr>

                    <tr>
                        <th>Weekly Free Time</th>
                        <td>
                            <?php
                            foreach ($days as $day) {
                                $val = isset($free_time[$day]) ? $free_time[$day] : "0";
                                echo "<strong>$day:</strong> " . htmlspecialchars($val) . " hrs<br>";
                            }
                            ?>
                        </td>
                    </tr>

                </tbody>
            </table>

            <br>
            <div style="text-align: center;">
                <a href="create_plan.php?id=<?php echo urlencode($client['client_id']); ?>" class="cyber-button">
                    Create Plan
                </a>
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

    <script src="../js/templatemo-nexus-scripts.js"></script>

    <script>
        document.querySelectorAll(".client-detail-table tr").forEach(row => {
            row.addEventListener("mouseenter", () => {
                row.style.transition = "transform 0.3s ease";
                row.style.transform = "scale(1.02)";
            });
            row.addEventListener("mouseleave", () => {
                row.style.transform = "scale(1)";
            });
        });
    </script>

    <script>
        document.querySelectorAll(".client-detail-table tr").forEach((row, i) => {
            row.style.opacity = 0;
            row.style.transform = "translateX(-20px)";
            setTimeout(() => {
                row.style.transition = "all 0.6s ease";
                row.style.opacity = 1;
                row.style.transform = "translateX(0)";
            }, i * 200);
        });
    </script>

    <script>
        // Approve modal functions
        function openApproveModal() {
            document.getElementById("approveModal").style.display = "flex";
        }

        function closeApproveModal() {
            document.getElementById("approveModal").style.display = "none";
        }

        // Reject modal functions
        function openRejectModal() {
            document.getElementById("rejectModal").style.display = "flex";
        }

        function closeRejectModal() {
            document.getElementById("rejectModal").style.display = "none";
        }
    </script>

</body>

</html>


<!-- http://localhost/train&gain/coach/client_detail.php -->