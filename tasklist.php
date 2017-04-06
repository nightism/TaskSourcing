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
    <title>Tasource - View Tasks</title>
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/style.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/new-task-styling.css">
    <link rel="stylesheet" href="../TaskSourcing/css/bootstrap-datetimepicker.min.css">

    <script src="../TaskSourcing/js/jquery-3.2.0.min.js"></script>
    <script src="../TaskSourcing/bootstrap/js/bootstrap.min.js"></script>
    <script src="../TaskSourcing/js/jquery.ns-autogrow.min.js"></script>
    <script src="../TaskSourcing/js/bootstrap-datetimepicker.min.js"></script>
</head>
<body>
    <!-- include php -->
    <?php include "config/db-connection.php"; ?>

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

        <!-- page content -->
        <div class="container-fluid">

            <!-- panel -->
            <div class="panel new-task-panel">
                <form action="" id="findForm">
                    <!-- panel heading -->
                    <div class="panel-heading">
                        <h2 class="new-task-form-title">View Tasks</h2>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                <label class="col-sm-3 control-label">Keywords</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="title">
                                </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Category</label>
                                    <div class="col-sm-6">
                                        <select name="category" class="form-control">
                                            <option value="">Select Category</option>
                                            <?php
                                                $query = "SELECT c.name FROM categories c ORDER BY c.name";
                                                $result = pg_query($query) or die('Query failed: ' . pg_last_error());

                                                while ($row = pg_fetch_row($result)){
                                                    echo "<option value=\"".$row[0]."\">".$row[0]."</option><br>";
                                                }
                                                pg_free_result($result);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Region</label>
                                    <div class="col-sm-6">
                                        <select name="region" class="form-control">
                                            <option value="">Select Region</option>
                                            <?php
                                                $query = "SELECT r.name FROM regions r ORDER BY r.name";
                                                $result = pg_query($query) or die('Query failed: ' . pg_last_error());

                                                while ($row = pg_fetch_row($result)){
                                                    echo "<option value=\"".$row[0]."\">".$row[0]."</option><br>";
                                                }
                                                pg_free_result($result);
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <!-- task duration -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Start After</label>
                                        <div class="col-sm-6">
                                            <div class="input-group date" id="start-datetimepicker">
                                            <input type="text" class="form-control" name="start_time">
                                                <div class="input-group-addon">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                                </div>
                                            </div>                                
                                        </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">End Before</label>
                                        <div class="col-sm-6">
                                            <div class="input-group date" id="end-datetimepicker">
                                            <input type="text" class="form-control" name="end_time">
                                                <div class="input-group-addon">
                                                <i class="glyphicon glyphicon-calendar"></i>
                                                </div>
                                            </div>                                
                                        </div>
                                </div>
                            </div>
                        </div>

                        <!-- task salary range -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Salary Lowerbound</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="lowerbound">
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label">Salary Upperbound</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" name="upperbound">
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="row" style = "float: right;">
                                    <div class="form-group row">
                                        <input type="submit" class="btn-primary btn" id="findBtn" name="forFind" value="Find" >
                                        <a href="tasklist.php" class="btn-default btn">Cancel</a>
                                    </div>
                            </div>
                        </div>

                        <?php
                            if(isset($_GET['forFind']))
                            {
                                $title = $_GET['title'];
                                $category = $_GET['category'];
                                $region = $_GET['region'];
                                $start_time = $_GET['start_time'];
                                $end_time = $_GET['end_time'];
                                $lowerbound = $_GET['lowerbound'];
                                $upperbound = $_GET['upperbound'];

                                $query = "SELECT t.title, t.description, t.start_time, t.end_time, c.name, r.name, t.salary, t.id
                                          FROM tasks t INNER JOIN categories c ON t.category = c.id INNER JOIN regions r ON t.region = r.id
                                          WHERE 1 = 1 ";

                                if (trim($title)) {
                                    $query .= " AND UPPER(t.title) like UPPER('%". $title ."%') ";
                                }

                                if (trim($category)) {
                                    $query .= " AND c.name = '". $category ."' ";
                                }

                                if (trim($region)) {
                                    $query .= " AND r.name = '". $region ."' ";
                                }

                                if (trim($start_time)) {
                                    $query .= " AND t.start_time >= timestamp '". $start_time ."' ";
                                }

                                if (trim($end_time)) {
                                    $query .= " AND t.end_time <= timestamp '". $end_time ."' ";
                                }

                                if (trim($lowerbound)) {
                                    $query .= " AND t.salary >= ". $lowerbound ." ";
                                }

                                if (trim($upperbound)) {
                                    $query .= " AND t.salary <= ". $upperbound ." ";
                                }

                                $query .= " ORDER BY t.title ";

                                // echo $query;
                                $result = pg_query($query) or die('Query failed: ' . pg_last_error());
                            } else {
                                $query = "SELECT t.title, t.description, t.start_time, t.end_time, c.name, r.name, t.salary, t.id FROM tasks t INNER JOIN categories c ON t.category = c.id INNER JOIN regions r ON t.region = r.id";
                                $result = pg_query($query) or die('Query failed: ' . pg_last_error());
                            }
                        ?>

                        <table class="table table-striped">
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Category</th>
                                <th>Region</th>
                                <th>Salary</th>
                            </tr>
                            <?php
                                while ($row = pg_fetch_row($result)){
                                    echo "<tr class='taskLink' tid='" . $row[7] . "' onclick='viewTask(this)' style='cursor: pointer;'>";
                                    echo "<td>" . $row[0] . "</td>";
                                    echo "<td>" . $row[1] . "</td>";
                                    echo "<td>" . $row[2] . "</td>";
                                    echo "<td>" . $row[3] . "</td>";
                                    echo "<td>" . $row[4] . "</td>";
                                    echo "<td>" . $row[5] . "</td>";
                                    echo "<td>" . $row[6] . "</td>";
                                    echo "</tr>";
                                }
                            ?>

                        </table>

                    </div>

                </form>
                <form action='task.php' id='viewTask'><input id='task_id' type='hidden' name='task_id'></form>
            </div>
        </div>
    </div>
    <?php
    pg_close($dbconn);
    ?>
    <script src="../TaskSourcing/js/find-task.js"></script>
</body>
</html>
