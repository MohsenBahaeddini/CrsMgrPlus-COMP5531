<?php 
    require("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        /* .sidenav{
            background-color: #fff; 
            width: auto;
            display: flex;
            flex-direction: column;
            height: 100vh; 
            padding:40px 20px"
        }
        .main-content {
            background-color: #f5f5f5;
        } */

        .sidenav {
    background-color: #fff;
    width: 220px;
    display: flex;
    flex-direction: column;
    height: 100vh;
    padding: 40px 20px;
}

.dropdownbtn {
    padding: 20px 20px;
    color: #333;
    font-weight: bold;
    background: none;
    border: none;
    text-align: left;
    width: 100%;
    text-transform: uppercase;
    cursor: pointer;
}

.dropdown-container {
    background-color: #0E836A;
}

.dropdown-container a {
    color: #fff;
    padding: 10px 15px;
    text-decoration: none;
    display: block;
}

.dropdown-container .dropdownbtn {
    color: #fff;
    padding: 10px 15px;
    background-color: #00C897;
    border: none;
    width: 100%;
}

.dropdown-container .dropdown-container {
    background-color: #00C897;
}

.dropdown-container .dropdown-container a {
    padding-left: 30px;
}

.system-logs-link {
    padding: 20px 20px;
    color: #333;
    font-weight: bold;
    text-decoration: none;
    display: block;
}

    </style>
</head>
<body style="background-color:#f5f5f5">
    <section>
        <div class="sidenav">

                        <button class="dropdownbtn"><a href="?page=structure.php">Structure</a></button>
                        <button class="dropdownbtn"><a href="?page=createtable.php">Create Table</a></button>
                        <button class="dropdownbtn"><a href="?page=sql.php">SQL</a></button>
                        <button class="dropdownbtn"><a href="../student/student.php">QUIT DB MANAGER</a></button>

                        
                            


        </div> 
    </section>
    <section>
        <div class="main-content">
            
            <?php 
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if (file_exists($page)) {
                        include($page);
                    }
                    if(file_exists("../$page")) {
                        include("../$page");
                    }
                } 
            ?>
        </div>
    </section>
</body>
</html>