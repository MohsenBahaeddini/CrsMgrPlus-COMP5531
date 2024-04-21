<?php 

    $conn = include("../database.php");
    $sql_roles = "SELECT role_name FROM roles";
    $allRoleName = mysqli_query($conn,$sql_roles); 
    $success = false;
    
    if(isset($_POST["submit"])){
        
        $sql_insertuser = sprintf("INSERT INTO users (first_name, last_name,email)
                                   VALUES ('%s','%s','%s')",$_POST['firstname'],$_POST['lastname'],$_POST['email']);
        if(mysqli_query($conn,$sql_insertuser)){
            $success = true;
        }
        $user_id = mysqli_insert_id($conn); 
        $username = genUserName($_POST['firstname'],$_POST['lastname'],$user_id);
        $password = genPassWord(8); 
        

        $sql_update = "UPDATE users 
                       SET username = '$username', password = '$password'
                       WHERE ID = '$user_id'";
        mysqli_query($conn,$sql_update);
        $sql_insertrole = "INSERT INTO access_role
                           VALUES ()";
        switch($_POST['rolename']){
            case 'administrator':
                $sql_insertrole = "INSERT INTO access_role
                                   VALUES ('1',$user_id)";
                mysqli_query($conn,$sql_insertrole);
            break;
            case 'professor':
                $sql_insertrole = "INSERT INTO access_role
                                   VALUES ('2',$user_id)";
                $sql_insertprof = "INSERT INTO professor
                                VALUES ('$username',$user_id)";
            
                mysqli_query($conn,$sql_insertrole);
                mysqli_query($conn,$sql_insertprof);
            break;
            case 'teacher assistant':
                $sql_insertrole = "INSERT INTO access_role
                                   VALUES ('3',$user_id)";
                mysqli_query($conn,$sql_insertrole);
                $sql_insertta = "INSERT INTO teacher_assistant
                                      VALUES ('$username',$user_id)";
                mysqli_query($conn,$sql_insertta);
            break;
            case 'course student':
                $sql_insertrole = "INSERT INTO access_role
                                   VALUES ('4',$user_id)";
                mysqli_query($conn,$sql_insertrole);
                $sql_insertstudent = "INSERT INTO student
                                      VALUES ('$username',$user_id)";
                mysqli_query($conn,$sql_insertstudent);
            break;
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Document</title>
    <link ref="stylesheet" href="styles/style.css">
    <style> 
        .submitbtn {
            margin-top: 5px;
        }
        .firstnameinput {
            margin-top: 5px;
        }
        .lastnameinput {
            margin-top: 5px;
        }
        .email_input {
            margin-top: 5px;
        }
        .main{
           margin-left:350px;
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
    
    <div class = "main">
    <?php 
        if($success) {
            display_success();
        }
    ?>
    <h1 style="padding:60px 0 10px 0">Create new user</h1>
    <form method="post">
        <label>Role</label> 
        <select name="rolename">
            <?php 
                while($rolename = mysqli_fetch_array($allRoleName,MYSQLI_ASSOC)):; 
            ?>
            <option value="<?php echo $rolename['role_name']; ?>">
                <?php echo $rolename['role_name'] ?>
            </option>
            <?php 
                endwhile;
            ?>
        </select>
        <br> 
        <p><label>First name</label> <input type="text" name="firstname" class="firstnameinput" required> <br></p>
        <p><label>Last name</label> <input type="text" name="lastname" class="lastnameinput" required> <br></p>
        <p><label>Email address</label> <input type="text" name="email" class="email_input" required> <br></p>
        <input type="submit" value="Create User" name="submit" class="submitbtn">

    </form>
    <?php 
    
        if(isset($_POST["submit"])) {
            echo $_POST["firstname"]." ".$_POST["lastname"];
        }

    ?>

    </div>
</body>
</html>