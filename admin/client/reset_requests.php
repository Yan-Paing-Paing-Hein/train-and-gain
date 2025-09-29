<?php
require_once("../../db_connect.php");

$result = $conn->query("
    SELECT pr.id, pr.token, pr.requested_at, c.name, c.email
    FROM password_resets pr
    JOIN client_registered c ON pr.client_id = c.id
    WHERE pr.is_used = 0
    ORDER BY pr.requested_at DESC
");
?>

<h2>Password Reset Requests</h2>
<table border="1" cellpadding="8">
    <tr>
        <th>User</th>
        <th>Email</th>
        <th>Requested At</th>
        <th>Reset Link</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['name']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= $row['requested_at']; ?></td>
            <td>
                <input type="text" value="http://localhost/train&gain/client/reset_password.php?token=<?= $row['token']; ?>" readonly style="width:400px;">
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<!-- http://localhost/train&gain/admin/client/reset_requests.php -->