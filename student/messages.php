<?php
require_once '../database.php';



$currentUserStudentID = $_SESSION['studentID'];

// Fetch all courses the current user is enrolled in
$sqlEnrolledCourses = "SELECT CourseID FROM classes WHERE StudentID = ?";
$stmtEnrolledCourses = mysqli_prepare($conn, $sqlEnrolledCourses);
mysqli_stmt_bind_param($stmtEnrolledCourses, 's', $currentUserStudentID);
mysqli_stmt_execute($stmtEnrolledCourses);
$resultEnrolledCourses = mysqli_stmt_get_result($stmtEnrolledCourses);

$enrolledCourses = mysqli_fetch_all($resultEnrolledCourses, MYSQLI_ASSOC);
mysqli_stmt_close($stmtEnrolledCourses);

// Define the functions to get professors and TAs
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

function getTA($conn, $courseID) {
    $sql = "SELECT ta.taID, u.first_name, u.last_name, u.ID as user_id
    FROM teacher_assistant ta
    JOIN users u ON ta.user_id = u.ID
    JOIN classes_ta cta ON cta.taID = ta.taID
    WHERE cta.CourseID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $courseID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
return mysqli_fetch_assoc($result);
}

// Initialize arrays to hold classmates, professors, and TAs
$classmates = [];
$professors = [];
$tas = [];

// Loop through each enrolled course to get classmates, professors, and TAs
foreach ($enrolledCourses as $course) {
    $courseID = $course['CourseID'];
    $classmates[$courseID] = []; // Initialize an array to hold classmates for each course

    // Fetch classmates
    $sqlClassmates = "SELECT u.ID, u.first_name, u.last_name, s.StudentID FROM users u
                      INNER JOIN student s ON u.ID = s.user_id
                      INNER JOIN classes c ON s.StudentID = c.StudentID
                      WHERE c.CourseID = ? AND s.StudentID != ?";
    $stmtClassmates = mysqli_prepare($conn, $sqlClassmates);
    mysqli_stmt_bind_param($stmtClassmates, 'ss', $courseID, $currentUserStudentID);
    mysqli_stmt_execute($stmtClassmates);
    $resultClassmates = mysqli_stmt_get_result($stmtClassmates);

    while ($row = mysqli_fetch_assoc($resultClassmates)) {
        $classmates[$courseID][] = $row;
    }
    mysqli_stmt_close($stmtClassmates);

    // Fetch professor and TA
    $professors[$courseID] = getProfessor($conn, $courseID);
    $tas[$courseID] = getTA($conn, $courseID);
}

// Make sure to handle errors for each of your SQL queries
// For example:
if (!$resultEnrolledCourses) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Message Classmates, Professors, and TAs</title>
    <!-- Include your CSS here -->
    <style>
        .main{
            padding-top:60px;
        }
        h2{
            color: #333;
            padding:40px 0 10px 0;
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
    <h1>Message Your Classmates, Professors, and TAs</h1>
    <?php foreach ($enrolledCourses as $enrolledCourse): ?>
        <?php $courseID = $enrolledCourse['CourseID']; ?>
        <h2>Course: <?= htmlspecialchars($courseID) ?></h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
            <?php foreach ($classmates[$courseID] as $classmate): ?>
                <tr>
                    <td><?= htmlspecialchars($classmate['first_name'] . ' ' . $classmate['last_name']) ?></td>
                    <td>
                        <form action="send_message_form.php" method="post">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($classmate['ID']) ?>">
                            <input type="submit" value="Message">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!empty($professors[$courseID])): ?>
                <tr>
                    
                    <td>Professor: <?= htmlspecialchars($professors[$courseID]['first_name'] . ' ' . $professors[$courseID]['last_name']); ?></td>
                    <td>
                        <form action="send_message_form.php" method="post">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($professors[$courseID]['user_id']); ?>">
                            <input type="submit" value="Message Professor">
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($tas[$courseID])): ?>
                <tr>
                    <td>TA: <?= htmlspecialchars($tas[$courseID]['first_name'] . ' ' . $tas[$courseID]['last_name']); ?></td>
                    <td>
                        <form action="send_message_form.php" method="post">
                            <input type="hidden" name="receiver_id" value="<?= htmlspecialchars($tas[$courseID]['user_id']); ?>">
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
