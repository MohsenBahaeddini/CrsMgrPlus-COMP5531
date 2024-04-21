<?php 
    if(isset($_POST["modify"])){
        $sql_update = sprintf("UPDATE section SET prof_id='%s', 
                                start_date='%s',end_date = '%s'
                                WHERE SectionID='%s' AND CourseID='%s'",
                                $_POST['prof'],$_POST['startdate'],$_POST['enddate'],$_GET["search_SectionID"],$_GET["search_CourseID"]);
        echo$sql_update;
        if(mysqli_query($conn,$sql_update)){
            display_success();
        }
    }
    if(isset($_GET['delete'])){
        $sql_delete = sprintf("DELETE FROM classes WHERE SectionID='%s' AND StudentID='%s' AND CourseID='%s'",
        $_GET['search_SectionID'],$_GET['delete'],$_GET['search_CourseID']);
        mysqli_query($conn,$sql_delete);

    }
    if(isset($_GET['coursedelete'])){
        $sql_coursedelete = sprintf("DELETE FROM section 
                                     WHERE SectionID ='%s' AND CourseID = '%s'",$_GET['search_SectionID'],$_GET['search_CourseID']);
        if(mysqli_query($conn,$sql_coursedelete)){
            display_success();
        }

    }
    if(isset($_GET['tadelete'])){
        $sql_tadelete = sprintf("DELETE FROM classes_ta
                                 WHERE taID ='%s' AND CourseID ='%s' AND SectionID ='%s' AND Term='%s'",$_GET['tadelete'], $_GET['search_CourseID'],$_GET['search_SectionID'],$_GET['search_term']);
        if(mysqli_query($conn,$sql_tadelete)){
            display_success();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
    </style>
</head>
<body>
    <div class="content" style="padding: 60px 45px">
        <h2 style="padding-bottom: 10px">MODIFY SECTION</h2> 
            <form action="" method="get">
                <input type="hidden" name="page" value="section.php">
                <label>Enter Course ID</label><input type="text" name="search_CourseID"><br>
                <label>Term</label><input type="text" name="search_term" required> <br>
                <input type="submit" value="Enter" name = "search_section">
            </form>
            <br><hr><br>
            <?php 
                if(isset($_GET["search_section"])) {
                $sql_search_section = sprintf("SELECT * 
                                            FROM section
                                            WHERE courseID = '%s' AND Term = '%s'",$_GET["search_CourseID"],$_GET["search_term"]);
                $result = mysqli_query($conn, $sql_search_section);
                // $result_row = mysqli_fetch_assoc($result);
                $search_CourseID = $_GET['search_CourseID'];
                $search_term = $_GET['search_term'];
                
                }
            ?>
        <table>
            <thead>
                <th>COURSEID</th>
                <th>SECTIONID</th>
                <th>TERM</th>
                <th>START</th>
                <th>END</th>
                <th>ProfID</th>
                <th>ACTION</th>
            </thead>

            <tbody>
                <?php 
                    if(isset($_GET["search_section"])) {
                        foreach($result as $result_row) {
                            $CourseID = $result_row['CourseID'];
                            $SectionID = $result_row['SectionID'];
                            $term = $result_row['Term'];
                            $startdate = $result_row['start_date'];
                            $enddate = $result_row['end_date'];
                            $prof = $result_row['prof_id']; 
                    
                ?>
                <tr>
                    <td><?= $CourseID?></td>
                    <td><?= $SectionID?></td>
                    <td><?= $term?></td>
                    <td><?= $startdate?></td>
                    <td><?= $enddate?></td>
                    <td><?= $prof?></td>
                    <td><a href="?page=section.php&search_CourseID=<?=$CourseID?>&search_term=<?=$term?>&search_SectionID=<?=$SectionID?>&search_section=enter&modifyview=true">MODIFY</a></td>
                    <td><a href="?page=section.php&search_CourseID=<?=$CourseID?>&search_term=<?=$term?>&search_SectionID=<?=$SectionID?>&search_section=enter&classes=true&add=true&prof=<?=$prof?>">SEE STUDENT</a></td>
                    <td><a href="?page=section.php&search_CourseID=<?=$CourseID?>&search_term=<?=$term?>&search_SectionID=<?=$SectionID?>&search_section=enter&coursedelete=true" onclick="return confirm('Are you sure you want to delete this Section?');">DELETE</a></td>
                    <td><a href="?page=section.php&search_CourseID=<?=$CourseID?>&search_term=<?=$term?>&search_SectionID=<?=$SectionID?>&search_section=enter&assign=true">ASSIGN TA</a></td>
                </tr>
            </tbody>
            <?php }}; ?>
        </table>
        <?php if(isset($_GET['modifyview'])) { 
             $sql_search_section = sprintf("SELECT * 
                                            FROM section
                                            WHERE courseID = '%s' AND Term = '%s' AND SectionID ='%s'",$_GET["search_CourseID"],$_GET["search_term"],$_GET['search_SectionID']);
             $result = mysqli_query($conn, $sql_search_section);
             $resultrow =mysqli_fetch_assoc($result);
            ?>
        <form action="" method="post">
         
        <p>          
            <label>ProfID</label> 
            <input type = "text" name = "prof" value="<?=$resultrow['prof_id']?>"><br>
        </p> 
        <p>
            <label>Start date: </label>
            <input type="text" name="startdate" value="<?=$resultrow['start_date']?>"> <br>
        </p> 
        <p>
            <label>End date:</label> 
            <input type="text" name="enddate" value="<?=$resultrow['end_date']?>"><br> 
        </p> 
        <p>
            <input type="submit" value="submit" name="modify">
        </p> 
        </form>
        <?php }?>

        <?php 
            if(isset($_GET['classes'])){include("studentlist.php");};
        ?>
        

        <?php 
            
            if(isset($_GET['add'])){ 
            
        ?>
            
            <div style="margin-top: 5px;">
                <form action="" method="post">
                    <label for="studentID">Student ID:</label><input type="text" name="studentid" id="studentid"><input type="submit" value="Add" name="addstudent">
                </form>
            </div>
        <?php }?>
        <?php 
            if(isset($_POST['addstudent'])){
                $sql_getprofid = sprintf("SELECT (userID)
                                  FROM professor
                                  WHERE ProfID='%s'",$_GET['prof']);
                $resultprofid = mysqli_query($conn,$sql_getprofid);
                $resultrow = mysqli_fetch_assoc($resultprofid);
                $profID = $resultrow['userID'];

                
                $sql_addmember = sprintf("INSERT INTO `classes` (CourseID, StudentID, SectionID, user_id)
                                  VALUES ('%s','%s','%s','%s')",$_GET['search_CourseID'],$_POST['studentid'],$_GET['search_SectionID'],$profID);
                if(mysqli_query($conn,$sql_addmember)){
                    display_success();
                }
            }
            ?>
             <?php 
            
            if(isset($_GET['assign'])){ 
            
            ?>
            
            <div style="margin-top: 5px;">
                <form action="" method="post">
                    <label for="taID">taID:</label><input type="text" name="assignta" id="assignta"><input type="submit" value="Add" name="addta">
                </form>
            </div>
            <div>
                <?php include("talist.php"); ?>
            </div>
        <?php }?>
        <?php 
            if(isset($_POST['addta'])) {
                $sql_addta = sprintf("INSERT INTO `classes_ta` (CourseID, SectionID, taID, Term)
                                      VALUES ('%s','%s','%s','%s');",$_GET['search_CourseID'],$_GET['search_SectionID'],$_POST['assignta'],$_GET['search_term']);
                mysqli_query($conn,$sql_addta);
            }
        ?>
    </div>       
</body>
</html>