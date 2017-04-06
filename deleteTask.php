<?php
    if(isset($_GET['delete']) {
        $$task_id = $_GET['t_id'];
        $query = "DELETE FROM tasks WHERE id=" . $task_id . ";";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        pg_free_result($result);
        header("Location: tasklist.php");
        echo "<script>window.location = '/TaskSourcing/tasklist.php';</script>";
        exit();
    }
?>