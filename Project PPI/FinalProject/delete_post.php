<?php
    function delete_post($postID, $db_server) {
        $query = "DELETE FROM comments WHERE post_ID = $postID";
        mysqli_query($db_server, $query) or die("Comment delete failed" . mysqli_error($db_server));
        $query = "DELETE FROM user_ratings WHERE post_ID=$postID";
        mysqli_query($db_server, $query) or die("Comment delete failed" . mysqli_error($db_server));
        
        $query = "SELECT * FROM comments WHERE reply_ID = $postID";
        $result = mysqli_query($db_server, $query);
        if (!$result) die("Database access failed2: " . mysqli_error($db_server) . $query);
        while($row = mysqli_fetch_array($result)) {
            delete_post($row['post_ID'], $db_server);
        }
    }
    require_once("db_connect.php");
    require_once('checklog.php');
    require_once('function.php');
    $postID = trim($_GET['pID']);
    $previousURL = trim($_GET['previousURL']);
    if (!$db_server){
        die("Unable to connect to DB: " . mysqli_connect_error($db_server));
    }else{
        delete_post($postID, $db_server);
        header('Location: ' . $previousURL);
    }
?>