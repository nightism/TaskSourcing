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
    	$query = "SELECT t.title, t.id FROM tasks t;";
    	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
    	$table_content = "<table>";
    	while ($row = pg_fetch_row($result)) {
    		$table_content .= "<tr><td class='taskLink' tid='" . $row[1] . "'>" . $row[0] . "</a></td></tr>";
    	}
    	echo $table_content . "<form action='task.php' id='viewTask'><input id='task_id' type='hidden' name='task_id'></form></table>";
    ?>
    <script type="text/javascript" src="js/tasklist.js"></script>
</body>
</html>