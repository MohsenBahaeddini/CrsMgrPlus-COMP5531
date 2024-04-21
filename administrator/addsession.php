<?php 
    $conn = include("../database.php");
    $sql = "SELECT CourseID FROM course";
    $allCourseID = mysqli_query($conn,$sql); 
    
    
    // ADD 
    if(isset($_POST['add_section'])){
        
        $sql_insert = sprintf("INSERT INTO section (SectionID, CourseID,Term,start_date,end_date,prof_id)
                               VALUES ('%s','%s','%s','%s','%s','%s')",$_POST["SectionID"],$_POST["CourseID"],$_POST["term"],
                               $_POST["startdate"],$_POST["enddate"],$_POST["prof"]);
        if(mysqli_query($conn,$sql_insert)) {
            echo "allo";
        };
        
    };

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link ref="stylesheet" href="styles/style.css">
    <style>
        .main{
            margin-left: 305px;
        }
         /* form */
        form  { display: table;      }
        p     { display: table-row;  }
        label { display: table-cell; }
        input { display: table-cell; }
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
    </style>
</head>
<body>
    <div class="main" style="padding:60px 0 10px 45px">
    
        <h1 >Open Section for Courses</h1>
    
    <form name="formcontainer" method="post">
        <p>
            <label>CourseID</label>
            <select name="CourseID">
                <?php 
                    while($CourseID = mysqli_fetch_array($allCourseID,MYSQLI_ASSOC)):;
                ?>
                <option value="<?php echo $CourseID["CourseID"]; ?>">
                    <?php echo $CourseID["CourseID"]; ?>
                </option>
                <?php
                    endwhile;
                ?>
            </select> <br> 
        </p>
        <p>                
            <label>SectionID</label>
            <input type = "text" name = "SectionID" required> <br>
        </p> 
        <p>
            <label>Term</label>
            <input type = "text" name = "term" required> <br>
        </p>  
        <p>          
            <label>ProfID</label> 
            <input type = "text" name = "prof"><br>
        </p> 
        <p>
            <label>Start date: </label>
            <input type="text" name="startdate"> <br>
        </p> 
        <p>
            <label>End date:</label> 
            <input type="text" name="enddate"><br> 
        </p> 
        <p>
            <input type="submit" value="submit" name="add_section">
        </p> 
    
    </form>
    </div>
</body>
</html>