<?php 
    require("../includes/header.php");
?>
<?php if(isset($_SESSION["user_id"])): ?>

    <?php
        
        if($_SESSION["user_role"][0][0] != 1){
            echo"likely an illegal access!";
            exit;
        }
    ?>

<?php else: ?>

    <p><a href="login.php">LOG IN</a>
    <?php exit ?>

<?php endif ?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator</title>
    <style>
        body {
        font-family: "Lato", sans-serif;
        background-color: #f5f5f5;
        }
        
        .main-content {
            
            background-color: #f5f5f5;
        }
    </style>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body> 
    <div class="main-body">
            <section>
                <div class="sidenav" style="background-color: #fff; width: auto; display: flex; flex-direction: column; height: 100vh; padding:40px 20px">

                    <button class="dropdownbtn" style="padding:20px 20px; color: #333; font-weight:bold;">COURSE</button>
                        <div class="dropdown-container"  style="background-color: #0E836A;"> 
                            <a href="?page=addcourse.php" style="color: #fff" >Add course</a>
                            <a href="?page=addsession.php" style="color: #fff">Open a new section</a>
                            <a href="?page=section.php" style="color: #fff">Section</a>
                        </div>
                    <button class="dropdownbtn" style="padding:20px 20px ;color: #333; font-weight:bold;">MANAGE</button>
                        <div class="dropdown-container" style="background-color: #0E836A;">
                            <button class="dropdownbtn" style="color: #fff">USER</button>
                                <div class="dropdown-container" style="background-color: #00C897;">
                                    <a href="?page=newuser.php" style="color: #fff">NEW</a>
                                    <a href="?page=existinguser.php" style="color: #fff">EXISTING</a>
                                </div>
                                <button class="dropdownbtn" style="color: #fff">GROUP</button>
                                <div class="dropdown-container" style="background-color: #00C897;" >
                                    <a href="?page=newgroup.php" style="color: #fff">NEW</a>
                                    <a href="?page=existinggroup.php" style="color: #fff">EXISTING</a>
                                </div>
                        </div>
                        <a href="?page=viewlogs.php" style="padding:20px 20px ;color: #333; font-weight:bold;">SYSTEM LOGS</a>
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
                    } else {
                        include("../home.php");
                    }
                    ?>

                </div>
            </section>
        <!-- script for opening the dropdown btn  -->            
        <script>
        
        var dropdown = document.getElementsByClassName("dropdownbtn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
            } else {
            dropdownContent.style.display = "block";
            }
        });
        }
        </script>
    </div>

</body>