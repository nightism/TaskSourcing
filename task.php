<?php
// Start the session
session_start();
if (isset($_SESSION["user_id"])) {
	$user_id = $_SESSION["user_id"];
} else {
	header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Tasource - Making task sourcing simple</title>
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/style.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/login-styling.css">

    <script src="../TaskSourcing/js/jquery-3.2.0.min.js"></script>
    <script src="../TaskSourcing/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
	<!-- include php -->
    <?php include "config/db-connection.php"; ?>

    <?php
    	if (isset($_GET["task_id"])) {
    		$task_id = $_GET["task_id"];
    		$query = "SELECT * FROM tasks t WHERE t.id = " . $task_id . ";";
    		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $row = pg_fetch_row($result);
            if ($row) {
            	$owner_id = $row[1];
            	if ($owner_id == $user_id) {
            		// User's own task
            		echo "My Task";
            	} else {
            		// Other user's task
            		echo "Not My Task";
            	}
            } else {
                echo "Error in fetching task";
            }
            pg_free_result($result);
    	}
    ?>
</body>
</html>