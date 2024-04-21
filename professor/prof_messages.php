<?php
session_start(); // Start the session at the beginning of the script
require("../includes/header.php");
require_once '../database.php';

if (!isset($_SESSION['user_id'])) {
    die('Error: You must be logged in as a professor to access this page.');
}

$professorUserId = $_SESSION['user_id'];

// Fetch all classes the professor is teaching
$sqlClasses = "SELECT s.CourseID, s.SectionID
               FROM section s
               JOIN professor p ON p.ProfID = s.prof_id
               WHERE p.userID = ?";
$stmtClasses = $conn->prepare($sqlClasses);
if ($stmtClasses === false) {
    die('Prepare failed: ' . $conn->error);
}

$stmtClasses->bind_param('i', $professorUserId);
$stmtClasses->execute();
$resultClasses = $stmtClasses->get_result();

if ($resultClasses === false) {
    die('Error fetching classes: ' . $conn->error);
}

$classes = $resultClasses->fetch_all(MYSQLI_ASSOC);
$stmtClasses->close();

$students = [];
$tas = [];

foreach ($classes as $class) {
    $courseID = $class['CourseID'];
    $sectionID = $class['SectionID'];

    // Fetch students for a particular course and section
    $sqlStudents = "SELECT u.ID, u.first_name, u.last_name FROM users u
                    JOIN student s ON u.ID = s.user_id
                    JOIN classes c ON s.StudentID = c.StudentID
                    WHERE c.CourseID = ? AND c.SectionID = ?";
    $stmtStudents = $conn->prepare($sqlStudents);
    if ($stmtStudents === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmtStudents->bind_param('ss', $courseID, $sectionID);
    $stmtStudents->execute();
    $resultStudents = $stmtStudents->get_result();

    while ($row = $resultStudents->fetch_assoc()) {
        $students[$courseID][$sectionID][] = $row;
    }
    $stmtStudents->close();

    // Fetch TA
    $sqlTA = "SELECT DISTINCT u.ID, u.first_name, u.last_name FROM users u
                JOIN teacher_assistant ta ON u.ID = ta.user_id
                JOIN classes_ta cta ON ta.taID = cta.taID
                WHERE cta.CourseID = ? AND cta.SectionID = ?";
    $stmtTA = $conn->prepare($sqlTA);
    if ($stmtTA === false) {
        die('Prepare failed: ' . $conn->error);
    }

    $stmtTA->bind_param('ss', $courseID, $sectionID);
    $stmtTA->execute();
    $resultTA = $stmtTA->get_result();

    if ($row = $resultTA->fetch_assoc()) {
        $tas[$courseID][$sectionID] = $row;
    }
    $stmtTA->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Communication Panel</title>
    <link rel="stylesheet" href="styles.css">

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
    <div style="padding: 60px">
    <h1>Communication Panel for Professor</h1>
    <?php foreach ($classes as $class): ?>
        <?php
        $courseID = $class['CourseID'];
        $sectionID = $class['SectionID'];
        ?>
        <h2 style="padding: 40px 0 10px 0; color:#333">Class: <?= htmlspecialchars($courseID) ?> - Section <?= htmlspecialchars($sectionID) ?></h2>
        <table >
            <tr>
                <th>Student Name</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students[$courseID][$sectionID] as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                    <td>
                        <form action="prof_send_message_form.php" method="post" style="margin-bottom:0">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($student['ID']); ?>">
                            <input type="submit" value="Message Student">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (isset($tas[$courseID][$sectionID])): ?>
                <tr>
                    <td>TA: <?= htmlspecialchars($tas[$courseID][$sectionID]['first_name'] . ' ' . $tas[$courseID][$sectionID]['last_name']); ?></td>
                    <td>
                        <form action="prof_send_message_form.php" method="post" style="margin-bottom:0">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($tas[$courseID][$sectionID]['ID']); ?>">
                            <input type="submit" value="Message TA">
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endforeach; ?>
    </div>
</body>
</html>
