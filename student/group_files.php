<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require("../database.php");
require("../logs.php");

session_start(); 

// Check if the user is logged in and has a group_id stored in their session
if (!isset($_SESSION['user_id'], $_SESSION['usergroup'])) {
    die("You must be logged in and part of a group to access this page.");
}

$userId = $_SESSION['user_id'];
$groupId = $_SESSION['usergroup']; 

// Handling file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['group_file'])) {
    $file = $_FILES['group_file'];

    // Check for errors in the file upload
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileName = $file['name'];
        $fileType = $file['type'];
        $fileData = file_get_contents($file['tmp_name']); // Get file content

        // Prepare the SQL statement to insert the file
        $stmt = $conn->prepare("INSERT INTO group_files (group_id, file_name, file_type, file_data, uploaded_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssi", $groupId, $fileName, $fileType, $fileData, $userId);

        if ($stmt->execute()) {
            echo "File uploaded successfully.";
           
            writeToLog($conn, $userId, "FILE_UPLOAD", "File uploaded: " . $fileName);
        } else {
            echo "Failed to upload file.";
                        writeToLog($conn, $userId, "FILE_UPLOAD_FAILED", "Failed to upload file: " . $fileName);
                    }
                    $stmt->close();
                } else {
                    echo "Error uploading file. Error code: " . $file['error'];
                    writeToLog($conn, $userId, "FILE_UPLOAD_ERROR", "Error uploading file. Error code: " . $file['error']);
                }
            }
            
            // Retrieve files for the group
            $filesStmt = $conn->prepare("SELECT * FROM group_files WHERE group_id = ?");
            $filesStmt->bind_param("i", $_SESSION['usergroup']);
            $filesStmt->execute();
            $filesResult = $filesStmt->get_result();
            
            ?>
         <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Group Files</title>
    <style>
        .main {
            /* margin: 0 auto; */
            padding: 60px 60px 60px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        h1{
            padding: 10px 0;
        }
        form {
            padding: 20px;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h2{ 
            padding: 10px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="main">

    <h1>Group Files</h1>
    <form action="group_files.php" method="post" enctype="multipart/form-data">
        <input type="file" name="group_file" required>
        <button type="submit">Upload File</button>
    </form>

    <h2>Uploaded Files</h2>

    <?php 
    $sql = "SELECT file_id,file_name,file_type,file_data
    FROM group_files 
    WHERE group_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['usergroup']);
        $stmt->execute();
        $result = $stmt->get_result(); 

    ?>
    <table>
                <tr>
                    <th>Course Material</th>
                </tr>
                <tr>
                    <th>File Name</th>
                    <th>File Type</th>
                    <th>Download Link</th>
                    <th>Delete File</th>
                </tr>
               <?php if ($result->num_rows > 0) {  
                        while($row = $result->fetch_assoc()) {?> 
                            <tr>
                                <td><?=$row['file_name']?></td>
                                <td><?=$row['file_type']?></td>
                                <td><a href='download_group_file.php?file_id=<?=$row['file_id']?>'>download</a></td>
                                <td><a href='delete_group_file.php?file_id=<?= $row['file_id'] ?>' onclick="return confirm('Are you sure you want to delete this file?');">Delete</a></td>
                            </tr>
                        <?php } ?>
                <?php }?>
            </table> 
    </div> 
</body>
</html>
            
