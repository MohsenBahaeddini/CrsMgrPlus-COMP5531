<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
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

        a {
            color: #333;
            text-decoration: none;
        }

        a:hover {
            color: #666;
        }

        .loginbtn {
            border: 2px solid ;
            border-radius: 3px;
            text-decoration: none;
            padding: 5px;
            background-color: #3399ff;
            color: black;
        }

        .button-container {
            display: inline-block;
            margin: 5px;
        }

        .button-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button-container a:hover {
            background-color: #555;
        }

        
    </style>
</head>

<body>
    <header>
        <h1>HOME</h1>
    </header>

    <section>
        <?php if (isset($_SESSION["user_id"])) : ?>
        <p>You are logged in. Choose one of the roles to proceed:</p>

        <?php foreach ($_SESSION["user_role"] as $x) : ?>
            <?php if ($x[0] == 1) : ?>
                <p class="button-container"><a href="administrator/administrator.php?role=1">Administrator</a></p>
            <?php elseif ($x[0] == 2) : ?>
                <p class="button-container"><a href="professor/professor.php?role=2">Professor</a></p>
            <?php elseif ($x[0] == 3) : ?>
                <p class="button-container"><a href="ta/teaching_assistant.php?role=3">Teaching Assistant</a></p>
            <?php elseif ($x[0] == 4) : ?>
                <p class="button-container"><a href="student/student.php?role=4">Course Student</a></p>
            <?php endif; ?>
        <?php endforeach; ?>

        <p class="button-container"><a href="logout.php">Log Out</a></p>
    <?php else : ?>
        <p class="button-container"><a href="login.php" class="loginbtn">LOG IN</a></p>
    <?php endif ?>
    </section>
</body>

</html>
