<?php


function writeToLog($conn, $userId, $eventType, $description) {
    $sql = "INSERT INTO system_logs (user_id, event_type, description) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iss', $userId, $eventType, $description);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return true;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

?>