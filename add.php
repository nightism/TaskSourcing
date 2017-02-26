<html>
<head> <title>task list demo</title> </head>

<body>
<table>
<tr> <td colspan="2" style="background-color:#FFA500;">
<h1> Demo Todo List </h1>
</td> </tr>

<?php
$dbconn = pg_connect("
    host=127.0.0.1
    port=8080
    dbname=tasklist
    user=postgres
    password=Yzh1996+
    ")
    or die('Could not connect: ' . pg_last_error());
?>

<tr>
<td style="background-color:#eeeeee;">
<a href="demo.php">Search</a>
<a href="reset.php">Reset</a>
<form>
    <input type="text" name="taskName" placeholder="Task Name" style="width: 100px">
    <input type="number" name="taskPriority" placeholder="Priority" min="0" max="3" style="width: 100px">
    <input type="submit" name="formSubmit" value="Reset Data" >
</form>
<?php if(isset($_GET['formSubmit'])) 
{
    $query = "INSERT INTO tasks (task_name, task_priority) VALUES ('" . $_GET['taskName'] . "', '" . $_GET['taskPriority'] . "')";
    pg_query($query) or die('Add task failed: ' . pg_last_error());
    $sqlLog = $query . "<br>";

    echo "<b>SQL: </b>".$sqlLog."<br><br>";
    echo "Task Added";
    pg_free_result($tableNames);
}
?>
</td> </tr>

<?php
pg_close($dbconn);
?> 

<tr>
<td colspan="2" style="background-color:#FFA500; text-align:center;"> Copyright &#169; CS2102
</td> </tr>
</table>

</body>
</html>