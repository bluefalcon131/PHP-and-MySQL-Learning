<?php
    $db_hostname = 'localhost';
    $db_database = 'me18ccg_PPI'; //'Your database name'
    $db_username = 'me18ccg'; //'your username';
    $db_password = 'allergictocornC4z'; //'Your password';
    $db_status = 'not initialised';
    $db_server = mysqli_connect($db_hostname, $db_username, $db_password);
    $db_status = "connected";
    if (!$db_server){
        die("Unable to connect to MySQL: " . mysqli_error($db_server));
        $db_status = "not connected";
    }
    mysqli_select_db($db_server, $db_database);
?>