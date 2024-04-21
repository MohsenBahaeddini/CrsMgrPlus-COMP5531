<?php 
    $groupview = false;
    $materialview = false;
    if(isset($_GET['courseID']) && isset($_GET['SectionID'])){
        
        $CourseID = $_GET['courseID'];
        $SectionID = $_GET['SectionID'];
        $sql_getTerm = sprintf("SELECT (Term) FROM section WHERE CourseID = '%s' AND SectionID = '%s';",$CourseID,$SectionID);
        $result = mysqli_query($conn,$sql_getTerm);
        $resultrow = mysqli_fetch_assoc($result);
        $Term = $resultrow['Term'];
        
    } 
    if(isset($_GET['groupview'])) {
        $groupview = $_GET['groupview'];
    }
    if(isset($_GET['materialview'])) {
        $materialview = $_GET['materialview'];
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .main{
            padding: 60px 0;
        }
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
    <h1>Welcome <?=$_SESSION["user_firstname"]." ".$_SESSION['user_lastname']?>  to course <?=$CourseID?>, section <?=$SectionID?>, in <?=$Term?> term</h1>

    <div>
        <button class="viewbtn">
            <?php echo"<a href='?page=course_home.php&courseID="
            .$CourseID."&SectionID=".$SectionID."&groupview=true'>View Course Group</a>"?>
        </button>
        <button class="viewbtn">
            <?php echo"<a href='?page=course_home.php&courseID="
            .$CourseID."&SectionID=".$SectionID."&materialview=true'>View Course Material</a>"?>
        </button>
        <?php 
        
        if($groupview) { 
            include("studentview/group_view.php");
        } 
        if($materialview){
            include("studentview/student_crsmaterial_view.php");
        }
        ?>
        <button class="viewbtn"><a href="?page=group_files.php">Group Files</a></button>
    </div>        

       
</body>
</html>