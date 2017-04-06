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
    <title>Tasource - New Task</title>
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/style.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/new-task-styling.css">
    <link rel="stylesheet" href="../TaskSourcing/css/bootstrap-datetimepicker.min.css">

    <script src="../TaskSourcing/js/jquery-3.2.0.min.js"></script>
    <script src="../TaskSourcing/bootstrap/js/bootstrap.min.js"></script>
    <script src="../TaskSourcing/js/jquery.ns-autogrow.min.js"></script>
    <script src="../TaskSourcing/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../TaskSourcing/js/new-task.js"></script>
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
                    <li><a href="/TaskSourcing/tasklist.php">View task</a></li>
                    <li><a href="/TaskSourcing/new_task.php">Post task</a></li>
                    <?php 
                        if ($_SESSION['is_admin'] == "t") {
                            echo "<li><a href='/TaskSourcing/dashboard.php'>Dashboard</a></li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- content -->
    <div class="content-container container">

        <!-- page heading -->
        <div class="page-heading">
            <ol class="breadcrumb">
                <li><a href="/">Home</a></li>
                <li>New task</li>
            </ol>
            <h1>New Task</h1>
        </div>

        <!-- page content -->
        <div class="container-fluid">

            <!-- panel -->
            <div class="panel new-task-panel">
                <form action="">
                    <!-- panel heading -->
                    <div class="panel-heading">
                        <h2 class="new-task-form-title">New Task Information</h2>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        
                        <!-- task name -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Task Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="title" required="true">
                            </div>
                        </div>

                        <!-- task category -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Task Category</label>
                            <div class="col-sm-6">
                                <select name="category" class="form-control" required="true">
                                    <option value="">Select Category</option>
                                    <?php
                                        $query = "SELECT c.id, c.name FROM categories c ORDER BY c.name";
                                        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

                                        while ($row = pg_fetch_row($result)){
                                            echo "<option value=\"".$row[0]."\">".$row[1]."</option><br>";
                                        }
                                        pg_free_result($result);
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- task region -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Task Region</label>
                            <div class="col-sm-6">
                                <select name="region" class="form-control" required="true">
                                    <option value="">Select Region</option>
                                    <?php
                                        $query = "SELECT r.id, r.name FROM regions r ORDER BY r.name";
                                        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

                                        while ($row = pg_fetch_row($result)){
                                            echo "<option value=\"".$row[0]."\">".$row[1]."</option><br>";
                                        }
                                        pg_free_result($result);
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- task description -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-6">
                                <textarea name="description" class="form-control autosize" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 56px;" required="true"></textarea>
                            </div>
                        </div>

                        <!-- task salary -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Salary (SGD/hour)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="salary" required="true">
                            </div>
                        </div>
                        
                        <!-- start time -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Start Time</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="start-datetimepicker">
                                    <input type="text" class="form-control" name="start_time" required="true">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        
                        <!-- end time -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">End Time</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="end-datetimepicker">
                                    <input type="text" class="form-control" name="end_time" required="true">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </div>
                                </div>                                
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="btn-toolbar">
                                        <button type="submit" name="create" class="btn-primary btn">Create</button>
                                        <button type="button" class="btn-default btn">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <?php if(isset($_GET['create'])) {
                    $user_id = $_SESSION["user_id"];
                    $title = $_GET['title'];
                    $category = $_GET['category'];
                    $region = $_GET['region'];
                    $description = $_GET['description'];
                    $salary = $_GET['salary'];
                    $start_time = $_GET['start_time'];
                    $end_time = $_GET['end_time'];
                    $current_time = date('Y-m-d h:i', time());

                    $query = "INSERT INTO tasks(owner, title, category, region, description, salary, start_time, end_time, post_time) 
                              VALUES (". $user_id .", '". $title ."', ". $category .", ". $region .", '". $description ."', ". $salary .", '". $start_time ."', '". $end_time ."', '". $current_time ."')";

                    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
                    pg_free_result($result);
                    header("Location: tasklist.php");
                    echo "<script>window.location = '/TaskSourcing/tasklist.php';</script>";
                    exit();
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>