<?php
require_once '../database.php';

// Function to fetch all groups
function getAllGroups($conn) {
    $sql = "SELECT sg.group_id, sg.group_name, sg.group_leader_sid, gc.course_id
            FROM student_groups sg
            LEFT JOIN group_of_course gc ON sg.group_id = gc.group_id";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Error: " . mysqli_error($conn);
        exit;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Call the function and store the results in $groups
$groups = getAllGroups($conn);


// // Check if the 'delete' parameter is set in the URL query string
 if (isset($_GET['delete'])) {
     $groupIdToDelete = $_GET['delete'];
    
   // SQL to delete the group
     $sql = "DELETE FROM student_groups WHERE group_id = ?";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $groupIdToDelete);
        
        if (mysqli_stmt_execute($stmt)) {
           // Deletion was successful, now redirect to the same page without the delete parameter
            mysqli_stmt_close($stmt);
           header('Location: ' . strtok($_SERVER["REQUEST_URI"], '?'));
            exit();
       } else {
             echo "Error executing statement: " . mysqli_stmt_error($stmt);
         mysqli_stmt_close($stmt);
        }
    } else {
       echo "Error preparing statement: " . mysqli_error($conn);
    }
}
// // If not a delete operation or after deletion, fetch groups
 $groups = getAllGroups($conn);

if(isset($_GET['studentdelete'])){
    $sql_studdelete = sprintf("DELETE FROM member_of_group WHERE group_id='%s' AND student_id='%s'",
                            $_GET['viewgroup'],$_GET['studentdelete']);
    echo$sql_studdelete;
    if(mysqli_query($conn,$sql_studdelete)){
        display_success();
    }
}
if(isset($_GET['deletefilesgroup'])){
    $sql_deletefile = sprintf("DELETE FROM group_files 
                       WHERE group_id='%s';",$_GET['deletefilesgroup']);
    if(mysqli_query($conn,$sql_deletefile)){
        display_success();
    }
}
if(isset($_GET['deletedbgroup'])){
    include('deletedbgroup.php');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Group Management</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;

        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        form {
            /* margin-left: 350px; */
            margin: 10px 347px 10px 350px;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            color: #333;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2 style="padding: 60px 30px 10px 350px">Search Group</h2> 
        <form action="" method="get">
            <input type="hidden" name="page" value="existinggroup.php">
            <label>GroupID</label> <input type="text" name="viewgroup" required>  <input type="submit" value="true" name = "searchbtn" >
        </form>

    <table style="margin-left: 350px;">
        <tr>
            <th>Group ID</th>
            <th>Group Name</th>
            <th>Group Leader ID</th>
            <th>Course ID</th>
            <th>Action</th> 
        </tr>
        <?php foreach ($groups as $group): ?>
            <tr>
                <td><?= htmlspecialchars($group['group_id']); ?></td>
                <td><?= htmlspecialchars($group['group_name']); ?></td>
                <td><?= htmlspecialchars($group['group_leader_sid']); ?></td>
                <td><?= htmlspecialchars($group['course_id']); ?></td>
                <td>
                <a href="?page=existinggroup.php&delete=<?= $group['group_id']; ?>" onclick="return confirm('Are you sure you want to delete this group?');">Delete</a>
                <a href="?page=existinggroup.php&viewgroup=<?=$group['group_id'];?>">Members</a> |
                <a href="?page=existinggroup.php&deletedbgroup=<?=$group['group_id'];?>">Delete Database</a> |
                <a href="?page=existinggroup.php&deletefilesgroup=<?=$group['group_id'];?>">Delete Files</a>
                 </td> 
            </tr>
        <?php endforeach; ?>
    </table>


    
    <?php if(isset($_GET['viewgroup'])) { 
        $currentgroup  = $_GET['viewgroup'];
        $sql_getmember = sprintf("SELECT student.StudentID,users.first_name,users.last_name,users.email 
                                  FROM ( (users 
                                  INNER JOIN student ON users.ID = student.user_id) 
                                  INNER JOIN member_of_group ON student.StudentID = member_of_group.student_id) 
                                  WHERE member_of_group.group_id = '%s';",$_GET['viewgroup']);
        $result_getmember = mysqli_query($conn,$sql_getmember);
    ?>

        <div style="margin-left: 350px; padding-top:10px">
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
                        <td><a href="?page=existinggroup.php&viewgroup=<?=$group['group_id'];?>&studentdelete=<?=$studentid?>">DELETE</a></td>
                    </tr>
                </tbody>
                
                <?php  } ?>
            </table> 
           <button style="margin-top: 5px;"><a href="?page=existinggroup.php&viewgroup=<?=$currentgroup;?>&add=true">ADD</a></button>

            <?php if(isset($_GET['add'])){ ?>
                
                <div style="margin-top: 5px;">
                    <form action="" method="post">
                        <label for="studentID">Student ID:</label><input type="text" name="studentid" id="studentid"><input type="submit" value="Add" name="addstudent">
                    </form>
                </div>
            <?php }?>
            
            <?php 
            if(isset($_POST['addstudent'])){
                $sql_addmember = sprintf("INSERT INTO `member_of_group` (student_id, group_id)
                                  VALUES ('%s','%s')",$_POST['studentid'],$currentgroup);
                if(mysqli_query($conn,$sql_addmember)){
                    display_success();
                }
            }
            ?>
    </div>
    
    <?php }?>
    
</body>
</html>
