<?php 
    if(isset($_POST['tablename']) && isset($_POST['numbercol'])) {

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .main{
            margin: 60px 60px 60px 320px;
        }
        .sidenav{
            background-color: #fff; 
            width: auto;
            display: flex;
            flex-direction: column;
            height: 100vh; 
            padding:40px 20px"
        }
        .main-content {
            background-color: #f5f5f5;
        }
        .success {
            background-color: #00C897;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin: 10px;
        }
        form {
            display: flex;
            flex-direction: column;
            padding: 0px;

        }
        table {
            width: 100%;
            border-collapse: collapse;

        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #333;
            margin-top: 10px;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="main">
    <div style="padding:5px;">
        <form action="" method="post">
            <label for="tablename">Table name:</label><input type="text" name="tablename" required>
            <label for="numbercol">Number of Columns:</label><input type="number" min='0' name="numbercol" required> 
            <input type="submit" value="Go">
        </form>
    </div>

    <?php 
        if(isset($_POST['tablename']) && isset($_POST['numbercol'])) {
            $tablename = $_POST['tablename'];
            $numbercol = $_POST['numbercol'];
    ?>
        <div>
            <form action="?page=createtable.php&tablename=<?=$tablename?>&numbercol=<?=$numbercol?>" method="post">
                <table>
                    <thead>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Length/Values</th>
                    </thead>
                    <tbody>
                        <?php for($x = 0;$x < $numbercol;$x++) { ?>

                            <tr>
                                <td><input type="text" name="colname[]" id="colname"></td>
                                <td><select name="coltype[]" id="coltype">
                                    <option value="INT">INT</option>
                                    <option value="VARCHAR">VARCHAR</option>
                                    <option value="TEXT">TEXT</option>
                                    <option value="DATE">DATE</option>
                                </select>
                                </td>
                                <td><input type="text" name="colvalues[]" id="colvalues"></td>
                        
                            </tr>

                        <?php }?>
                        
                    </tbody>
                </table>
                <input type="submit" value="GO">
            </form> 
        </div>
    <?php } ?> 
    <?php 
       if(isset($_POST['colname'])) {
           $tablename = $_GET['tablename'];
           $numbercol = $_GET['numbercol'];
           $colname = $_POST['colname'];
           $coltype = $_POST['coltype'];
           $colvalue = $_POST['colvalues'];
           $usergroup = $_SESSION['usergroup'];
           $sql_create = "CREATE TABLE `".$usergroup."_".$tablename."`(";
           for($x=0;$x<$numbercol;$x++){
                if($x==0){
                    $sql_create .= "".$colname[$x]." ".$coltype[$x]."(".$colvalue[$x].") ";
                } else {
                    switch($coltype[$x]) {
                        case "INT":
                            $sql_create .= ", ".$colname[$x]." ".$coltype[$x]."(".$colvalue[$x].") ";
                            break;
                        case "VARCHAR":
                            $sql_create .= ", ".$colname[$x]." ".$coltype[$x]."(".$colvalue[$x].") ";
                            break;
                        case "TEXT":
                            $sql_create .= ", ".$colname[$x]." ".$coltype[$x];
                            break;
                        case "DATE":
                            $sql_create .= ", ".$colname[$x]." ".$coltype[$x];
                            break;

                    }
                }
           } 
           $sql_create .= ");";  
           if(mysqli_query($conn,$sql_create)){
        
    ?>  
                <div class="success" onclick="this.remove()">Sucessfull</div>   
    <?php
           }
        }
    ?>
    </div>
</body>
</html>