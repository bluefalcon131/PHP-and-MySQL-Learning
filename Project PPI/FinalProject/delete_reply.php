<?php
//includes necessary files for program to run
    require_once('checklog.php'); //checks if session variables have been set --> user has logged in
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions
    
    //clean inputs
    $reply_ID = trim($_GET['rID']);
    $previousURL = trim($_GET['previousURL']);
    
    //check if replyID exists
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
        header('Location: ' . $previousURL); //redirects user to the previous URL from where the post is deleted
    }
?>