<?php
// Protect admin access
require_once "../admin_protect.php";

require_once("../../db_connect.php");

// Fetch all client payments
$query = "SELECT id, client_id, plan_type, payment_method, status, created_at FROM client_payment ORDER BY id ASC";
$result = $conn->query($query);
$payments = $result->fetch_all(MYSQLI_ASSOC);
$total_payments = count($payments);
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
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title">Client Payments</h2>
                <p class="section-subtitle">
                    <?php
                    if ($total_payments === 0) {
                        echo "No payment has been received.";
                    } elseif ($total_payments === 1) {
                        echo "1 payment has been recorded.";
                    } else {
                        echo "$total_payments payments have been recorded.";
                    }
                    ?>
                </p>
            </div>
        </div>

        <div class="crud-table-container">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Client ID</th>
                        <th>Plan Type</th>
                        <th>Payment Method</th>
                        <th>Paid at</th>
                        <th>Status</th>
                        <th>View Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_payments > 0): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['client_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['plan_type']); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($payment['created_at']); ?></td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($payment['status']) {
                                        case 'Approved':
                                            $statusClass = 'status-approved';
                                            break;
                                        case 'Rejected':
                                            $statusClass = 'status-rejected';
                                            break;
                                        default:
                                            $statusClass = 'status-pending';
                                            break;
                                    }
                                    ?>
                                    <span class="<?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($payment['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="detail.php?id=<?php echo $payment['id']; ?>" class="btn-view">View Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No payment has been received.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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


<!-- http://localhost/train&gain/admin/payment/index.php -->