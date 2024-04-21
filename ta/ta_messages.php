<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../database.php';



// $currentUserTAID = $_SESSION['taID'];

$taUserID = $_SESSION['user_id'];

// Fetch taID using the user_id from the session
$sql_gettaid = "SELECT taID FROM teacher_assistant WHERE user_id = ?";
$stmt_gettaid = mysqli_prepare($conn, $sql_gettaid);
mysqli_stmt_bind_param($stmt_gettaid, 'i', $taUserID);
mysqli_stmt_execute($stmt_gettaid);
$result_gettaid = mysqli_stmt_get_result($stmt_gettaid);
$taIDRow = mysqli_fetch_assoc($result_gettaid);
mysqli_stmt_close($stmt_gettaid);

if (!$taIDRow) {
    die("Error: TA not found.");
}

$currentUserTAID = $taIDRow['taID'];

// Fetch all classes the current TA is assisting in
$sqlClassesTA = "SELECT CourseID, SectionID FROM classes_ta WHERE taID = ?";
$stmtClassesTA = mysqli_prepare($conn, $sqlClassesTA);
mysqli_stmt_bind_param($stmtClassesTA, 's', $currentUserTAID);
mysqli_stmt_execute($stmtClassesTA);
$resultClassesTA = mysqli_stmt_get_result($stmtClassesTA);

$classesTA = mysqli_fetch_all($resultClassesTA, MYSQLI_ASSOC);
mysqli_stmt_close($stmtClassesTA);



function getProfessor($conn, $courseID) {
    $sql = "SELECT p.ProfID, u.first_name, u.last_name, u.ID as user_id
            FROM professor p
            JOIN users u ON p.userID = u.ID
            JOIN classes c ON c.user_id = p.userID
            WHERE c.CourseID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $courseID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}




// Initialize arrays to hold students and professors
$students = [];
$professors = [];

foreach ($classesTA as $class) {
    $courseID = $class['CourseID'];
    $sectionID = $class['SectionID'];

    // Fetch students
    $sqlStudents = "SELECT u.ID, u.first_name, u.last_name FROM users u
                    INNER JOIN student s ON u.ID = s.user_id
                    INNER JOIN classes c ON s.StudentID = c.StudentID
                    WHERE c.CourseID = ? AND c.SectionID = ?";
    $stmtStudents = mysqli_prepare($conn, $sqlStudents);
    mysqli_stmt_bind_param($stmtStudents, 'ss', $courseID, $sectionID);
    mysqli_stmt_execute($stmtStudents);
    $resultStudents = mysqli_stmt_get_result($stmtStudents);

    while ($row = mysqli_fetch_assoc($resultStudents)) {
        $students[$courseID][$sectionID][] = $row;
    }
    mysqli_stmt_close($stmtStudents);

// Fetch the professor for the class
$professor = getProfessor($conn, $courseID);
if ($professor) {
    $professors[$courseID][$sectionID] = $professor;
}
}

if (!$resultClassesTA) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Message System</title>
    <style>
        .main{
            margin-top:60px;
        }
        h2{
            color: #333;
            padding:10px 0;
        }
        input{
            padding: 10px 10px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="main">
    <h1>Email Students and Professor</h1>
    <?php foreach ($classesTA as $class): ?>
        <?php
        $courseID = $class['CourseID'];
        $sectionID = $class['SectionID'];
        ?>
        <h2>Class: <?= htmlspecialchars($courseID) ?> - <?= htmlspecialchars($sectionID) ?></h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php foreach ($students[$courseID][$sectionID] as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                    <td>
                        <form action="ta_send_message_form.php" method="post">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($student['ID']); ?>">
                            <input type="submit" value="Message Student">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!empty($professors[$courseID][$sectionID])): ?>
                <tr>
                    <td>Professor <?= htmlspecialchars($professors[$courseID][$sectionID]['first_name'] . ' ' . $professors[$courseID][$sectionID]['last_name']); ?></td>
                    <td>
                        <form action="ta_send_message_form.php" method="post">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($professors[$courseID][$sectionID]['user_id']); ?>">
                            <input type="submit" value="Message Professor">
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endforeach; ?>
    </div>
</body>
</html>
