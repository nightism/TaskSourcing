<?php
$dbconn = pg_connect("
    host=127.0.0.1
    port=8080
    dbname=task_sourcing
    user=postgres
    password=Yzh1996+
    ")
    or die('Could not connect: ' . pg_last_error());
?>