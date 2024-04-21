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
    width: 200px;
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
<body style=" background-color: #f5f5f5;">
    <section>
        <div class="sidenav">

            <button class="dropdownbtn">COURSE LIST</button>
                
                <div class="dropdown-container"> 
                    <?php include("tacourselist.php") ?>
                </div>
                <button class="dropdownbtn"><a href="?page=ta_messages.php" style="color: #333">EMAIL</a></button>
                
        </div> 
           
    </section>
    <section>
            <div class="main-content" style="margin-left: 305px;">

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
</body>
</html>