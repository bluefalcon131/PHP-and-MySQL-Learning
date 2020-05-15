<?php
    //create function to delete post and replies
    function delete_post($postID, $db_server) {
        //Deletes comments based on the Post ID
        $query = "DELETE FROM comments WHERE post_ID = $postID";
        mysqli_query($db_server, $query) or die("Comment delete failed" . mysqli_error($db_server));
        //Deletes all ratings from deleted post
        $query = "DELETE FROM user_ratings WHERE post_ID=$postID";
        mysqli_query($db_server, $query) or die("Comment delete failed" . mysqli_error($db_server));
        //Deletes all replies from deleted post
        $query = "SELECT * FROM comments WHERE reply_ID = $postID";
        $result = mysqli_query($db_server, $query);
        if (!$result) die("Database access failed2: " . mysqli_error($db_server) . $query);
        while($row = mysqli_fetch_array($result)) {
            delete_post($row['post_ID'], $db_server);
        }
    }
    //includes necessary files for program to run
    require_once('checklog.php'); //checks if session variables have been set --> user has logged in
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions
    //clean input
    $postID = trim($_GET['pID']);
    $previousURL = trim($_GET['previousURL']); //grabs data on which page user deletes the post
    if (!$db_server){
        die("Unable to connect to DB: " . mysqli_connect_error($db_server));
    }else{
        delete_post($postID, $db_server); //calls the delete post function
        header('Location: ' . $previousURL); //redirects user to the previous URL from where the post is deleted
    }
?>