<?php 
    $pourcentsymbol = "%";
    $sql_showtable = sprintf("SHOW TABLES 
                              WHERE tables_in_".$db_name." LIKE '%s%s';",$_GET['deletedbgroup'],$pourcentsymbol);
    
    $resultshowtable = mysqli_query($conn, $sql_showtable);
    foreach($resultshowtable as $result_row) {
        $real_tablename = $result_row['Tables_in_projectdb'];
        $sql_delete = "DROP TABLE ".$real_tablename.";";
        mysqli_query($conn,$sql_delete);
    }
?>