<?php
session_name("admin_session");
session_start();

// Invalidate cache for this request
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Destroy session completely
session_unset();
session_destroy();

// Prevent back button caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Redirect to login
header("Location: login_form.php");
exit();
