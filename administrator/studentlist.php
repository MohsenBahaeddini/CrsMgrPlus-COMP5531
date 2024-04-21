<?php 
    $sql_getstudent = sprintf("SELECT classes.StudentID, users.first_name, users.last_name, users.email
                               FROM (( users
                               INNER JOIN student ON users.ID = student.user_id)
                               INNER JOIN classes ON classes.StudentID = student.StudentID)
                               WHERE classes.CourseID = '%s' AND classes.SectionID = '%s'",
                               $_GET['search_CourseID'],$_GET['search_SectionID']);
    
    $result = mysqli_query($conn,$sql_getstudent); 
    

?>

<br><hr><br>
<div>
    <table>
        <thead>
            <th>StudentID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>ACTION</th>
        </thead>
        <tbody>
                <?php 
                        foreach($result as $member_row) {
                            $studentid = $member_row['StudentID'];
                            $firstname= $member_row['first_name'];
                            $lastname = $member_row['last_name'];
                            $email = $member_row['email'];    
                        
                    ?>
                    <tr>
                        <td><?= $studentid?></td>
                        <td><?= $firstname?></td>
                        <td><?= $lastname?></td>
                        <td><?= $email?></td>
                        <td><a href="?page=section.php&search_CourseID=<?=$_GET['search_CourseID']?>&search_term=<?=$_GET["search_term"]?>&search_SectionID=<?=$_GET['search_SectionID']?>&search_section=enter&classes=true&delete=<?=$studentid?>">DELETE</a>
                        </td>
                    </tr>
                </tbody>
                
                <?php  } ?>
        </tbody>
    </table>
    
</div>