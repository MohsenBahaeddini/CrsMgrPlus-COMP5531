<?php 
    $studentid = $_SESSION['studentID'];
    
    $sql_getusergroup = sprintf("SELECT goc.group_id
                                 FROM group_of_course goc
                                 JOIN member_of_group mog ON goc.group_id=mog.group_id
                                WHERE goc.course_id='%s' AND mog.student_id='%s';",$CourseID,$studentid);
    $result_usergroup = mysqli_query($conn,$sql_getusergroup);
   
        
    
    $usergroup = mysqli_fetch_array($result_usergroup)[0];
    $_SESSION['usergroup'] = $usergroup;
    $sql_getmember = sprintf("SELECT student.StudentID,users.first_name,users.last_name,users.email 
                              FROM ( (users 
                              INNER JOIN student ON users.ID = student.user_id) 
                              INNER JOIN member_of_group ON student.StudentID = member_of_group.student_id) 
                              WHERE member_of_group.group_id = '%s';",$usergroup);
    $result_getmember = mysqli_query($conn,$sql_getmember);
    
    
    
    

?>
<!-- <h2>Course Group 
    <button class="viewbtn">
            <?php echo"<a href='?page=course_home.php&courseID="
            .$CourseID."&SectionID=".$SectionID."'>Close View Course Group</a>"?>
        </button> </h2>
<hr> -->
<!-- <p>Your group info</p> -->

    <div>
            <table>
                <thead>
                    <th>StudentID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </thead>
                <tbody>
                    <?php 
                        foreach($result_getmember as $member_row) {
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
                    </tr>
                </tbody>
                
                <?php  } ?>
            </table>
    </div>


    <button class="viewbtn"><a href="../database/project_database.php">Database Manager</a> </button>
