<?php 
    $sql_getta = sprintf("SELECT classes_ta.taID, users.first_name, users.last_name, users.email
                               FROM (( users
                               INNER JOIN teacher_assistant ON users.ID = teacher_assistant.user_id)
                               INNER JOIN classes_ta ON classes_ta.taID = teacher_assistant.taID)
                               WHERE classes_ta.CourseID = '%s' AND classes_ta.SectionID = '%s'",
                               $_GET['search_CourseID'],$_GET['search_SectionID']);
    echo"$sql_getta";
    $result = mysqli_query($conn,$sql_getta); 
    

?>

<br><hr><br>
<div>
    <table>
        <thead>
            <th>taID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>ACTION</th>
        </thead>
        <tbody>
                <?php 
                        foreach($result as $member_row) {
                            $studentid = $member_row['taID'];
                            $firstname= $member_row['first_name'];
                            $lastname = $member_row['last_name'];
                            $email = $member_row['email'];    
                        
                    ?>
                    <tr>
                        <td><?= $studentid?></td>
                        <td><?= $firstname?></td>
                        <td><?= $lastname?></td>
                        <td><?= $email?></td>
                        <td><a href="?page=section.php&search_CourseID=<?=$CourseID?>&search_term=<?=$term?>&search_SectionID=<?=$SectionID?>&search_section=enter&assign=true&tadelete=<?=$studentid?>">DELETE</a>
                        </td>
                    </tr>
                </tbody>
                
                <?php  } ?>
        </tbody>
    </table>
    
</div>