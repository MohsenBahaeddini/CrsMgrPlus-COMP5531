<?php


// If the user is not logged in, redirect to the login page.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: ../login.php");
    exit;
}

// Get the first role ID if no specific role is selected
// $role_id = isset($_GET['role']) ? $_GET['role'] : $_SESSION['user_role'][0];
/* if(isset($_GET['role'])){
    $_SESSION['selected_role'] = $_GET['role'];
} */
$role_id = $_SESSION['selected_role'];
// Display information based on role
$info = '';
switch ($role_id) {
    case 1:
        $info = "Welcome to the home page, admin!";
        break;
    case 2:
        $info = "Professor specific information.";
        break;
    case 3:
        $info = "Teaching Assistant specific information.";
        break;
    case 4:
        $info = "Course Student specific information.";
        break;
    default:
        $info = "General information.";
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>

</style>
</head>
<body>
    <section>
    <h1 style="margin-left:350px; padding: 60px 0 10px 0">Home</h1>
    <p style="margin-left:350px; padding: 10px 0 10px 0"><?php echo htmlspecialchars($info); ?></p>
    </section>
</body>
</html>
