<?php    
    //includes necessary files for program to run
    require_once('checklog.php'); //checks if session variables have been set --> user has logged in
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions

    //clean user input
    $like_id = clean_string($db_server, $_GET['likeid']);
    $dislike_id = clean_string($db_server, $_GET['dislikeid']);
    $clear_id = clean_string($db_server, $_GET['clearid']);
    $previous_url = clean_string($db_server, $_GET['previousurl']);

    // Comment like button action
    if ($like_id and $like_id != ""){
        $_SESSION['liked_' . $like_id] = $like_id;
        unset($_SESSION['disliked_' . $like_id]);

        $query = "SELECT * FROM user_ratings WHERE userID = " . $_SESSION['userID'] . " AND post_ID=$like_id";
        $result = mysqli_query($db_server, $query) or die ("search for rating based on userID failed" . mysqli_error($db_server) );
        if ($row = mysqli_fetch_array($result)) {
            $query = "UPDATE user_ratings SET rating=1 WHERE post_ID=$like_id AND userID= " . $_SESSION['userID'];
            mysqli_query($db_server, $query) or die ("updating of like failed" . mysqli_error($db_server) );
        } else {
            $query = "INSERT INTO user_ratings (post_ID , rating, userID) VALUES ('$like_id', '1', " . $_SESSION['userID'] . ")";
            mysqli_query($db_server, $query) or die ("insertion of like failed" . mysqli_error($db_server) );
        }
    }

    // Comment dislike button action
    if ($dislike_id and $dislike_id != ""){
        $_SESSION['disliked_' . $dislike_id] = $dislike_id;
        unset($_SESSION['liked_' . $dislike_id]);

        $query = "SELECT * FROM user_ratings WHERE userID = " . $_SESSION['userID'] . " AND post_ID=$dislike_id";
        $result = mysqli_query($db_server, $query) or die ("search for rating based on userID failed" . mysqli_error($db_server) );
        if ($row = mysqli_fetch_array($result)) {
            $query = "UPDATE user_ratings SET rating=-1 WHERE post_ID=$dislike_id AND userID= " . $_SESSION['userID'];
            mysqli_query($db_server, $query) or die ("updating of dislike failed" . mysqli_error($db_server) );
        } else {
            $query = "INSERT INTO user_ratings (post_ID , rating, userID) VALUES ('$dislike_id', '-1', " . $_SESSION['userID'] . ")";
            mysqli_query($db_server, $query) or die ("insertion of dislike failed" . mysqli_error($db_server) );
        }
    }

    // Comment clear action
    if ($clear_id and $clear_id != ""){
        unset($_SESSION['disliked_' . $clear_id]);
        unset($_SESSION['liked_' . $clear_id]);

        $query = "SELECT * FROM user_ratings WHERE userID = " . $_SESSION['userID'] . " AND post_ID=$clear_id";
        $result = mysqli_query($db_server, $query) or die ("search for rating based on userID failed" . mysqli_error($db_server) );
        if ($row = mysqli_fetch_array($result)) {
            $query = "UPDATE user_ratings SET rating=0 WHERE post_ID=$clear_id AND userID= " . $_SESSION['userID'];
            mysqli_query($db_server, $query) or die ("updating of clear failed" . mysqli_error($db_server) );
        } else {
            $query = "INSERT INTO user_ratings (post_ID , rating, userID) VALUES ('$clear_id', '0', " . $_SESSION['userID'] . ")";
            mysqli_query($db_server, $query) or die ("insertion of clear failed" . mysqli_error($db_server) );
        }
    }

    $mainquery = "SELECT * FROM comments";
    $mainresult = mysqli_query($db_server, $mainquery) or die ("counting of like");
    while ($row = mysqli_fetch_array($mainresult)) {
        $query = "SELECT COUNT(post_ID) AS a FROM user_ratings WHERE rating = -1 AND post_ID = " . $row['post_ID'];
        $result = mysqli_query($db_server, $query) or die ("counting of dislike");
        $countrow = mysqli_fetch_array($result);
        $query = "UPDATE comments SET dislike = " . $countrow['a'] . " WHERE post_ID = " . $row['post_ID'];
        mysqli_query($db_server, $query) or die ("Updating of comment rating count failed" . mysqli_error($db_server) . $query);
    
        $query = "SELECT COUNT(post_ID) AS a FROM user_ratings WHERE rating = 1 AND post_ID = " . $row['post_ID'];
        $result = mysqli_query($db_server, $query) or die ("counting of like");
        $countrow = mysqli_fetch_array($result);
        $query = "UPDATE comments SET sentiment = " . $countrow['a'] . " WHERE post_ID = " . $row['post_ID'];
        mysqli_query($db_server, $query) or die ("Updating of comment rating count failed" . mysqli_error($db_server) . $query);
    }
    
    header("location: $previous_url"); 
?>