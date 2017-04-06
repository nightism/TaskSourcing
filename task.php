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
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/new-task-styling.css">
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
        $query = "SELECT task, assignee, is_done FROM assignments WHERE task=" . $task_id . ";";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        if ($row = pg_fetch_row($result)) {
            pg_free_result($result);
            if ($owner_id == $user_id || $row[1] == $user_id) {
                if (trim($row[2]) == "t") {
                    $query = "SELECT * FROM payments WHERE task=" . $task_id ."";
                    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
                    $payment_info = pg_fetch_row($result);
                    $payment = "The payment has been made<br>Details: ";
                    $payment .= "Receipt: " . $payment_info[0] . "<br>Card number: " . $payment_info[3]; 

                    pg_free_result($result);

                } else {
                    $payment = "The payment haven't been made<br>";
                    $payment .= "<form class='form-inline' action='make_payment.php' method='get'><div class='form-group' style='float: left;'><input type='submit' class='form-control' value='Task is finished and Make Payment'></div><input type='hidden' name='task_id' value='" . $task_id . "'></form><br><br>";
                }
            }
            
            $bidders = "<p>This task is assigned to: ";
            $query = "SELECT u.name, u.id FROM users u WHERE u.id = " . $row[1] . ";";
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
                    $bidders .=  $row[0] . "<br><form class='form-inline' action='assignTask.php' method='get'><div class='form-group' style='float: left;'><input type='submit' class='form-control' value='Assign'></div><input type='hidden' name='task_id' value='" . $task_id . "'><input type='hidden' name='assignee' value='" . $row[1] . "'></form>";
                } else {
                    $bidders = $bidders . "<p>" . $row[0] . "</p>";
                }
            }
            pg_free_result($result);
        }

    ?>



    <!-- navigation bar -->
    <nav class="navbar navbar-inverse navigation-bar navbar-fixed-top">
        <div class="container navbar-container">
            <div class="navbar-header pull-left"><a class="navbar-brand" href="">Tasource</a></div>
            <div class="nav navbar-nav navbar-form">
                <div class="input-icon">
                    <i class="glyphicon glyphicon-search search"></i>
                    <input type="text" placeholder="Type to search..." class="form-control search-form" tabindex="1">
                </div>
            </div>
            <div class="collapse navbar-collapse pull-right">
                <ul class="nav navbar-nav">
                    <li><a href="">View task</a></li>
                    <li><a href="">Post task</a></li>
                    <li><a href="">Profile</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- content -->
    <div class="content-container container">

        <!-- page heading -->
        <div class="page-heading">
            <ol class="breadcrumb">
                <li><a href="tasklist.php">Home</a></li>
                <li>View task</li>
            </ol>
            <h1>View Tasks</h1>
        </div>

        <div class="container-fluid">

            <!-- panel -->
            <div class="panel new-task-panel">
                <!-- panel heading -->
                <div class="panel-heading">
                    <h2 class="new-task-form-title"><?php echo $title;?></h2>
                </div>

                <!-- panel body -->
                <div class="panel-body">

                    <div class="row"><h4>Description:</h4></div>
                    <div class="row"><p><?php echo $desc;?></p></div>
                    <div class="row"><h5>Category:</h5></div>
                    <div class="row"><p><?php echo $category;?></p></div>
                    <div class="row"><h5>Region:</h5></div>
                    <div class="row"><p><?php echo $region;?></p></div>
                    <div class="row"><h5>Start Time:</h5></div>
                    <div class="row"><p><?php echo $start_time;?></p></div>
                    <div class="row"><h5>End Time:</h5></div>
                    <div class="row"><p><?php echo $end_time;?></p></div>
                    <div class="row"><h5>Salary:</h5></div>
                    <div class="row"><p><?php echo $salary;?></p></div>
                </div>
                <div class="panel-body">
                    <h4>Bidders: </h4>
                    <p><?php echo $payment; ?></p>
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
    </div>

</body>
</html>