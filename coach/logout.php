<?php
session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. Destroy the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Finally destroy the session
session_destroy();

// 4. Send headers to prevent caching (so back button won't reopen old pages)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// 5. Redirect to login form
header("Location: login_form.php");
exit();
