<?php include("coach_protect.php"); ?>
<?php
// Make sure database connection is included
require_once("../db_connect.php");

// Use coach ID from session (already set in coach_protect.php)
$coach_id = $_SESSION['coach_id'];

// Fetch approved clients assigned to this coach
$query = $conn->prepare("
    SELECT 
        cr.id AS client_id,
        cr.name AS client_name,
        cr.email AS client_email,
        cs.profile_picture AS profile_picture,
        cs.gender AS gender,
        cp.plan AS plan_type,
        pay.status AS payment_status
    FROM client_plan cp
    INNER JOIN client_registered cr ON cp.client_id = cr.id
    LEFT JOIN client_survey cs ON cr.id = cs.client_id
    INNER JOIN client_payment pay ON cr.id = pay.client_id
    WHERE cp.coach_id = ?
      AND pay.status = 'Approved'
");
$query->bind_param("i", $coach_id);
$query->execute();
$result = $query->get_result();

$clients = [];
while ($row = $result->fetch_assoc()) {
    $clients[] = $row;
}

$total_clients = count($clients);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <!-- <ul class="nav-links">
                <li><a href="../../client/blogpost.php">BlogPost</a></li>
                <li><a href="../../client/coach.php">Coach</a></li>
            </ul> -->
            <div class="nav-bottom">
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
                <h2 class="section-title3">Welcome, <?php echo $full_name; ?>!</h2>
                <p class="section-subtitle">
                    <?php
                    if ($total_clients === 0) {
                        echo "You have no client yet.";
                    } elseif ($total_clients === 1) {
                        echo "You have 1 client.";
                    } else {
                        echo "You have $total_clients clients.";
                    }
                    ?>
                </p>
            </div>



            <div class="crud-table-container">
                <table class="crud-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Profile Picture</th>
                            <th>Email</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>View Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total_clients > 0): ?>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($client['client_id']); ?></td>
                                    <td><?php echo htmlspecialchars($client['client_name']); ?></td>
                                    <td>
                                        <?php if (!empty($client['profile_picture'])): ?>
                                            <img src="../uploads/<?php echo htmlspecialchars($client['profile_picture']); ?>" alt="Profile" class="table-profile-pic">
                                        <?php else: ?>
                                            <span>No Image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($client['client_email']); ?></td>
                                    <td><?php echo htmlspecialchars($client['gender'] ?? ''); ?></td>
                                    <td><span class="status-approved">Planned</span></td>
                                    <td><a href="detail.php?id=<?php echo (int)$client['client_id']; ?>" class="btn-view">View Detail</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align:center;">No client has assigned yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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


</body>

</html>

<!-- http://localhost/train&gain/coach/home.php -->