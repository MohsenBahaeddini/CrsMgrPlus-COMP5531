<?php 
$conn = include("../database.php");
$sql_getstudentid = sprintf("SELECT StudentID
                             FROM student
                             WHERE user_id ='%s'",$_SESSION['user_id']);
$result = mysqli_query($conn,$sql_getstudentid);
$studentID = $result->fetch_assoc();



    if($studentID){
        $user_studentID = $studentID['StudentID'];
        $sql_getcourse = sprintf("SELECT * FROM classes
                                  WHERE studentID = '%s'",$user_studentID);
        $result_getcourse = mysqli_query($conn,$sql_getcourse);
        
    
    }

?>

<?php 
    foreach($result_getcourse as $row){
        $CourseID = $row['CourseID'];
        $SectionID = $row['SectionID'];
      
       
        
    
?>      
        <a href='?page=course_home.php&courseID=<?="$CourseID"?>&SectionID=<?="$SectionID"?>'><?="$CourseID"?></a><br>
<?php } ?>