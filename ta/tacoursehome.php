<?php 
    $materialview = false;
    if(isset($_GET['courseID']) && isset($_GET['SectionID'])){
        
        $CourseID = $_GET['courseID'];
        $SectionID = $_GET['SectionID'];
        $Term = $_GET['Term'];
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .viewbtn{
            padding: 10px 10px;
            margin: 10px 0;
        }
        .viewbtn a {
            color: #333;
            font-weight: bold;
            font-size: 18px;
            
        }
        .main{
            padding: 60px 0;
        }
        h1{
            padding: 0px 0 10px 0;
        }
    </style>

</head>
<body>
    <div class="main">
    <h1>Welcome to <?=$CourseID?> in <?=$Term?> </h1>
    <button class="viewbtn"><?php echo"<a href='?page=tacoursehome.php&courseID="
            .$CourseID."&SectionID=".$SectionID."&Term="."$Term"."&materialview=true'>View Course Material</a>"?></button>
   
    <?php if(isset($_GET["materialview"])) { 
        include("taview/materialview.php");
    }
    ?>
    </div>
            

</body>
</html>