<?php
require_once '../database.php';

// Function to get all courses
function getCourses($conn) {
    $sql = "SELECT CourseID, Course_name FROM course";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Function to get sections based on the selected course
function getSections($conn, $courseID) {
    $sql = "SELECT SectionID FROM section WHERE CourseID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $courseID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Function to get students based on the selected section and course
function getStudents($conn, $sectionID, $courseID) {
    // SQL Query to select students who are not already part of a group for the specified course
    $sql = "SELECT s.StudentID, u.first_name, u.last_name 
            FROM student s
            JOIN users u ON s.user_id = u.ID
            JOIN classes c ON s.StudentID = c.StudentID
            LEFT JOIN member_of_group mog ON mog.student_id = s.StudentID
            LEFT JOIN group_of_course goc ON goc.group_id = mog.group_id AND goc.course_id = c.CourseID
            WHERE c.SectionID = ? AND c.CourseID = ? AND goc.group_id IS NULL";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $sectionID, $courseID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


// Handle POST request to add a new group
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_group'])) {
    $courseID = $_POST['course_id'] ?? '';
    $sectionID = $_POST['section_id'] ?? '';
    $groupLeaderID = $_POST['group_leader_sid'] ?? '';
    $groupName = $_POST['group_name'] ?? '';

    // Check if the group leader is already leading a group for this course
    $checkLeaderQuery = "SELECT sg.group_id FROM student_groups sg
                         INNER JOIN group_of_course goc ON sg.group_id = goc.group_id
                         WHERE sg.group_leader_sid = ? AND goc.course_id = ?";
    $checkLeaderStmt = mysqli_prepare($conn, $checkLeaderQuery);
    mysqli_stmt_bind_param($checkLeaderStmt, "ss", $groupLeaderID, $courseID);
    mysqli_stmt_execute($checkLeaderStmt);
    $checkLeaderResult = mysqli_stmt_get_result($checkLeaderStmt);
    
    // Also, check if the group leader is a member of another group for the same course
    $checkMemberQuery = "SELECT mog.group_id FROM member_of_group mog
                         INNER JOIN group_of_course goc ON mog.group_id = goc.group_id
                         WHERE mog.student_id = ? AND goc.course_id = ?";
    $checkMemberStmt = mysqli_prepare($conn, $checkMemberQuery);
    mysqli_stmt_bind_param($checkMemberStmt, "ss", $groupLeaderID, $courseID);
    mysqli_stmt_execute($checkMemberStmt);
    $checkMemberResult = mysqli_stmt_get_result($checkMemberStmt);

    if (mysqli_num_rows($checkLeaderResult) > 0 || mysqli_num_rows($checkMemberResult) > 0) {
        echo "Error: The selected group leader is already leading or a member of a group for this course.";
    } else {
        // Insert the new group if the group leader is not already in a group for this course
        $insertGroupSQL = "INSERT INTO student_groups (group_name, group_leader_sid) VALUES (?, ?)";
        $insertStmt = mysqli_prepare($conn, $insertGroupSQL);
        mysqli_stmt_bind_param($insertStmt, "ss", $groupName, $groupLeaderID);
        if (mysqli_stmt_execute($insertStmt)) {
            $newGroupID = mysqli_insert_id($conn);
            // Insert relation to group_of_course
            $insertGroupCourseSQL = "INSERT INTO group_of_course (group_id, course_id) VALUES (?, ?)";
            $insertCourseStmt = mysqli_prepare($conn, $insertGroupCourseSQL);
            mysqli_stmt_bind_param($insertCourseStmt, "is", $newGroupID, $courseID);
            if (mysqli_stmt_execute($insertCourseStmt)) {
                echo "New group added successfully.";
            } else {
                echo "Error adding group to course: " . mysqli_error($conn);
            }
        } else {
            echo "Error creating new group: " . mysqli_error($conn);
        }
        mysqli_stmt_close($insertStmt);
        mysqli_stmt_close($insertCourseStmt);
    }
    mysqli_stmt_close($checkLeaderStmt);
    mysqli_stmt_close($checkMemberStmt);
}


// Get courses for the dropdown
$courses = getCourses($conn);
$sections = [];
$students = [];

// Check for a selected course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_course'])) {
    $selectedCourseID = $_POST['course_id'];
    $sections = getSections($conn, $selectedCourseID);
}

// Check for a selected section
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['select_section'])) {
    $selectedCourseID = $_POST['course_id'];
    $selectedSectionID = $_POST['section_id'];
    $students = getStudents($conn, $selectedSectionID, $selectedCourseID);
    $sections = getSections($conn, $selectedCourseID); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Group</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .submitbutton {
            margin-top: 8px;
        }
        .description{
            height: 100px;
        }
        .inputform {
            /* margin-left: 305px; */
            padding:50px 350px ;
        }
        h1 {
            color: black;
            padding: 10px 0;
        }
        form {
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            color: #333;
            font-weight: bold;
        }
        input, textarea{
            padding: 10px 0;
            margin: 10px 0;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;

        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 5px;
        }
        .content{
            padding: 60px 30px 10px 45px;
        }

    </style>
</head>
<body>
    <div class="content">
        <h1>Create New Group</h1>
        <form method="POST">
            <label for="course_id">Select Course:</label>
            <select name="course_id" id="course_id">
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= htmlspecialchars($course['CourseID']); ?>" <?= (isset($selectedCourseID) && $selectedCourseID === $course['CourseID']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($course['Course_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="submit" name="select_course" value="Select Course">
        </form>

        <?php if (!empty($sections)): ?>
        <form method="POST">
            <label for="section_id">Select Section:</label>
            <select name="section_id" id="section_id">
                <option value="">Select a section</option>
                <?php foreach ($sections as $section): ?>
                    <option value="<?= htmlspecialchars($section['SectionID']); ?>" <?= (isset($selectedSectionID) && $selectedSectionID === $section['SectionID']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($section['SectionID']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="course_id" value="<?= htmlspecialchars($selectedCourseID); ?>">
            <input type="submit" name="select_section" value="Select Section">
        </form>
        <?php endif; ?>

        <?php if (!empty($students)): ?>
        <form method="POST">
            <label for="group_leader_sid">Select Group Leader:</label>
            <select name="group_leader_sid" id="group_leader_sid">
                <option value="">Select a student</option>
                <?php foreach ($students as $student): ?>
                    <option value="<?= htmlspecialchars($student['StudentID']); ?>">
                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" name="course_id" value="<?= htmlspecialchars($selectedCourseID); ?>">
            <input type="hidden" name="section_id" value="<?= htmlspecialchars($selectedSectionID); ?>">
            <br>
            <br>
            <label for="group_name">Group Name:</label>
            <input type="text" name="group_name" id="group_name" required>
            <input type="submit" name="add_group" value="Create Group">
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
