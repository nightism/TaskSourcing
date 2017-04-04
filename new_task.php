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
                    <li><a href="">Find task</a></li>
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
                                <input type="text" class="form-control" name="task_name">
                            </div>
                        </div>

                        <!-- task category -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Task Category</label>
                            <div class="col-sm-6">
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php
                                        $query = "SELECT c.category_name FROM categories c ORDER BY c.category_name";
                                        $result = pg_query($query) or die('Query failed: ' . pg_last_error());

                                        while ($row = pg_fetch_row($result)){
                                            echo "<option value=\"".$row[0]."\">".$row[0]."</option><br>";
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
                                <textarea name="descriptoin" class="form-control autosize" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 56px;"></textarea>
                            </div>
                        </div>
                        
                        <!-- start time -->
                        <div class="form-group row">
                            <label class="col-sm-3 control-label">Start Time</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="start-datetimepicker">
                                    <input type="text" class="form-control" name="start_time">
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
                                    <input type="text" class="form-control" name="end_time">
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
                                        <button class="btn-primary btn">Create</button>
                                        <button class="btn-default btn">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>