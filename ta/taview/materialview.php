<?php 
$materialview = true;

$sql = "SELECT id, material_name, material_type, material_data 
        FROM course_materials 
        WHERE CourseID = ? AND SectionID = ? AND Term = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $CourseID, $SectionID, $Term);
            $stmt->execute();
            $result = $stmt->get_result(); 
            


?>

<?php if($materialview) {  ?>
            <table>
                <tr>
                    <th>Course Material</th>
                </tr>
                <tr>
                    <th>File Name</th>
                    <th>File Type</th>
                    <th>download link</th>
                </tr>
               <?php if ($result->num_rows > 0) {  
                        while($row = $result->fetch_assoc()) {?> 
                            <tr>
                                <td><?=$row['material_name']?></td>
                                <td><?=$row['material_type']?></td>
                                <td><a href='../professor/download.php?id=<?=$row['id']?>'>download</a></td>
                            </tr>
                        <?php } ?>
                <?php }?>
 <?php } ?>