<?php
// ==========================
// Admin Access Protection
// ==========================

// Prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header("HTTP/1.1 403 Forbidden");
    exit("<h1>Access Denied</h1>");
}

session_start();

// Strong no-cache headers
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// If not logged in, redirect
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Use an absolute path (relative to project root)
    header("Location: /train&gain/admin/login_form.php?loginfirst=1");
    exit();
}
?>

<script>
    window.addEventListener("pageshow", function(event) {
        if (event.persisted) {
            // Force reload if coming from browser back cache
            window.location.reload();
        }
    });
</script>