<?php
session_start();
require("../includes/header.php");
require_once '../database.php';

if (!isset($_SESSION['user_id'])) {
    exit('You must be logged in as a professor to send messages.');
}

$professorUserId = $_SESSION['user_id'];
$receiverId = $_GET['receiver_id'] ?? ($_POST['receiver_id'] ?? null);

// Insert a message into the database if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $messageContent = mysqli_real_escape_string($conn, $_POST['message_content']);
    $sqlInsertMessage = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content) VALUES (?, ?, ?)";
    $stmtInsertMessage = mysqli_prepare($conn, $sqlInsertMessage);
    mysqli_stmt_bind_param($stmtInsertMessage, 'iis', $professorUserId, $receiverId, $messageContent);
    if (mysqli_stmt_execute($stmtInsertMessage)) {
        mysqli_stmt_close($stmtInsertMessage);
        // Redirect to prevent form resubmission and append receiver_id to GET parameters
        header("Location: prof_send_message_form.php?receiver_id=" . urlencode($receiverId));
        exit;
    } else {
        echo "<p>Error sending message: " . mysqli_error($conn) . "</p>";
    }
}

// Fetch message history
$sqlMessages = "SELECT * FROM messages WHERE (sender_user_id = ? AND receiver_user_id = ?) OR (receiver_user_id = ? AND sender_user_id = ?) ORDER BY message_date ASC";
$stmtMessages = mysqli_prepare($conn, $sqlMessages);
mysqli_stmt_bind_param($stmtMessages, 'iiii', $professorUserId, $receiverId, $professorUserId, $receiverId);
mysqli_stmt_execute($stmtMessages);
$resultMessages = mysqli_stmt_get_result($stmtMessages);
$messages = mysqli_fetch_all($resultMessages, MYSQLI_ASSOC);
mysqli_stmt_close($stmtMessages);

// Fetch receiver info if available
$receiverInfo = null;
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
    <link rel="stylesheet" href="styles.css">
    <style>
        .message-history {
            margin-bottom: 20px;
            padding: 0 60px;
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
    <header>
        <?php
        $conn = include("../database.php");
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT u.first_name, u.last_name
                FROM users u
                JOIN professor p ON u.username = p.ProfID
                WHERE p.userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $professor_first = $row['first_name'];
        $professor_last = $row['last_name']
        ?>
        <h1>Professor Page of <?php echo strtoupper($professor_first);?> <?php echo strtoupper($professor_last);?></h1>
    </header>
    <h1 style="padding: 60px 60px">Message History</h1>
    <div class="message-history" >
        <?php foreach ($messages as $message): ?>
            <div class="message" style="padding: 10px 60px">
                <strong ><?= $message['sender_user_id'] == $professorUserId ? 'You' : htmlspecialchars($receiverInfo['first_name'] . ' ' . $receiverInfo['last_name']) ?>:</strong>
                <p><?= htmlspecialchars($message['message_content']) ?></p>
                <span><?= date('M d, Y h:i A', strtotime($message['message_date'])) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    
    <form style="padding: 10px 60px" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiverId) ?>">
        <textarea name="message_content" id="message_content" required></textarea>
        <input type="submit" name="send_message" value="Send Message">
    </form>
</body>
</html>
