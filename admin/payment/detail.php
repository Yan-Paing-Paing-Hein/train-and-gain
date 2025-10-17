<?php

require_once("../../db_connect.php");

// Get payment ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid payment ID.");
}
$payment_id = intval($_GET['id']);

// Handle admin message form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_message = trim($_POST['admin_message']);
    $update_query = "UPDATE client_payment SET admin_message = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $admin_message, $payment_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh page after updating
    header("Location: detail.php?id=$payment_id");
    exit;
}

// Fetch joined data from both tables
$query = "
    SELECT 
        p.id AS payment_id,
        c.id AS client_id,
        c.name,
        c.email,
        p.plan_type,
        p.payment_method,
        p.status,
        p.created_at,
        p.screenshot_path,
        p.admin_message
    FROM client_payment p
    JOIN client_registered c ON p.client_id = c.id
    WHERE p.id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment = $result->fetch_assoc();
$stmt->close();

if (!$payment) {
    die("Payment record not found.");
}


// require_once("../../db_connect.php");

// Fetch all client payments
// $query = "SELECT id, client_id, plan_type, payment_method, status, created_at FROM client_payment ORDER BY id ASC";
// $result = $conn->query($query);
// $payments = $result->fetch_all(MYSQLI_ASSOC);
// $total_payments = count($payments);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Index</title>
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
                <a href="../payment/index.php" class="cyber-button">View All Payments</a>
            </div>
            <!-- <div class="nav-bottom">
                <a href="../payment/create.php" class="cyber-button">Create Payment</a>
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
            </ul>
        </nav>
    </div>



    <section class="contact fade-up" id="contact">
        <div class="client-detail-container">
            <h2 class="client-detail-title">Detail of Payment ID.<?php echo htmlspecialchars($payment['payment_id']); ?></h2>
            <table class="client-detail-table">
                <tbody>
                    <tr>
                        <th>Client ID</th>
                        <td><?php echo htmlspecialchars($payment['client_id']); ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?php echo htmlspecialchars($payment['name']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($payment['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Plan Type</th>
                        <td><?php echo htmlspecialchars($payment['plan_type']); ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><?php echo htmlspecialchars($payment['status']); ?></td>
                    </tr>
                    <tr>
                        <th>Paid at</th>
                        <td><?php echo htmlspecialchars($payment['created_at']); ?></td>
                    </tr>
                    <tr>
                        <th>Screenshot</th>
                        <td>
                            <?php
                            $storedPath = $payment['screenshot_path'];
                            $publicUrl = '../../' . $storedPath;
                            $serverPath = __DIR__ . '/../../' . $storedPath;
                            if (is_file($serverPath)) {

                                echo '<a href="' . htmlspecialchars($publicUrl) . '" target="_blank" rel="noopener noreferrer">';
                                echo '<img src="' . htmlspecialchars($publicUrl) . '" alt="Payment Screenshot" width="200">';
                                echo '</a>';
                            } else {
                                echo '<span style="color:#f900e0;">Screenshot not found.</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Admin Message</th>
                        <td class="form-group">
                            <form method="POST" action="">
                                <textarea name="admin_message" rows="4" cols="50" placeholder="Type admin message here..." required><?php echo htmlspecialchars($payment['admin_message']); ?></textarea>
                                <br>
                                <!-- <button type="submit" class="cyber-button">Save Message</button> -->
                            </form>
                            <?php if (isset($_GET['updated'])): ?>
                                <p style="color:#00ffff;">Admin message updated successfully.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><br><br><br><br>

        <div class="action-bar fade-up">

            <div class="action-left">
                <a href="#">
                    <button class="btn-edit">Approve</button>
                </a>
            </div>

            <div class="action-right">
                <button class="btn-delete">Reject</button>
            </div>

        </div>
    </section>




    <!-- <section class="contact fade-up" id="contact">

        <div class="client-detail-container">
            <h2 class="client-detail-title">Detail of Payment ID.2</h2>
            <table class="client-detail-table">
                <tbody>
                    <tr>
                        <th>Client ID</th>
                        <td>xyz</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>xyz</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>xyz</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><br><br><br><br>

        <div class="action-bar fade-up">

            <div class="action-left">
                <a href="#">
                    <button class="btn-edit">Approve</button>
                </a>
            </div>

            <div class="action-right">
                <button class="btn-delete">Reject</button>
            </div>

        </div>

    </section> -->



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

</body>

</html>


<!-- http://localhost/train&gain/admin/payment/detail.php -->