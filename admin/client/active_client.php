<?php
// Protect admin access
require_once "../admin_protect.php";
require_once("../../db_connect.php");

// Fetch only clients whose payment is approved
$query = $conn->prepare("
    SELECT 
        cr.id, 
        cr.name, 
        cr.email, 
        cr.registered_at
    FROM client_registered cr
    INNER JOIN client_payment cp ON cp.client_id = cr.id
    WHERE cp.status = 'Approved'
    GROUP BY cr.id
    ORDER BY cr.id ASC
");
$query->execute();
$clients = $query->get_result();

// Count approved clients
$total_clients = $clients->num_rows;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Clients</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
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

    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title">Active Clients</h2>
                <p class="section-subtitle">
                    <?php
                    if ($total_clients === 0) {
                        echo "No client is active!";
                    } elseif ($total_clients === 1) {
                        echo "1 client is active!";
                    } else {
                        echo "$total_clients clients are active!";
                    }
                    ?>
                </p>
            </div>
        </div>

        <div class="crud-table-container">
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_clients > 0): ?>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($client['id']); ?></td>
                                <td><?php echo htmlspecialchars($client['name']); ?></td>
                                <td><?php echo htmlspecialchars($client['email']); ?></td>
                                <td><?php echo htmlspecialchars($client['registered_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align:center;">No approved clients found.</td>
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
                <p>&copy; 2025 NexusFlow Systems. All rights reserved.</p>
                <p class="footer-credit">Brought to you by <a href="https://templatemo.com" target="_blank"
                        rel="noopener nofollow">TemplateMo</a></p>
            </div>
        </div>
    </footer>

    <script src="../../js/templatemo-nexus-scripts.js"></script>
</body>

</html>