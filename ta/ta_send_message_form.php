<?php
session_start();  // Ensure session is started
require_once '../database.php';
require("../includes/header.php");


// Check if the TA's user ID is set in the session
if (!isset($_SESSION['user_id'])) {
    die('Error: You must be logged in to send messages.');
}

$currentUserId = $_SESSION['user_id']; // TA's user ID from session
$receiverId = $_POST['receiver_id'] ?? $_GET['receiver_id'] ?? null;

// Insert a message into the database if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $messageContent = mysqli_real_escape_string($conn, $_POST['message_content']);
    $sqlInsertMessage = "INSERT INTO messages (sender_user_id, receiver_user_id, message_content) VALUES (?, ?, ?)";
    $stmtInsertMessage = mysqli_prepare($conn, $sqlInsertMessage);
    mysqli_stmt_bind_param($stmtInsertMessage, 'iis', $currentUserId, $receiverId, $messageContent);
    if (mysqli_stmt_execute($stmtInsertMessage)) {
        // Redirect to prevent form resubmission
        header("Location: ta_send_message_form.php?receiver_id=" . urlencode($receiverId));
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

// Fetch receiver info if available
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
    <link rel="stylesheet" href="../styles/style.css">
    <style>
                .sidenav {
    background-color: #fff;
    width: 200px;
    display: flex;
    flex-direction: column;
    height: 100vh;
    padding: 40px 20px;
}

.dropdownbtn {
    padding: 20px 20px;
    color: #333;
    font-weight: bold;
    background: none;
    border: none;
    text-align: left;
    width: 100%;
    text-transform: uppercase;
    cursor: pointer;
}

.dropdown-container {
    background-color: #0E836A;
}

.dropdown-container a {
    color: #fff;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
}

.dropdown-container .dropdownbtn {
    color: #fff;
    padding: 10px 15px;
    background-color: #00C897;
    border: none;
    width: 100%;
}

.dropdown-container .dropdown-container {
    background-color: #00C897;
}

.dropdown-container .dropdown-container a {
    padding-left: 30px;
}

.system-logs-link {
    padding: 20px 20px;
    color: #333;
    font-weight: bold;
    text-decoration: none;
    display: block;
}
        .main{
            margin: 50px auto;
            width: 50%;
            padding: 20px;}
        .message-history {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            max-height: 300px;
            overflow-y: auto;
            background-color: #fff;
            border-radius: 5px;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .message p {
            margin: 0;
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
            padding: 10px 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;

        }

    </style>
</head>
<body style="background-color:#f5f5f5;">
<section>
        <div class="sidenav" style="background-color:#fff">

            <button class="dropdownbtn">COURSE LIST</button>
                
                <div class="dropdown-container"> 
                    <?php include("tacourselist.php") ?>
                </div>
                <button class="dropdownbtn"><a href="?page=ta_messages.php" style="color: #333">EMAIL</a></button>
                
        </div> 
           
    </section>
    <div class="main">
    <h1>Messages</h1>
    <div class="message-history">
        <?php foreach ($messages as $message): ?>
            <div class="message">
                <strong><?= $message['sender_user_id'] == $currentUserId ? 'You' : htmlspecialchars($receiverInfo['first_name'] . ' ' . $receiverInfo['last_name']) ?>:</strong>
                <p><?= htmlspecialchars($message['message_content']) ?></p>
                <span><?= date('M d, Y h:i A', strtotime($message['message_date'])) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($receiverId) ?>">
        <textarea name="message_content" id="message_content" required></textarea>
        <input type="submit" name="send_message" value="Send Message">
    </form>
    </div>
</body>
</html>
