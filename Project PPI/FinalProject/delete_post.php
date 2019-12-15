<?php
    require_once('checklog.php');
    require_once('function.php');
    $postID = trim($_GET['pID']);
    $previousURL = trim($_GET['previousURL']);
    if($postID != '' && is_numeric($postID)){
        require_once("db_connect.php");
    }
    if (!$db_server){
        die("Unable to connect to DB: " . mysqli_connect_error($db_server));
    }else{
        $postID = clean_string($db_server, $postID);
        mysqli_select_db($db_server,$db_database) or die("Couldn't find db");
        // DELETE post FROM comments
        $query = "DELETE FROM comments WHERE post_ID=$postID";
        mysqli_query($db_server, $query) or
        die("Comment delete failed" . mysqli_error($db_server));
        header('Location: ' . $previousURL);
    }
        
?>