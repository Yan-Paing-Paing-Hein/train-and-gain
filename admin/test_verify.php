<?php
require_once __DIR__ . "/admin_config.php";

$test = 'skibidi';
$ok = password_verify($test, $admin_hashed_password);

echo "Testing password: " . htmlspecialchars($test) . "<br>";
echo "Hash: " . htmlspecialchars($admin_hashed_password) . "<br>";
echo "password_verify result: " . ($ok ? 'MATCH' : 'NO MATCH');
