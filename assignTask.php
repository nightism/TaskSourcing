<?php include "config/db-connection.php"; ?>
<?php
// Start the session
session_start();
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
} else {
    header("Location: login.php");
    exit;
}

$task_id = $_GET["task_id"];
$assignee = $_GET["assignee"];

$query = "INSERT INTO assignments(assignee, task) VALUES(" . $assignee . ", " . $task_id . ");";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
pg_free_result($result);
header("Location: task.php?task_id=$task_id");

?>