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
    <title>Tasource - Edit Task</title>
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/style.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/edit-task-styling.css">
    <link rel="stylesheet" href="../TaskSourcing/css/bootstrap-datetimepicker.min.css">

    <script src="../TaskSourcing/js/jquery-3.2.0.min.js"></script>
    <script src="../TaskSourcing/bootstrap/js/bootstrap.min.js"></script>
    <script src="../TaskSourcing/js/jquery.ns-autogrow.min.js"></script>
    <script src="../TaskSourcing/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../TaskSourcing/js/edit-task.js"></script>
</head>
<body>

    <!-- include php -->
    <?php include "config/db-connection.php"; ?>

    <?php
        if (isset($_GET["task_id"])) {
            $task_id = $_GET["task_id"];
            $query = "SELECT * FROM tasks t WHERE t.id = " . $task_id . ";";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error(). $task_id);
            $row = pg_fetch_row($result);
            if ($row) {
                $owner_id = $row[1];
                $title = $row[2];
                $desc = $row[3];
                $post_time = $row[4];
                $start_time = $row[5];
                $end_time = $row[6];
                $category = pg_fetch_row(pg_query("SELECT name FROM categories WHERE id = " . $row[8] . ";"))[0];
                $region = pg_fetch_row(pg_query("SELECT name FROM regions WHERE id = " . $row[9] . ";"))[0];
                $salary = $row[10];
                if ($owner_id == $user_id) {

                } else {
                    header("Location: tasklist.php");
                    exit;
                }
            } else {
                header("Location: tasklist.php");
                exit;
            }
            pg_free_result($result);
        }
    ?>

    <!-- navigation bar -->
    <nav class="navbar navbar-inverse navigation-bar navbar-fixed-top">
        <div class="container navbar-container">
            <div class="navbar-header pull-left"><a class="navbar-brand" href="">Tasource</a></div>
        </div>
    </nav>

    <!-- content -->
    <div class="content-container container">

        <!-- page heading -->
        <div class="page-heading">
            <ol class="breadcrumb">
                <li><a href="tasklist.php">Home</a></li>
                <li>Edit task</li>
            </ol>
            <h1>Edit Task</h1>
        </div>

        <!-- page content -->
        <div class="container-fluid">

            <!-- panel -->
            <div class="panel new-task-panel">
                <form action="">
                    <!-- panel heading -->
                    <div class="panel-heading">
                        <h2 class="new-task-form-title">Task Information</h2>
                    </div>

                    <!-- panel body -->
                    <div class="panel-body">
                        <!-- task id -->
                        <input name="t_id" value="<?php echo $task_id?>" type='hidden' />

                        <!-- task title -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Task Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="title" value="<?php echo $title; ?>" />
                            </div>
                        </div>

                        <!-- task category -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Task Category</label>
                            <div class="col-sm-6">
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php
                                        $query = "SELECT c.id, c.name FROM categories c ORDER BY c.name";
                                        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

                                        while ($row = pg_fetch_row($result)){
                                            if ($row[1] == $category) {
                                                echo "<option value=\"".$row[0]."\" selected>".$row[1]."</option><br>";
                                            } else {
                                                echo "<option value=\"".$row[0]."\">".$row[1]."</option><br>";
                                            }
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
                                            if ($row[1] == $region) {
                                                echo "<option value=\"".$row[0]."\" selected>".$row[1]."</option><br>";
                                            } else {
                                                echo "<option value=\"".$row[0]."\">".$row[1]."</option><br>";
                                            }
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
                                <textarea name="description" class="form-control autosize" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 56px;" >
                                    <?php echo trim($desc); ?>
                                </textarea>
                            </div>
                        </div>
                        
                        <!-- task salary -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Salary (SGD/hour)</label>
                            <div class="col-sm-6">
                                <input type="number" class="form-control" name="salary" required="true" value="<?php echo $salary; ?>">
                            </div>
                        </div>

                        <!-- start time -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Start Time</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="start-datetimepicker">
                                    <input type="text" class="form-control" name="start_time"
                                        value = "<?php echo $start_time; ?>">
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
                                    <input type="text" class="form-control" name="end_time"
                                        value = "<?php echo $end_time; ?>">
                                    <div class="input-group-addon">
                                        <i class="glyphicon glyphicon-calendar"></i>
                                    </div>
                                </div>                                
                            </div>
                        </div>

                        <!-- aciton buttons -->
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <div class="btn-toolbar">
                                        <button type="submit" name="edit" class="btn-primary btn">Edit</button>
                                        <button class="btn-default btn" onclick="window.location.href='tasklist.php'">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <?php 
                    if(isset($_GET['edit'])) {
                        $title = $_GET['title'];
                        $category = $_GET['category'];
                        $region = $_GET['region'];
                        $description = $_GET['description'];
                        $salary = $_GET['salary'];
                        $start_time = $_GET['start_time'];
                        $end_time = $_GET['end_time'];
                        $task_id = $_GET['t_id'];

                        $query = "UPDATE tasks
                                  SET title='" . $title . "', category=" . $category . ", region=" . $region . ",  description='" . $description . 
                                  "', salary=" . $salary . ", start_time='" . $start_time . "', end_time='". $end_time . "' WHERE id= ". $task_id . ";";
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