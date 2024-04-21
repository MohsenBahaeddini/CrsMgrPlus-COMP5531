<?php 
    
    if(isset($_POST['sqlquery'])){
        $sql_query = $_POST['sqlquery'];
        echo$sql_query;
        if(mysqli_query($conn,$sql_query)){
            echo"sucess";
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
        form {
            padding: 10px 40px 20px 40px;
            margin-right:60px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        textarea {
            padding: 10px 0px;
            margin: 10px 0;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border:none;
            border-radius: 5px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <div style="margin-left: 320px; padding: 60px 0">
        <form action=""  method="post">
            <label for="sqlquery"><h3>Run SQL QUERY on your database</h3></label><br>
            <textarea name="sqlquery" id="sqlquery" cols="30" rows="10" required></textarea><br>
            <input type="submit" value="Run">
        </form>
    </div>
</body>
</html>