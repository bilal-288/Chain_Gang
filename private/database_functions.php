<?php
function db_connect()
{
    $connection = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
}
?>
