<?php
$conn = include("../database.php");

if (isset($_GET['CourseID'])) {
    $courseID = $_GET['CourseID'];
    $stmt = $conn->prepare("SELECT faq_id, faq_content, faq_subject FROM faq WHERE CourseID = ?");
    $stmt->bind_param("s", $courseID);
    $stmt->execute();
    $result = $stmt->get_result();
    $faqs = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($faqs as $faq) {
        echo "<div class='faq-item'>";
        echo "<h4>FAQ Question: " . $faq['faq_subject'] . "</h4>";
        echo "<p>FAQ Answer: " . $faq['faq_content'] . "</p>";
        echo "</div>";
    }
}
?>
