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
            	$title = $row[2];
            	$desc = nl2br($row[3]);
				$post_time = (new DateTime($row[4]))->format('Y-m-d H:i:s');
				$start_time = (new DateTime($row[5]))->format('Y-m-d H:i:s');
				$end_time = (new DateTime($row[6]))->format('Y-m-d H:i:s');
				$category = pg_fetch_row(pg_query("SELECT name FROM categories WHERE id = " . $row[8] . ";"))[0];
				$region = pg_fetch_row(pg_query("SELECT name FROM regions WHERE id = " . $row[9] . ";"))[0];
				$salary = $row[10];

				$bidForm = "";
            	if ($owner_id == $user_id) {
            		$bidForm = "<form class='form-inline' action='edit-task.php' method='get'><div class='form-group' style='float: right;'><input type='submit' class='form-control' value='Edit'></div><input type='hidden' name='task_id' value='" . $task_id . "'></form>";
            	} else {
            		$biddingCheckQuery = "SELECT * FROM biddings b WHERE b.bidder = " . $user_id . " AND b.task = "  . $task_id . ";";
            		$biddingCheckResult = pg_query($biddingCheckQuery) or die('Query failed: ' . pg_last_error());
            		if (pg_fetch_row($biddingCheckResult)) {
            			$bidForm = "<form class='form-inline' action='cancelBid.php' method='get'><div class='form-group' style='float: right;'><input type='submit' class='form-control' value='Cancel Bidding'></div><input type='hidden' name='task_id' value='" . $task_id . "'></form>";
            		} else {
            			$bidForm = "<form class='form-inline' action='bid.php' method='get'><div class='form-group' style='float: right;'><input type='submit' class='form-control' value='Bid'></div><input type='hidden' name='task_id' value='" . $task_id . "'></form>";
            		}
            	}
            } else {
                echo "Error in fetching task";
            }
            pg_free_result($result);
    	} else {
    		header("Location: tasklist.php");
    	}
    ?>

    <?php
        $query = "SELECT task, assignee FROM assignments WHERE task=" . $task_id . ";";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        if ($row = pg_fetch_row($result)) {
            $bidders = "<p>This task is assigned to: ";
            $query = "SELECT u.name, u.id FROM users u WHERE u.id = " . $row[1] . ";";
            pg_free_result($result);
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $assignee_info = pg_fetch_row($result);
            $bidders = $bidders . $assignee_info[0] . "</p>";
            pg_free_result($result);
        } else {
            pg_free_result($result);
            $query = "SELECT u.name, u.id FROM users u INNER JOIN biddings b on u.id = b.bidder WHERE b.task = " . $task_id . ";";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
            $bidders = "";
            while ($row = pg_fetch_row($result)) {
                if ($owner_id == $user_id) {
                    $bidders = "<p>test0</p>" . $bidders . "<p>" . $row[0] . "</p><form class='form-inline' action='assignTask.php' method='get'><div class='form-group' style='float: left;'><input type='submit' class='form-control' value='Assign'></div><input type='hidden' name='task_id' value='" . $task_id . "'><input type='hidden' name='assignee' value='" . $row[1] . "'></form>";
                } else {
                    $bidders = $bidders . "<p>" . $row[0] . "</p>";
                }
            }
            pg_free_result($result);
        }

    ?>
    <br>
	<div class="container">
		<div class="panel panel-info">
			<div class="panel-heading"><h3><?php echo $title;?></h3></div>
			<div class="panel-body">
				<h4>Description: </h4><br>
				<p><?php echo $desc;?></p><br>
				<h5>Category:</h5>
				<p><?php echo $category;?></p>
				<h5>Region:</h5>
				<p><?php echo $region;?></p>
				<h5>Start Time:</h5>
				<p><?php echo $start_time;?></p>
				<h5>End Time:</h5>
				<p><?php echo $end_time;?></p>
				<h5>Salary:</h5>
				<p><?php echo $salary;?></p>
			</div>
            <div class="panel-body">
                <h4>Bidders: </h4>
                <p><?php echo $bidders;?></p>
            </div>
			<div class="panel-footer">
				<div class="row">
		            <div class="col-md-6"><strong>Posted on </strong><?php echo $post_time;?></div>
		            <div class="col-md-6">
		            	<?php echo $bidForm; ?>
	            	</div>
		        </div>
			</div>
		</div>
	</div>
</body>
</html>