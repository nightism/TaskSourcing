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
				$post_time = $row[4];
				$start_time = $row[5];
				$end_time = $row[6];
				$category = pg_fetch_row(pg_query("SELECT name FROM categories WHERE id = " . $row[8] . ";"))[0];
				$region = pg_fetch_row(pg_query("SELECT name FROM regions WHERE id = " . $row[9] . ";"))[0];
				$salary = $row[10];
            	if ($owner_id == $user_id) {
            		// User's own task
            		echo "My Task";
            	} else {

            	}
            } else {
                echo "Error in fetching task";
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
			<div class="panel-footer panel-info">
				<div class="row">
		            <div class="col-md-6">Posted on <?php echo $post_time;?></div>
		            <div class="col-md-6">
		            	<form class="form-inline">
		            		<div class="form-group">
								<label for="bidAmount">Bid For This Task:</label>
								<input type="number" class="form-control" id="bidAmount" name="bidAmount" min="0">
							</div>
							<div class="form-group">
								<input type="submit" class="form-control" value="Bid">
							</div>
		            	</form>
	            	</div>
		        </div>
			</div>
		</div>
	</div>

</body>
</html>