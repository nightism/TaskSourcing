<html>
<head> <title>task list demo</title> </head>

<body>
<table>
<tr> <td colspan="2" style="background-color:#FFA500;">
<h1> Demo Todo List </h1>
</td> </tr>


<tr>
<td style="background-color:#eeeeee;">
<a href="reset.php">Reset</a>
<a href="add.php">Add Task</a>
<form>
        Task Name: <input type="text" name="Title" id="Title">
        <input type="submit" name="formSubmit" value="Search" >
</form>
<?php if(isset($_GET['formSubmit'])) 
{
    $query = "SELECT task_id, task_name, task_time, task_priority FROM tasks WHERE task_name ILIKE '%".$_GET[Title]."%'";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        <?php include 'db-connection.php';?>


    echo "<b>SQL: </b>".$query."<br><br>";
    
    echo "<table border=\"1\" >
    <col width=\"75%\">
    <col width=\"25%\">
    <tr>
    <th>Task ID</th>
    <th>Task Name</th>
    <th>Task Time</th>
    <th>Task Priority</th>
    </tr>";

    while ($row = pg_fetch_row($result)){
      echo "<tr>";
      echo "<td>" . $row[0] . "</td>";
      echo "<td>" . $row[1] . "</td>";
      echo "<td>" . $row[2] . "</td>";
      echo "<td>" . $row[3] . "</td>";
      echo "</tr>";
    }
    echo "</table>";


    // while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    // echo "\t<tr>\n";
    // foreach ($line as $col_value) {
    //     echo "\t\t<td>$col_value</td>\n";
    // }
    // echo "\t</tr>\n";
    
    pg_free_result($result);
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