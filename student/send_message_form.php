<?php
// Start the session and include necessary files
session_start();
require_once '../includes/header.php'; // Adjust to your file structure


$currentUserId = $_SESSION['user_id']; // The ID of the current user
$receiverId = $_POST['receiver_id'] ?? $_GET['receiver_id'] ?? null;

// Insert a message into the database if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $messageContent = mysqli_real_escape_string($conn, $_POST['message_content']);
    $sqlInsertMessage = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content) VALUES (?, ?, ?)";
    $stmtInsertMessage = mysqli_prepare($conn, $sqlInsertMessage);
    mysqli_stmt_bind_param($stmtInsertMessage, 'iis', $currentUserId, $receiverId, $messageContent);
    if (mysqli_stmt_execute($stmtInsertMessage)) {
        // Redirect to prevent form resubmission
        header("Location: send_message_form.php?receiver_id=" . urlencode($receiverId));
        exit;
    } else {
        echo "<p>Error sending message: " . mysqli_error($conn) . "</p>";
    }
    mysqli_stmt_close($stmtInsertMessage);
}

// Fetch message history
$sqlMessages = "SELECT * FROM messages WHERE (sender_user_id = ? AND receiver_user_id = ?) OR (receiver_user_id = ? AND sender_user_id = ?) ORDER BY message_date ASC";
$stmtMessages = mysqli_prepare($conn, $sqlMessages);
mysqli_stmt_bind_param($stmtMessages, 'iiii', $currentUserId, $receiverId, $currentUserId, $receiverId);
mysqli_stmt_execute($stmtMessages);
$resultMessages = mysqli_stmt_get_result($stmtMessages);
$messages = mysqli_fetch_all($resultMessages, MYSQLI_ASSOC);
mysqli_stmt_close($stmtMessages);

// Fetch receiver info
if ($receiverId) {
    $sqlReceiverInfo = "SELECT first_name, last_name FROM users WHERE ID = ?";
    $stmtReceiverInfo = mysqli_prepare($conn, $sqlReceiverInfo);
    mysqli_stmt_bind_param($stmtReceiverInfo, 'i', $receiverId);
    mysqli_stmt_execute($stmtReceiverInfo);
    $resultReceiverInfo = mysqli_stmt_get_result($stmtReceiverInfo);
    $receiverInfo = mysqli_fetch_assoc($resultReceiverInfo);
    mysqli_stmt_close($stmtReceiverInfo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Message</title>
    <!-- Include your CSS here -->
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .message-history {
            margin-bottom: 20px;
        }
        .message {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
        .message p {
            margin: 0;
        }
        .message span {
            font-size: 0.8em;
            color: #666;
        }
        form {
            margin-top: 20px;
        }
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #333;
            border-radius: 5px;
            color: white;
            border: none;
            cursor: pointer;
        }
        .messages {
            margin: 0 auto;
            padding: 60px;
        }
    </style>
</head>
<body>
    <div class="messages">       
    <h1 style="padding-bottom: 10px">Message History</h1>

    <!-- Display message history -->
    <div class="message-history">
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <strong><?= $message['sender_user_id'] == $currentUserId ? 'You' : htmlspecialchars($receiverInfo['first_name'] . ' ' . $receiverInfo['last_name']) ?>:</strong>
                <p><?= htmlspecialchars($message['message_content']) ?></p>
                <span><?= date('M d, Y h:i A', strtotime($message['message_date'])) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Message form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiverId) ?>">
        <textarea name="message_content" id="message_content" required></textarea>
        <input type="submit" name="send_message" value="Send Message">
    </form>
    </div>
</body>
</html>
