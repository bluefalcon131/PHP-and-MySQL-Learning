<?php
    function clean_string($db_server = null, $string){
        $string = trim($string);
        $string = utf8_decode($string);
        $string = str_replace("#", "&#35", $string);
        $string = str_replace("%", "&#37", $string);
        $string = htmlspecialchars($string);
        if($db_server){
             if (mysqli_real_escape_string($db_server, $string)) {
                //Remove characters potentially harmful to the database
                $string = mysqli_real_escape_string($db_server, $string);
             }
        }
        if (get_magic_quotes_gpc()) {
            $string = stripslashes($string);
        }
        return htmlentities($string);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function print_comment_and_replies($row, $comments, $db_server, $direction, $previous_url) {
        // PRINT OUT COMMENTS
        
        // Open divider per comment
        $comments .=  "<div class = 'comments'>";
        // Ensuring font follows the styling of paragraph in stylesheet
        $comments .= "<p>";
        // Printing Author, Username, Date and Content of post
        $comments .=  "<strong>" . $row['FullName'] . "</strong>
        <i> (" . $row['Username'] . ")" . " - " . $row['commDate'] . "</i>
        <br />" .  $row['comment'] . "<br/>"; 

        // USER INTERACTIONS
        
        // Likes
        if(!isset($_SESSION["liked_" . $row['post_ID']])){ // Comment is not liked yet
            $comments .= "<a href='rating_update.php?likeid=" . $row['post_ID'] . "&previousurl=$previous_url'><i class='fa fa-thumbs-up' style='color:grey' alt='thumbs up'></i></a>&nbsp" . $row['sentiment'] . "&nbsp&nbsp&nbsp";
        }else{
            $comments .= "<a href='rating_update.php?clearid=" . $row['post_ID'] . "&previousurl=$previous_url'><i class='fa fa-thumbs-up' style='color:green'></a></i>&nbsp" . $row['sentiment'] . "&nbsp&nbsp&nbsp";
        }

        // Dislikes
        if(!isset($_SESSION["disliked_" . $row['post_ID']])){ // Comment is not disliked yet
            $comments .= "<a href='rating_update.php?dislikeid=" . $row['post_ID'] . "&previousurl=$previous_url'><i class='fa fa-thumbs-down' style='color:grey' alt='thumbs down'></i></a>&nbsp" . $row['dislike'];
        }else{
            $comments .= "<a href='rating_update.php?clearid=" . $row['post_ID'] . "&previousurl=$previous_url'><i class='fa fa-thumbs-down' style='color:#e6300c'></a></i>&nbsp" . $row['dislike'];
        }
        
        // Reply
        $comments .= "&nbsp | &nbsp" . "<a class='forum-button' href='reply.php?postid=" . $row['post_ID'] . "'><i class='fa fa-reply'></i>&nbsp&nbsp&nbsp&nbspReply</a>";
        
        // Delete
        if ($row['userID'] == $_SESSION['userID']){ // Check that the comment userid matches session user id
            $comments .="&nbsp&nbsp|&nbsp&nbsp" . "<a class='forum-button' href='delete_post.php?pID=" . $row['post_ID'] . "&previousURL=$previous_url'><i class='fa fa-trash'></i>&nbsp&nbsp&nbsp&nbspDelete</a>";
        }

        $comments .= "<hr/>";
        // PRINT OUT REPLIES
        if ($direction > 0) {
            $query = "SELECT * FROM comments JOIN Students ON comments.userID = Students.ID WHERE comments.reply_ID = " . $row['post_ID'] . " ORDER BY comments.commDate";
            $result = mysqli_query($db_server, $query);
            if (!$result) die("Database access failed2: " . mysqli_error($db_server) );
            $comments .= "<div class = 'replies'>";
            while($replyrow = mysqli_fetch_array($result)){
                $comments = print_comment_and_replies($replyrow, $comments, $db_server, 1, $previous_url);
            }
            // Close divider for reply
            $comments .= "</div>";
        }
        // Close divider per comment
        $comments .= "</p></div>";
        return $comments;
    }
?>