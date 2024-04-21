<?php 
    $conn = include("../database.php")

?>
<?php
    
    if(isset($_POST['updatebtn'])){
        $user_id = $_GET['search_id'];
        if(isset($_POST['upfirstname'])) {
            $newfirstname = mysqli_real_escape_string($conn, $_POST['upfirstname']); 
            $newLastName = mysqli_real_escape_string($conn, $_POST['uplastname']);
            $newpassword = mysqli_real_escape_string($conn, $_POST['uppassword']);
            $newemail = mysqli_real_escape_string($conn, $_POST['upemail']);
            $sql_update_first_name = " UPDATE users 
                                       SET first_name = '$newfirstname', last_name = '$newLastName', password = '$newpassword', email = '$newemail' 
                                       WHERE ID = $user_id";
            mysqli_query($conn, $sql_update_first_name);
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
        .content{
            padding: 60px 30px 10px 45px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Search users</h2> 
        <form action="" method="post">
            <label>Email</label> <input type="text" name="email" required>  <input type="submit" value="search user" name = "searchbtn" >
        </form>

        <hr> 
        <?php 
            if(isset($_POST["searchbtn"])) {
                $sql_select = sprintf ("SELECT * 
                                        FROM users
                                        WHERE email ='%s';",$_POST["email"]); 
                $result = mysqli_query($conn,$sql_select);
                $users = mysqli_fetch_assoc($result);
            }
        
        ?> 
        <?php 
            if(isset($_GET["search_id"])) {
                $sql_select = sprintf ("SELECT * 
                                        FROM users
                                        WHERE ID ='%s';",$_GET["search_id"]); 
                $result = mysqli_query($conn,$sql_select);
                $users = mysqli_fetch_assoc($result);
            }
        
        ?>
        <table style="margin-top: 10px;">
            <thead>
                <th>ID</th>
                <th>First name</th>
                <th>last name</th>
                <th>Username</th>
                <th>Password</th>
                <th>email</th>
                <th>action</th>
            </thead>
            <tbody>
                <?php 
                    if(isset($_POST["searchbtn"])) {
                        $user_id = $users['ID'];
                        $first_name = $users['first_name'];
                        $last_name = $users['last_name'];
                        $username = $users['username'];
                        $password = $users['password'];
                        $email = $users['email'];
                    
                ?>
                <tr>
                    <td><?= $user_id ?></td>
                    <td><?= $first_name ?></td>
                    <td><?= $last_name ?></td>
                    <td><?= $username ?></td>
                    <td><?= $password ?></td>
                    <td><?= $email ?></td>
                    
                    <?php echo"<td><a href='?page=existinguser.php&updateform=true&search_id=".$user_id."'>UPDATE</a></td>" ?>
                    
                </tr>
                <?php } elseif(isset($_GET['search_id'])){ 
                    $user_id = $users['ID'];
                    $first_name = $users['first_name'];
                    $last_name = $users['last_name'];
                    $username = $users['username'];
                    $password = $users['password'];
                    $email = $users['email'];    
                
                ?>
                <tr>
                    <td><?= $user_id ?></td>
                    <td><?= $first_name ?></td>
                    <td><?= $last_name ?></td>
                    <td><?= $username ?></td>
                    <td><?= $password ?></td>
                    <td><?= $email ?></td>
                    
                    <?php echo"<td><a href='?page=existinguser.php&updateform=true&search_id=".$user_id."'>UPDATE</a></td>" ?>
                    
                </tr> 
                <?php } ?>
                    

        
            </tbody>
        </table>

        <div class="formcontainer">
            <?php if(isset($_GET["updateform"])) { 
                      
            ?>
                <div> 
                    <h3 style="margin-top: 10px;"> UPDATE FORM </h3>
                    <form action="" method="post" class="updateform">
                        
                        <p><label>first name</label> <input type="text" name="upfirstname" value="<?php echo$first_name?>"> <br></p>
                        <p><label>last name </label> <input type="text" name="uplastname" value="<?php echo$last_name?>"> <br> </p>
                        
                        <p><label>password </label> <input type="text" name="uppassword" value="<?php echo$password?>"> <br></p>
                        <p><label>email</label> <input type="text" name="upemail" value="<?php echo$email?>"> <br></p>
                        <p><input type="submit" name="updatebtn"> </p>
                    </form>

                    </form>
                    </form>
                </div>
            <?php }?>
        </div>
    </div>
</body>
</html>


