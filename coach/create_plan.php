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
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        .client-category-container {
            display: flex;
            justify-content: center;
            align-items: stretch;
            gap: 40px;
            margin-top: 40px;
            flex-wrap: wrap;
        }

        .client-category-box {
            flex: 1 1 300px;
            max-width: 400px;
            padding: 30px 20px;
            border: 2px solid #f900e0;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
            clip-path: polygon(20px 0%, 100% 0%, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0% 100%, 0% 20px);
            box-shadow: 0 0 15px rgba(249, 0, 224, 0.6);
            background: rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .client-category-box:hover {
            transform: scale(1.03);
            box-shadow: 0 0 25px rgba(249, 0, 224, 0.8);
        }

        .client-category-box h3 {
            color: #f900e0;
            font-size: 1.6rem;
            margin-bottom: 10px;
            text-shadow: 0 0 8px #f900e0;
        }

        .client-category-box p {
            color: #00ffff;
            font-size: 1rem;
            margin-bottom: 20px;
            text-shadow: 0 0 8px #00ffff;
        }
    </style>
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



    <!-- Contact Section -->
    <section class="contact fade-up" id="contact">
        <div class="contact-container">
            <div class="section-header">
                <h2 class="section-title">Create Plans for Client ID.<?php echo $client_id; ?></h2>
            </div>
        </div>



        <div class="client-category-container">
            <!-- Create Workout Plan -->
            <div class="client-category-box">
                <h3>Create Workout Plan</h3>
                <p>Start creating workout plans for your clients.</p>
                <a href="workout_plan.php?id=<?php echo $client_id; ?>" class="btn-view">Create</a>
            </div>

            <!-- Create Diet Plan -->
            <div class="client-category-box">
                <h3>Create Diet Plan</h3>
                <p>Start creating diet plans for your clients.</p>
                <a href="diet_plan.php?id=<?php echo $client_id; ?>" class="btn-view">Create</a>
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

</body>

</html>