<?php
$dbconn = pg_connect("
    host=127.0.0.1
    port=5432
    dbname=tasksourcing
    user=postgres
    password=smySMY2017
    ")
    or die('Could not connect: ' . pg_last_error());
?>