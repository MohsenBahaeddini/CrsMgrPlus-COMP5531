<?php 
    $conn = include("../database.php");
    
    if(isset($_POST["Course_ID"]) && isset($_POST["Course_name"])){
        $sql = sprintf("INSERT INTO course
                        VALUES ('%s','%s','%s')",$_POST["Course_ID"],$_POST["Course_name"],$_POST["course_desc"]);
        try {
            mysqli_query($conn,$sql);
        }
        catch(mysqli_sql_exception) {
            echo"invalid data entry";
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

<div class = "inputform">

    <h1>Add Course</h1>
    <form action="" method="POST">
          
        <label>Course ID</label> <br>
        <input  type="text" name="Course_ID" required> <br>

        <label>Course Name</label> <br>
        <input  type="text" name="Course_name" required> <br>

        <label>Course Description</label> <br>
        <textarea id="course_desc" name="course_desc" rows="4" cols="50"></textarea> <br>

        <input type="submit" name="submit" value="Add Course" class="submitbutton">

    </form>

</div>    

<div>
    

</div>
</body>
</html> 