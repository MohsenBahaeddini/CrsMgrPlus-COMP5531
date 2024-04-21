<?php  
    $studentid = $_SESSION['studentID'];
    $usergroup = $_SESSION['usergroup'];
    
    $pourcentsymbol = "%";
    $sql_showtable = sprintf("SHOW TABLES 
                              WHERE tables_in_".$db_name." LIKE '%s%s';",$usergroup,$pourcentsymbol);
    
    $resultshowtable = mysqli_query($conn, $sql_showtable);
    $insertview="";
    if(isset($_GET['insertview'])) {
        $insertview = $_GET['insertview'];
    }
    
    if(isset($_POST['insert'])) {
        $column = $_POST['field'];
        $value = $_POST['value'];

        $sql_insert = "INSERT INTO `".$insertview."` (";
        $sql_values = " VALUES (";
        for($x=0;$x < count($column);$x++) {
            if($x == count($column)-1){
                $sql_insert .= $column[$x].") ";
                $sql_values .= "'".$value[$x]."')";
            } else{
                $sql_insert .= $column[$x].", ";
                $sql_values .= "'".$value[$x]."', ";
            } 
        }
        $sql_completeinsert = $sql_insert.$sql_values;
        if(mysqli_query($conn,$sql_completeinsert)){
            echo '<div class="success" id="notification" onclick="this.remove()">INSERT sucesfull</div>';
        }
        
    }
    if(isset($_GET['tdelete'])){
        $table_name = substr($_GET['delete'],6);
        echo 
        "<div>
            <p>are you sure you want to delete table ".$table_name."?</p>
        </div>"; }
    
?>
<div >
<h1 style="padding: 60px 0 10px 340px">Database Structure</h1>

<table style="margin-left: 340px">
    <thead>
        <th>TABLE</th>
        <th>ACTION</th>
    </thead>

    <tbody >
        <?php 
            
            foreach($resultshowtable as $result_row) {
                $real_tablename = $result_row['Tables_in_projectdb'];
                $table_name = substr($real_tablename,6);

            ?>
                <tr >
                    <td><?="<a href='?page=structure.php&tableview=".$real_tablename."'>".$table_name."</a>"?></td>
                    <td><?="<a href='?page=structure.php&insertview=".$real_tablename."'>INSERT</a>
                            <a href='?page=structure.php&tdelete=".$real_tablename."'>DELETE</a>"?></td>
                </tr>
        <?php }?>
    </tbody>
        
</table>

<?php if(isset($_GET['tableview'])) {  
    $table_name = $_GET['tableview']; 

?>
    <h3>Table: <?=substr($_GET['tableview'],6)?> 
        <button><a href="?page=structure.php">Close table view</a></button>  
    </h3> 
    <?php 
        $sql_selectall = sprintf("SELECT *
                                  FROM %s;",$table_name);
        $sql_showcol = sprintf("SHOW COLUMNS FROM %s;",$table_name);
        
        $resultselectall = mysqli_query($conn, $sql_selectall);
        $resultshowcol = mysqli_query($conn, $sql_showcol); 
    ?>    
        <table>
            <thead>
                <?php foreach($resultshowcol as $row_col) {?>
                        <th><?=$row_col['Field']?></th>
                <?php }?>
                <th>Action</th>
            </thead>
            <tbody>
                <?php foreach($resultselectall as $row_selectall) {
                ?>
                        <tr>
                            <?php foreach($resultshowcol as $row_col){ 
                            $temp = $row_col['Field'];
                            $data = $row_selectall[$temp];    
                            ?>
                            <td><?=$data?></td>
                            <?php }?>
                            <td><a href="">update </a><a href=""> delete</a></td>
                        </tr>
                <?php }?>
            </tbody>
        </table>
<?php }?> 

<?php if($insertview) { 
        $table_name = $insertview;
         $sql_showcol = sprintf("SHOW COLUMNS FROM %s;",$table_name);
         echo$sql_showcol;
         $resultshowcol = mysqli_query($conn, $sql_showcol); 
?>    
    <form action="" method="post">
     <table>
        <thead>
            <th>COLUMN</th>
            <th>TYPE</th>
            <th>VALUE</th>
        </thead>

        <tbody>
            <?php foreach($resultshowcol as $col_name) { 
                $sql_get_col_data = sprintf("SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                                     WHERE TABLE_SCHEMA = '%s' 
                                     AND TABLE_NAME = '%s'
                                     AND COLUMN_NAME = '%s';",$db_name,$table_name,$col_name['Field']);
                $result = mysqli_query($conn,$sql_get_col_data) -> fetch_assoc();
                $col_data_type = $result['DATA_TYPE'];
                ?> 
                <tr>
                    <td><input type="text" name="field[]" value="<?=$col_name['Field']?>" readonly></td>
                    <td><?=$col_data_type?></td>
                    <td><input type="text" name="value[]" id="value"></td>
                </tr>
            <?php }?>
        </tbody>
     </table>
     <input type="submit" value="Go" name="insert">
     </form>
     </div>
<?php } ?>