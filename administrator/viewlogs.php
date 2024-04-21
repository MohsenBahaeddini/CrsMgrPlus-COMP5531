<?php
session_start();
require_once "../database.php"; 

// Function to check if the user is an administrator
function isAdmin($conn, $userId) {
    $sql = "SELECT role_id FROM access_role WHERE user_id = ? AND role_id = 1"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

if (!isset($_SESSION['user_id']) || !isAdmin($conn, $_SESSION['user_id'])) {
    echo "Access Denied. You must be an administrator to view this page.";
    exit;
}

// Fetch all logs
$sql = "SELECT * FROM system_logs ORDER BY event_timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Logs</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        table {
            margin: 0px 0px 10px 350px;

        }
    </style>
</head>
<body>
    <section>
    <h1 style="padding: 60px 0 10px 350px">System Logs</h1>
    <table>
        <thead>
            <tr>
                <th>Log ID</th>
                <th>User ID</th>
                <th>Event Type</th>
                <th>Description</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['log_id']) ?></td>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td><?= htmlspecialchars($row['event_type']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['event_timestamp']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No logs found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </section>
</body>
</html>
