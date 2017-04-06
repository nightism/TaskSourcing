<?php
// Start the session
session_start();
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $is_admin = $_SESSION["is_admin"];
    if ($is_admin == "t") {

    } else {
        echo "<script>window.location = '/TaskSourcing/tasklist.php';</script>";
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tasource - Find Task</title>
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/style.css">
    <link rel="stylesheet" type="text/css" href="../TaskSourcing/css/new-task-styling.css">
    <link rel="stylesheet" href="../TaskSourcing/css/bootstrap-datetimepicker.min.css">

    <script src="../TaskSourcing/js/jquery-3.2.0.min.js"></script>
    <script src="../TaskSourcing/bootstrap/js/bootstrap.min.js"></script>
    <script src="../TaskSourcing/js/jquery.ns-autogrow.min.js"></script>
    <script src="../TaskSourcing/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../TaskSourcing/js/find-task.js"></script>
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
                <li>Data summary</li>
            </ol>
            <h1>Data Summary</h1>
        </div>

        
        <div class="table-vertical first-table">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="padding-right:100px">Category</th>
                        <th>Number of tasks</th>
                        <th>Average salary (SGD/hr)</th>
                        <th>User who posts most</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $numOfTask = array();
                        $averageSalery = array();
                        $users = array();
                        $query1 = "SELECT c.name, SUM(CASE WHEN t.id IS NULL THEN 0 ElSE 1 END), AVG(CASE WHEN t.salary IS NULL THEN -1 ELSE t.salary END) FROM categories c LEFT OUTER JOIN tasks t ON t.category = c.id GROUP BY c.id, c.name ORDER BY c.name";
                        $result1 = pg_query($query1) or die('Query failed: ' . pg_last_error());

                        while ($row1 = pg_fetch_row($result1)) {
                            $numOfTask[$row1[0]] = $row1[1];
                            $averageSalery[$row1[0]] = $row1[2];
                        }

                        pg_free_result($result1);

                        $query2 = "SELECT c.name, temp4.uname FROM categories c LEFT OUTER JOIN (SELECT temp2.cid AS cid, MAX(temp3.uname) AS uname FROM (SELECT temp1.cid AS cid, MAX(temp1.task_count) AS task_count FROM (SELECT c.id AS cid, u.name AS uname, COUNT(t.id) AS task_count FROM categories c INNER JOIN tasks t ON t.category = c.id INNER JOIN users u ON u.id = t.owner GROUP BY c.id, u.name) AS temp1 GROUP BY temp1.cid) AS temp2 INNER JOIN (SELECT c.id AS cid, u.name AS uname, COUNT(t.id) AS task_count FROM categories c INNER JOIN tasks t ON t.category = c.id INNER JOIN users u ON u.id = t.owner GROUP BY c.id, u.name) AS temp3 ON temp2.cid = temp3.cid AND temp2.task_count = temp3.task_count GROUP BY temp2.cid, temp2.task_count) AS temp4 ON c.id = temp4.cid";
                        $result2 = pg_query($query2) or die('Query failed: ' . pg_last_error());

                        while ($row2 = pg_fetch_row($result2)){
                            $users[$row2[0]] = $row2[1];
                        }

                        foreach ($numOfTask as $key => $value) {
                            $average = $averageSalery[$key] < 0 ? '' : round(floatval($averageSalery[$key]), 2);
                            echo "<tr>";
                            echo "<td align='left'><i>" . $key . "</i></td>";
                            echo "<td>" . $numOfTask[$key] . "</td>";
                            echo "<td>" . $average . "</td>";
                            echo "<td>" . $users[$key] . "</td>";
                            echo "</tr>";
                        }
                        
                        pg_free_result($result2);
                    ?>
                    <!-- <tr>
                        <td align="left" data-title="Country"><i>World</i></td>
                        <td data-title="Population (1000s)">6085576</td>
                        <td data-title="Adults (1000s)">3697511</td>
                        <td data-title="Share of world population (%)">100.00</td>
                    </tr> -->
                </tbody>
            </table>
        </div>
    </div>

    <?php
    pg_close($dbconn);
    ?>
</body>
</html>