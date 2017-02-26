<html>
<head> <title>task list demo</title> </head>

<body>
<table>
<tr> <td colspan="2" style="background-color:#FFA500;">
<h1> Demo Todo List </h1>
</td> </tr>
<?php include 'db-connection.php';?>

<tr>
<td style="background-color:#eeeeee;">
<a href="demo.php">Search</a>
<a href="add.php">Add Task</a>
<form>
    <input type="submit" name="formSubmit" value="Reset Data" >
</form>
<?php if(isset($_GET['formSubmit'])) 
{
    /************** DROP EXISTING TABLES **************/
    $sqlLog = "<br>";
    $tableNames = array("tasks", "users");
    foreach ($tableNames as &$tableName) {
        $dropTableQuery = "DROP TABLE IF EXISTS " . $tableName;
        pg_query($dropTableQuery) or die ('Drop table failed: ' . pg_last_error());
        $sqlLog .= $dropTableQuery . "<br>";
    }

    /************** CREATE user TABLES **************/
    $createUsersQuery = "CREATE TABLE users (user_id serial PRIMARY KEY,
                                             user_name varchar(100) NOT NULL)";
    pg_query($createUsersQuery) or die ('Create table users failed: ' . pg_last_error());
    $sqlLog .= $createUsersQuery . "<br>";

    /************** CREATE tasks TABLES **************/
    $createTasksQuery = "CREATE TABLE tasks (task_id SERIAL PRIMARY KEY,
                                             task_name varchar(100) NOT NULL,
                                             task_priority int NOT NULL,
                                             owner_id int REFERENCES users(user_id))";
    pg_query($createTasksQuery) or die ('Create table tasks failed: ' . pg_last_error());
    $sqlLog .= $createTasksQuery . "<br>";


    /************** INSERT SAMPLE users **************/
    $users = array('Akiyama_Mio', 'Kotobuki_Tsumugi', 'Hirasawa_Yui', 'Tainaka_Ritsu', 'Nakano_Azusa');
    foreach ($users as &$user) {
        $addUserQuery = "INSERT INTO users (user_name) VALUES ('" . $user . "')";
        pg_query($addUserQuery) or die('Add user failed: ' . pg_last_error());
        $sqlLog .= $addUserQuery . "<br>";
    }

    /************** INSERT SAMPLE tasks **************/
    $tasks = array(array('Go to work', '3', '3'),
                   array('Study', '2', '1'),
                   array('Attend meeting', '3', '1'),
                   array('Visit parents', '3', '5'),
                   array('Go surfing', '1', '2'),
                   array('Buy tickets', '3', '1'),
                   array('Go night running', '2', '4'));
    foreach ($tasks as &$task) {
        $addTaskQuery = "INSERT INTO tasks (task_name, task_priority, owner_id) VALUES ('" . $task[0] . "', '" . $task[1] . "', '" . $task[2] . "')";
        pg_query($addTaskQuery) or die('Add task failed: ' . pg_last_error());
        $sqlLog .= $addTaskQuery . "<br>";
    }

    echo "<b>SQL: </b>".$sqlLog."<br><br>";
    echo "Data Reset";
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