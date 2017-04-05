<?php
$dbconn = pg_connect("
    host=127.0.0.1
    port=5432
    dbname=task_sourcing
    user=postgres
    password=Wzf19970822!!!
    ")
    or die('Could not connect: ' . pg_last_error());
?>