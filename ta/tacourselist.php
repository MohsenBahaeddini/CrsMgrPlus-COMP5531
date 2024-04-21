<?php 

$conn = include("../database.php");
$sql_gettaid = sprintf("SELECT taID
                             FROM teacher_assistant
                             WHERE user_id ='%s'",$_SESSION['user_id']);
$result = mysqli_query($conn,$sql_gettaid);
$taID = $result->fetch_assoc();



    if($taID){
        $user_taID = $taID['taID'];
        $sql_getcourse = sprintf("SELECT * FROM classes_ta
                                  WHERE taID = '%s'",$user_taID);
        $result_getcourse = mysqli_query($conn,$sql_getcourse);
        
    
    }

?>

<?php 
    foreach($result_getcourse as $row){
        $CourseID = $row['CourseID'];
        $SectionID = $row['SectionID']; 
        $Term = $row['Term'];  
?>      
        <a href='?page=tacoursehome.php&courseID=<?="$CourseID"?>&SectionID=<?="$SectionID"?>&Term=<?="$Term"?>'><?="$CourseID"?></a><br>
<?php } ?>