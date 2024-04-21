<?php
session_start();
require("../database.php");
require("../function.php");

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}
if (isset($_GET['role'])) {
    $_SESSION['selected_role'] = $_GET['role'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRSMGR</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #333;
            color: #fff;
            padding: 20px;

        }

        .inner_header {
            width: 1000px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* margin: 0 auto; */
            background-color: #0e836a;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .nav {
            display: flex;
            display: flex;
            /* list-style-type: none; */
            /* margin: 0; */
            margin-right:50px;
            align-content: center;
            justify-content: center;
            align-items: center;
        }

        .nav li {
            margin-left: 20px;
            margin-bottom: 0;
        }

        .nav a {
            color: #333;
            text-decoration: none;
        }

        .nav a:hover {
            color: #ccc;
        }
        
    </style>
</head>
<body>
    <header class="header" style="background-color: #333">
        <div class="inner_header"  style="display: flex; align-items: center">
            <h2 style="padding-left: 50px">CRSMGR+</h2>
                <ul class="nav" style="">
                    <li><a href="?page=home.php&role=<?php echo $_SESSION['selected_role']; ?>">HOME</a></li>
                    <li><a href="../index.php">CHANGE ROLE</a></li>
                    <li><a href="../faq/faq.php">FAQ</a></li>
                    <li><a href="?page=setting.php">SETTING</a></li>
                    <li><a href="../logout.php">LOGOUT</a></li>
                </ul>
        </div>
    </header>
</body>
</html>
