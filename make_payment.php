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

$query = "SELECT cc.card_number FROM credit_cards cc WHERE cc.owner =" . $user_id . ";";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
$card_number = pg_fetch_row($result);
$card_number = $card_number[0];
pg_free_result($result);

$query = "INSERT INTO payments(task, payer, card) VALUES(" . $task_id . ", " . $user_id . ", " . $card_number . ");";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
pg_free_result($result);

$query = "UPDATE assignments SET is_done = 'TRUE' WHERE task =" . $task_id .";";
$result = pg_query($query) or die('Query failed: ' . pg_last_error());
pg_free_result($result);

header("Location: task.php?task_id=$task_id");

?>