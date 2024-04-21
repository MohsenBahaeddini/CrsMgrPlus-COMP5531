<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require("../database.php");
require("../logs.php"); 
require("../includes/header.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Page</title>
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

    <section>
        <h2>Class List</h2>
        <?php
        $conn = include("../database.php");
        $sql = "SELECT c.CourseID, c.Course_name
        FROM course c
        JOIN section s ON c.CourseID = s.CourseID
        JOIN professor p ON s.prof_id = p.ProfID
        WHERE p.userID = {$_SESSION['user_id']}";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<ul>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li>{$row['CourseID']} - {$row['Course_name']}</li>";
            }
            echo "</ul>";
        } else {
            echo "No classes found.";
        }
        ?>
    </section>
    <section>
        <h2>
            <a href="prof_messages.php">EMAIL</a>
        </h2>
    </section>
    <section>
        <h2>Create Class List from CSV</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="csv_file" accept=".csv">
            <input type="submit" name="submit_csv" value="Upload CSV">
        </form>
        <?php
        if (isset($_POST['submit_csv'])) {
            $csv_file = $_FILES['csv_file']['tmp_name'];
            $file_handle = fopen($csv_file, 'r');
            $header = fgetcsv($file_handle);
            $upload_successful = true;

            while (!feof($file_handle)) {
                $row = fgetcsv($file_handle);
                if (!empty($row)) {
                    $course_id = $row[0];
                    $student_id = $row[1];
                    $section_id = $row[2];

                    $sql_check = "SELECT * FROM section WHERE CourseID = '$course_id' AND SectionID = '$section_id'";
                    $result_check = mysqli_query($conn, $sql_check);

                    if (mysqli_num_rows($result_check) > 0) {
                        $sql_check_class = "SELECT * FROM classes WHERE CourseID = '$course_id' AND StudentID = '$student_id' AND SectionID = '$section_id'";
                        $result_check_class = mysqli_query($conn, $sql_check_class);

                        if (mysqli_num_rows($result_check_class) == 0) {
                            $sql = "INSERT INTO classes (CourseID, StudentID, SectionID, user_id) VALUES ('$course_id', '$student_id', '$section_id', {$_SESSION['user_id']})";
                            mysqli_query($conn, $sql);
                        } else {
                            echo "Class record with CourseID: $course_id, StudentID: $student_id, and SectionID: $section_id already exists.<br>";
                            $upload_successful = false;
                        }
                    } else {
                        echo "Section with CourseID: $course_id and SectionID: $section_id does not exist in the database.<br>";
                        $upload_successful = false;
                    }
                }
            }

            fclose($file_handle);

            if ($upload_successful) {
                echo "CSV file uploaded and classes created successfully.";
            }
        }
        ?>
    </section>

    <section>
        <h2>Add Course Material</h2>
        <form method="post" enctype="multipart/form-data">

            <?php
            $professor_id = $_SESSION['user_id'];
            $sql = "SELECT SectionID, CourseID, Term FROM section as s JOIN professor as p ON s.prof_id = p.ProfID WHERE p.userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $professor_id);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>

            CourseID:
            <select name="course_id">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['CourseID']; ?>"><?= $row['CourseID']; ?></option>
                <?php endwhile; ?>
            </select>

            <?php
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            SectionID:
            <select name="section_id">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['SectionID']; ?>"><?= $row['SectionID']; ?></option>
                <?php endwhile; ?>
            </select>

            <?php
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            Term:
            <select name="term">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['Term']; ?>"><?= $row['Term']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="file" name="material_data" id="material_data">

            <input type="submit" name="submit_material" value="Upload Material">
        </form>
    </section>

    <section>
        <h2>Retrieve Course Material</h2>
        <form method="post">
            CourseID:
            <select name="retrieve_course_id" required>
                <option value="">Select CourseID</option>
                <?php
                $sql = "SELECT DISTINCT CourseID FROM course_materials";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['CourseID'] . "'>" . $row['CourseID'] . "</option>";
                }
                ?>
            </select>

            SectionID:
            <select name="retrieve_section_id" required>
                <option value="">Select SectionID</option>
                <?php
                $sql = "SELECT DISTINCT SectionID FROM course_materials";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['SectionID'] . "'>" . $row['SectionID'] . "</option>";
                }
                ?>
            </select>

            Term:
            <select name="retrieve_term" required>
                <option value="">Select Term</option>
                <?php
                $sql = "SELECT DISTINCT Term FROM course_materials";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['Term'] . "'>" . $row['Term'] . "</option>";
                }
                ?>
            </select>

            <input type="submit" name="retrieve_material" value="Retrieve Material">
        </form>
        <?php
        if (isset($_POST['retrieve_material'])) {
            $retrieve_course_id = $_POST['retrieve_course_id'];
            $retrieve_section_id = $_POST['retrieve_section_id'];
            $retrieve_term = $_POST['retrieve_term'];

            $sql = "SELECT id, material_name, material_type, material_data FROM course_materials WHERE CourseID = ? AND SectionID = ? AND Term = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $retrieve_course_id, $retrieve_section_id, $retrieve_term);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h4>Retrieved Course Materials:</h4>";
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>Material Name:</strong> " . $row['material_name'] . "<br>";
                    echo "<strong>Material Type:</strong> " . $row['material_type'] . "<br>";
                    echo "<a href='download.php?id=" . $row['id'] . "'>Download Material</a><br>";
                    echo "</li>";
                }
                echo "</ul>";
                writeToLog($conn, $_SESSION['user_id'], "RETRIEVE_MATERIAL_SUCCESS", "Prof retrieved material for CourseID: " . $retrieve_course_id);

            } else {
                echo "No course materials found for the specified CourseID, SectionID, and Term.";
                writeToLog($conn, $_SESSION['user_id'], "RETRIEVE_MATERIAL_FAILED", "No materials found for CourseID: " . $retrieve_course_id);

            }
        }
        ?>
    </section>
</body>
</html>

<?php
if (isset($_FILES["material_data"])) {
    if ($_FILES["material_data"]["error"] == 0) {
        $material_name = $_FILES["material_data"]["name"];
        $material_type = $_FILES["material_data"]["type"];
        $material_data = file_get_contents($_FILES["material_data"]["tmp_name"]);
        $professor_id = $_SESSION['user_id'];
        $section_id = $_POST['section_id'];
        $course_id = $_POST['course_id'];
        $term = $_POST['term'];

        $sql = "INSERT INTO course_materials (material_name, material_type, material_data, professor_id, SectionID, CourseID, Term) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssisss", $material_name, $material_type, $material_data, $professor_id, $section_id, $course_id, $term);
        $stmt->execute();

        echo "File uploaded successfully.";
        writeToLog($conn, $_SESSION['user_id'], "ADD_MATERIAL_SUCCESS", "Material added: " . $material_name);

    } else {
        echo "Error: " . $_FILES["material_data"]["error"];
        writeToLog($conn, $_SESSION['user_id'], "ADD_MATERIAL_FAILED", "Failed to add material: " . $material_name);

    }
}
?>
