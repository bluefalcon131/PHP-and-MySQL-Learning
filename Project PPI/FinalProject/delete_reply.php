<?php
    require_once('checklog.php');
    require_once('function.php');
    $reply_ID = trim($_GET['rID']);
    $previousURL = trim($_GET['previousURL']);
    if($reply_ID != '' && is_numeric($reply_ID)){
        require_once("db_connect.php");
    }
    if (!$db_server){
        die("Unable to connect to DB: " . mysqli_connect_error($db_server));
    }else{
        $reply_ID = clean_string($db_server, $reply_ID);
        mysqli_select_db($db_server,$db_database) or die("Couldn't find db");
        // DELETE post FROM replies
        $query = "DELETE FROM replies WHERE reply_ID=$reply_ID";
        mysqli_query($db_server, $query) or
        die("Reply delete failed" . mysqli_error($db_server));
        header('Location: ' . $previousURL);
    }
?>