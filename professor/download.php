<?php
require("../database.php");
require("../logs.php");  

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conn = include("../database.php");
    $sql = "SELECT material_name, material_type, material_data FROM course_materials WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $material_name = $row['material_name'];
        $material_type = $row['material_type'];
        $material_data = $row['material_data'];

        // Log the download event right before sending the file
        session_start();
        if (isset($_SESSION['user_id']) ) {
            writeToLog($conn, $_SESSION['user_id'], "DOWNLOAD", "Downloaded: " . $material_name);
        }

        header('Content-Type: ' . $material_type);
        header('Content-Disposition: attachment; filename="' . $material_name . '"');
        header('Content-Length: ' . strlen($material_data));
        echo $material_data;
        exit;
    } else {
        echo "Material not found.";
    }
} else {
    echo "Invalid request.";
}
?>
