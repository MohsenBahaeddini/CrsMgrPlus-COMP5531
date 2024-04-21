<?php
    require_once "logs.php"; 

    $conn = include "database.php";
    $is_invalid = FALSE;

    if(isset($_POST["submit"])){

        $sql = sprintf("SELECT * FROM users
                        WHERE username = '%s'",$_POST["username"]);
        
        $result = mysqli_query($conn, $sql);

        $user = $result->fetch_assoc();
        
        if($user){
            $user_id = $user["ID"];
            $sql2 = sprintf("SELECT role_id FROM access_role
                            WHERE user_id = '%s'",$user["ID"]);

            $result2 = mysqli_query($conn, $sql2);
            $userrole = mysqli_fetch_all($result2);
            
            $_SESSION["user_role"] = $userrole;

        }


        
        if($user){
            if($user["password"]==$_POST["password"]){
                session_start();
                $_SESSION["user_id"] = $user["ID"];
                $_SESSION["user_role"] = $userrole;
                $_SESSION["loggedin"] = true;
                $_SESSION["user_firstname"] = $user["first_name"];
                $_SESSION["user_lastname"] = $user["last_name"];
                

                // Log successful login
                writeToLog($conn, $user["ID"], "LOGIN_SUCCESS", "User logged in successfully.");


                header("Location: index.php");
                exit;
            } 
        }  

        $is_invalid = TRUE;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogIn</title>
    <style>
        body {
            /* background-color: whitesmoke; */
            background-color: #f5f5f5;
        }
        .container {
            width: 200px;
            margin: 0 auto;
            /* background-color: #ACA9BB; */
            background-color: #fff;
            color: #333;
            border-radius: 6px;
            box-shadow: 0px 0px 5px #666;
            padding: 20px;
            margin-top: 50px;
            font-weight: bold;

        } 
        .submitbutton {
            background-color: #333; 
            color: #fff; 
            font-weight: bold;
            padding: 10px 20px; 
            border: 2px solid ;
            border-radius: 5px;
            cursor: pointer; 
            font-size: 16px;
            margin-top: 5px; 
        }

    </style>
</head>
<body>
    <div class="container">
        <div style="display:flex; justify-content:center">
        <h1 style="color:#0E836A">
        CrsMgr+
        <!-- <img src="Screenshot (276).png" alt="course manager logo"> -->
        </h1>
        </div>
        <?php if($is_invalid): ?>

            <em>Invalid Login</em>
        <?php endif; ?>

            <form method="post">
                <label>Username:</label> <br>
                <input type="text" name="username"> <br>

                <label>Password:</label> <br>
                <input type="password" name="password"> <br>

                <input type="submit" name="submit" value="Log In" class="submitbutton">
            </form>
    </div>
</body>
</html>
