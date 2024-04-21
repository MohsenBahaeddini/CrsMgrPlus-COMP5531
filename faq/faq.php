<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require("../includes/header.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        section {
            margin: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        form {
            margin-bottom: 20px;
        }

        input[type="submit"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #333;
            color: #fff;
            cursor: pointer;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            resize: vertical;
        }

        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <header>
        <h1>FAQ</h1>
    </header>

    <section>
        <?php
        $conn = include("../database.php");
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['CourseID']) && isset($_POST['faq_subject']) && isset($_POST['faq_content'])) {
                $courseID = $_POST['CourseID'];
                $faqSubject = $_POST['faq_subject'];
                $faqContent = $_POST['faq_content'];

                $stmt = $conn->prepare("SELECT faq_id, faq_content FROM faq WHERE CourseID = ? AND faq_subject = ?");
                $stmt->bind_param("ss", $courseID, $faqSubject);
                $stmt->execute();
                $result = $stmt->get_result();
                $existingFAQ = $result->fetch_assoc();

                if ($existingFAQ) {
                    $faqId = $existingFAQ['faq_id'];
                    if ($existingFAQ['faq_content'] == null || $existingFAQ['faq_content'] == '') {
                        $stmt = $conn->prepare("UPDATE faq SET faq_content = ? WHERE faq_id = ?");
                        $stmt->bind_param("si", $faqContent, $faqId);
                        $stmt->execute();
                    } else {
                        echo "FAQ subject already has content.";
                    }
                } else {
                    $stmt = $conn->prepare("INSERT INTO faq (faq_content, faq_subject, CourseID) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $faqContent, $faqSubject, $courseID);
                    $stmt->execute();
                }
            }
        }

        if (isset($_GET['CourseID'])) {
            $courseID = $_GET['CourseID'];
            $stmt = $conn->prepare("SELECT faq_id, faq_content, faq_subject FROM faq WHERE CourseID = ?");
            $stmt->bind_param("s", $courseID);
            $stmt->execute();
            $result = $stmt->get_result();
            $faqs = $result->fetch_all(MYSQLI_ASSOC);
        }
        ?>
        <form method="POST">
            <label for="courseID">Course ID:</label><br>
            <?php
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT role_id FROM access_role WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $user_role_number = $row['role_id'];

            $stmt = $conn->prepare("SELECT role_name FROM roles WHERE role_id = ?");
            $stmt->bind_param("i", $user_role_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $user_role = $row['role_name'];


            if ($user_role == 'professor') {
                $sql = "SELECT c.CourseID
                FROM course c
                JOIN section s ON c.CourseID = s.CourseID
                JOIN professor p ON s.prof_id = p.ProfID
                WHERE p.userID = ?";
            } else {
                $sql = "SELECT c.CourseID
                FROM classes c
                JOIN users u ON c.StudentID = u.username
                WHERE u.ID = ?";
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            ?>
            <select name="CourseID">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['CourseID']; ?>"><?= $row['CourseID']; ?></option>
                <?php endwhile; ?>
            </select>
            <br>
            <br>
            <label for="faq_subject">FAQ Question:</label><br>
            <input type="text" id="faq_subject" name="faq_subject"><br>
            <br>
            <label for="faq_content">FAQ Answer:</label><br>
            <textarea id="faq_content" name="faq_content"></textarea><br>
            <input type="submit" value="Submit">
        </form>
        <h3>CURRENT FAQ:</h3>
        <div id="faq_display"></div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("select[name='CourseID']").change(function() {
                var courseID = $(this).val();
                $.ajax({
                    url: 'get_faq.php',
                    type: 'get',
                    data: {
                        CourseID: courseID
                    },
                    success: function(response) {
                        $("#faq_display").html(response);
                    }
                });
            }).change();
        });
    </script>
</body>
</html>
