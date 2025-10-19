<?php

require_once("../../db_connect.php");

// Get payment ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid payment ID.");
}
$payment_id = intval($_GET['id']);


// Handle admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_message = isset($_POST['admin_message']) ? trim($_POST['admin_message']) : '';

    // Approve action
    if (isset($_POST['approve'])) {
        $conn->begin_transaction();
        try {
            // Update payment status and message (if any)
            $stmt = $conn->prepare("UPDATE client_payment SET status='Approved', admin_message=? WHERE id=?");
            $stmt->bind_param("si", $admin_message, $payment_id);
            $stmt->execute();
            $stmt->close();

            // Get client_id to update client_process
            $getClient = $conn->prepare("SELECT client_id FROM client_payment WHERE id=?");
            $getClient->bind_param("i", $payment_id);
            $getClient->execute();
            $res = $getClient->get_result();
            $client = $res->fetch_assoc();
            $getClient->close();

            if ($client) {
                // When approved, mark payment_done=1
                $updateProcess = $conn->prepare("UPDATE client_process SET payment_done=1 WHERE client_id=?");
                $updateProcess->bind_param("i", $client['client_id']);
                $updateProcess->execute();
                $updateProcess->close();
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            die("Error: " . $e->getMessage());
        }

        // Redirect to refresh
        header("Location: detail.php?id=$payment_id&updated=1");
        exit;
    }

    // Reject action
    if (isset($_POST['reject'])) {
        $conn->begin_transaction();
        try {
            // Update payment status and admin message
            $stmt = $conn->prepare("UPDATE client_payment SET status='Rejected', admin_message=? WHERE id=?");
            $stmt->bind_param("si", $admin_message, $payment_id);
            $stmt->execute();
            $stmt->close();

            // Get client_id to update client_process
            $getClient = $conn->prepare("SELECT client_id FROM client_payment WHERE id=?");
            $getClient->bind_param("i", $payment_id);
            $getClient->execute();
            $res = $getClient->get_result();
            $client = $res->fetch_assoc();
            $getClient->close();

            if ($client) {
                // When rejected, mark payment_done=0
                $updateProcess = $conn->prepare("UPDATE client_process SET payment_done=0 WHERE client_id=?");
                $updateProcess->bind_param("i", $client['client_id']);
                $updateProcess->execute();
                $updateProcess->close();
            }

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            die("Error: " . $e->getMessage());
        }

        // Redirect to refresh
        header("Location: detail.php?id=$payment_id&updated=1");
        exit;
    }
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
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Index</title>
    <link href="../../css/templatemo-nexus-style.css" rel="stylesheet">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
        }

        /* ===== Base Modal Box (Glassmorphism) ===== */
        .modal-box {
            background: rgba(26, 26, 26, 0.55);
            color: #00ffff;
            padding: 25px;
            border-radius: 16px;
            width: 400px;
            text-align: center;
            backdrop-filter: blur(15px) saturate(160%);
            -webkit-backdrop-filter: blur(15px) saturate(160%);
            box-shadow:
                0 0 20px rgba(0, 0, 0, 0.8),
                inset 0 0 10px rgba(255, 255, 255, 0.05);
            animation: fadeIn 0.3s ease;
        }

        /* ===== APPROVE MODAL ===== */
        .approve-box {
            border: 2px solid #f900e0;
            box-shadow:
                0 0 15px rgba(249, 0, 224, 0.8),
                0 0 30px rgba(249, 0, 224, 0.6),
                0 0 45px rgba(249, 0, 224, 0.4);
            animation: fadeIn 0.3s ease, neonGlowPink 2s infinite alternate;
        }

        /* ===== REJECT MODAL ===== */
        .reject-box {
            border: 2px solid #ff0000;
            box-shadow:
                0 0 15px rgba(255, 0, 0, 0.8),
                0 0 30px rgba(255, 0, 0, 0.6),
                0 0 45px rgba(255, 0, 0, 0.4);
            animation: fadeIn 0.3s ease, neonGlowRed 2s infinite alternate;
        }

        /* ===== Titles and Text ===== */
        .modal-box h3 {
            margin-bottom: 15px;
            font-size: 1.3rem;
            color: #f900e0;
            text-shadow: 0 0 8px #f900e0;
        }

        .modal-box p {
            color: #00ffff;
            margin-bottom: 20px;
        }

        /* ===== Buttons Section ===== */
        .modal-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn-cancel,
        .btn-confirm {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s ease;
        }

        /* Cancel Button */
        .btn-cancel {
            background: rgba(85, 85, 85, 0.7);
            color: #fff;
            backdrop-filter: blur(5px);
        }

        .btn-cancel:hover {
            background: rgba(119, 119, 119, 0.8);
        }

        /* Confirm Button */
        .btn-confirm {
            background: rgba(0, 255, 255, 0.85);
            color: #000;
            backdrop-filter: blur(5px);
        }

        .btn-confirm:hover {
            background: #f900e0;
            color: #000;
            box-shadow: 0 0 15px #f900e0;
        }

        .btn-confirm2 {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s ease;
            background: #B60000;
            color: #fff;
            backdrop-filter: blur(5px);
        }

        .btn-confirm2:hover {
            background: #ff0000;
            color: #000;
            box-shadow: 0 0 15px #ff0000;
        }

        /* ===== Animations ===== */
        @keyframes fadeIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ===== Neon Glow Animations ===== */
        @keyframes neonGlowPink {
            0% {
                box-shadow:
                    0 0 10px rgba(249, 0, 224, 0.6),
                    0 0 20px rgba(249, 0, 224, 0.4),
                    0 0 30px rgba(249, 0, 224, 0.2);
            }

            100% {
                box-shadow:
                    0 0 20px rgba(249, 0, 224, 1),
                    0 0 40px rgba(249, 0, 224, 0.8),
                    0 0 60px rgba(249, 0, 224, 0.6);
            }
        }

        @keyframes neonGlowRed {
            0% {
                box-shadow:
                    0 0 10px rgba(255, 0, 0, 0.6),
                    0 0 20px rgba(255, 0, 0, 0.4),
                    0 0 30px rgba(255, 0, 0, 0.2);
            }

            100% {
                box-shadow:
                    0 0 20px rgba(255, 0, 0, 1),
                    0 0 40px rgba(255, 0, 0, 0.8),
                    0 0 60px rgba(255, 0, 0, 0.6);
            }
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
                        <th>Paid at</th>
                        <td><?php echo htmlspecialchars($payment['created_at']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
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

                            <?php if ($payment['status'] === 'Pending'): ?>
                                <!-- Show textarea and buttons only when still pending -->
                                <form method="POST" action="">
                                    <textarea name="admin_message" rows="4" cols="50" placeholder="Type admin message here..."><?php echo htmlspecialchars($payment['admin_message']); ?></textarea>
                                    <br><br>
                                    <div class="action-bar">
                                        <div class="action-left">
                                            <button type="button" class="btn-approve" onclick="openApproveModal()">Approve</button>
                                        </div>
                                        <div class="action-right">
                                            <button type="button" class="btn-reject" onclick="openRejectModal()">Reject</button>
                                        </div>
                                    </div>

                                    <!-- Approve Modal -->
                                    <div id="approveModal" class="modal-overlay">
                                        <div class="modal-box approve-box">
                                            <h3>Confirm Approval</h3>
                                            <p>Are you sure you want to approve this payment?</p>
                                            <div class="modal-actions">
                                                <button type="button" class="btn-cancel" onclick="closeApproveModal()">Cancel</button>
                                                <button type="submit" name="approve" class="btn-confirm">Approve</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div id="rejectModal" class="modal-overlay">
                                        <div class="modal-box reject-box">
                                            <h3>Confirm Rejection</h3>
                                            <p>Are you sure you want to reject this payment?</p>
                                            <div class="modal-actions">
                                                <button type="button" class="btn-cancel" onclick="closeRejectModal()">Cancel</button>
                                                <button type="submit" name="reject" class="btn-confirm2">Reject</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            <?php else: ?>
                                <!-- Once approved/rejected, show admin message as plain text -->
                                <?php if (!empty($payment['admin_message'])): ?>
                                    <p style="color:#00ffff;">
                                        <?php echo nl2br(htmlspecialchars($payment['admin_message'])); ?>
                                    </p>
                                <?php else: ?>
                                    <p style="color:#808080;">(No admin message was provided.)</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
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


<!-- http://localhost/train&gain/admin/payment/detail.php -->