<?php
    require_once('checklog.php');
    require_once "function.php";
    require_once "db_connect.php";
    
    $like_id = clean_string($db_server, $_GET['likeid']);
    $dislike_id = clean_string($db_server, $_GET['dislikeid']);
    $reply_like_id = clean_string($db_server, $_GET['replylikeid']);
    $reply_dislike_id = clean_string($db_server, $_GET['replydislikeid']);

    $post_id = clean_string($db_server, $_GET['postid']);
    // $like_id = clean_string($db_server, $_GET['likeid']);
    $message = $comments = $output = "";
    if (!$db_server){
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }else{
        // Connecting to Database
        mysqli_select_db($db_server, $db_database);
        
        // Comment like button action
        if ($like_id and $like_id != ""){
            $_SESSION['liked_' . $like_id] = $like_id;
            $query = "UPDATE comments SET sentiment = sentiment + 1 WHERE post_ID=$like_id";
            mysqli_query($db_server, $query) or die ("insertion of like failed");

            if (isset($_SESSION['disliked_' . $like_id])) {
                unset($_SESSION['disliked_' . $like_id]);
                $query = "UPDATE comments SET dislike = dislike - 1 WHERE post_ID=$like_id";
                mysqli_query($db_server, $query) or die ("removal of dislike failed");
            }
            echo "<meta http-equiv='refresh'>";
        }
        // Comment dislike button action
        if ($dislike_id and $dislike_id != ""){
            $_SESSION['disliked_' . $dislike_id] = $dislike_id;
            $query = "UPDATE comments SET dislike = dislike + 1 WHERE post_ID=$dislike_id";
            mysqli_query($db_server, $query) or die ("insertion of dislike failed");
            
            if (isset($_SESSION['liked_' . $dislike_id])) {
                unset($_SESSION['liked_' . $dislike_id]);
                $query = "UPDATE comments SET sentiment = sentiment - 1 WHERE post_ID=$dislike_id";
                mysqli_query($db_server, $query) or die ("removal of like failed");
            }
            echo "<meta http-equiv='refresh'>";
        }
        // Reply like button action
        if ($reply_like_id and $reply_like_id != ""){
            $_SESSION['reply_liked_' . $reply_like_id] = $reply_like_id;
            $query = "UPDATE replies SET sentiment = sentiment + 1 WHERE reply_ID=$reply_like_id";
            mysqli_query($db_server, $query) or die ("insertion of like failed");

            if (isset($_SESSION['reply_disliked_' . $reply_like_id])) {
                unset($_SESSION['reply_disliked_' . $reply_like_id]);
                $query = "UPDATE replies SET dislike = dislike - 1 WHERE reply_ID=$reply_like_id";
                mysqli_query($db_server, $query) or die ("removal of dislike failed");
            }
            echo "<meta http-equiv='refresh'>";
        }
        // Reply dislike button action
        if ($reply_dislike_id and $reply_dislike_id != ""){
            $_SESSION['reply_disliked_' . $reply_dislike_id] = $reply_dislike_id;
            $query = "UPDATE replies SET dislike = dislike + 1 WHERE reply_ID=$reply_dislike_id";
            mysqli_query($db_server, $query) or die ("insertion of dislike failed");
            
            if (isset($_SESSION['reply_liked_' . $reply_dislike_id])) {
                unset($_SESSION['reply_liked_' . $reply_dislike_id]);
                $query = "UPDATE replies SET sentiment = sentiment - 1 WHERE reply_ID=$reply_dislike_id";
                mysqli_query($db_server, $query) or die ("removal of like failed");
            }
            echo "<meta http-equiv='refresh'>";
        }
        
        // Test for form submission
        if(trim($_POST['submit']) == "Submit"){
            $captcha=$_POST['g-recaptcha-response'];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
            $response =
            file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
            $data = json_decode($response);
            if (isset($data->success) AND $data->success==true) {
                $comment = clean_string($db_server, $_POST['comment']);
                if($comment != ""){
                    $query = "INSERT INTO replies (userID, post_ID, comment) VALUES (" . $_SESSION['userID'] . ", '$post_id', '$comment')";
                    mysqli_query($db_server, $query) or
                    die("Insert failed: " . mysqli_error($db_server));
                    $message = "Thanks for your reply!";
                    header('location: forum.php');
                }else{
                    $message = "Invalid form submission";
                }
                echo "<meta http-equiv='refresh'>";
            }else{
                $message = "reCAPTCHA failed: ".$data->{'error-codes'}[0];
            }
        }

        $query = "SELECT * FROM comments JOIN Students ON comments.userID = Students.ID WHERE post_ID = '$post_id'";
        $result = mysqli_query($db_server, $query);
        if (!$result) die("Database access failed: " . mysqli_error($db_server));
        while($row = mysqli_fetch_array($result)){
            // Open divider per comment
            $comments .=  "<div><div class = 'comments'><p>" . "<strong>" . $row['FullName'] . "</strong>" ."<em> (" . $row['Username'] . ")" . " - " . $row['commDate'] . "</em></br>" .  $row['comment'] .    "<br/>"; 
            if(!isset($_SESSION["liked_" . $row['post_ID']])){ // Comment is not liked yet
                $comments .= "<a href='reply.php?postid=$post_id&likeid=" . $row['post_ID'] . "'><i class='fa fa-thumbs-up' style='color:grey'></i></a>&nbsp" . $row['sentiment'] . "&nbsp &nbsp";
            }else{
                $comments .= "<i class='fa fa-thumbs-up' style='color:green'></i>&nbsp" . $row['sentiment'] . "&nbsp &nbsp";
            }
            if(!isset($_SESSION["disliked_" . $row['post_ID']])){ // Comment is not disliked yet
                $comments .= "<a href='reply.php?postid=$post_id&dislikeid=" . $row['post_ID'] . "'><i class='fa fa-thumbs-down' style='color:grey'></i></a>&nbsp" . $row['dislike'];
            }else{
                $comments .= "<i class='fa fa-thumbs-down' style='color:#e6300c'></i>&nbsp" . $row['dislike'];
            }
            $comments .= "&nbsp | &nbsp" . "<a class='forum-button' href='reply.php?postid=" . $row['post_ID'] . "'><i class='fa fa-reply'></i>   Reply</a>   ";
            // CHECK THAT THE COMMENT USERID MATCHES SESSION USER ID
            if ($row['userID'] == $_SESSION['userID']){
                $comments .="&nbsp | &nbsp" . "<a class='forum-button' href='delete_post.php?pID=" . $row['post_ID'] . "&previousURL=forum.php'><i class='fa fa-trash'></i>   Delete</a>   ";
            }
            // Close divider per comment
            $comments .= "</p><hr/></div>";
            
            // Replies
            $reply_query = "SELECT * FROM replies INNER JOIN Students ON replies.userID = Students.ID WHERE replies.post_ID = " . $row['post_ID'] . " ORDER BY replies.reply_ID";
            $reply_result = mysqli_query($db_server, $reply_query);
            if (!$reply_result) die("Database access failed: " . mysqli_error($db_server) );
            while($row = mysqli_fetch_array($reply_result)){
                // Open divider per comment
                $comments .=  "<div class = 'replies'><p>" . "<strong>" . $row['FullName'] . "</strong>" ."<em> (" . $row['Username'] . ")" . " - " . $row['commDate'] . "</em></br>" .  $row['comment'] .    "<br/>"; 
                if(!isset($_SESSION["reply_liked_" . $row['reply_ID']])){ // Comment is not liked yet
                    $comments .= "<a href='reply.php?postid=$post_id&replylikeid=" . $row['reply_ID'] . "'><i class='fa fa-thumbs-up' style='color:grey'></i></a>&nbsp" . $row['sentiment'] . "&nbsp &nbsp";
                }else{
                    $comments .= "<i class='fa fa-thumbs-up' style='color:green'></i>&nbsp" . $row['sentiment'] . "&nbsp &nbsp";
                }
                if(!isset($_SESSION["reply_disliked_" . $row['reply_ID']])){ // Comment is not disliked yet
                    $comments .= "<a href='reply.php?postid=$post_id&replydislikeid=" . $row['reply_ID'] . "'><i class='fa fa-thumbs-down' style='color:grey'></i></a>&nbsp" . $row['dislike'];
                }else{
                    $comments .= "<i class='fa fa-thumbs-down' style='color:#e6300c'></i>&nbsp" . $row['dislike'];
                }
                // $comments .= "&nbsp | &nbsp" . "<a class='forum-button' href='reply.php?replyid=" . $row['reply_ID'] . "'><i class='fa fa-reply'></i>   Reply</a>   ";
                // CHECK THAT THE COMMENT USERID MATCHES SESSION USER ID
                if ($row['userID'] == $_SESSION['userID']){
                    $comments .="&nbsp | &nbsp" . "<a class='forum-button' href='delete_reply.php?rID=" . $row['reply_ID'] . "&previousURL=forum.php'><i class='fa fa-trash'></i>   Delete</a>   ";
                }
                // Close divider per comment
                $comments .= "</p><hr/></div>";
            }
            $comments .= "</div>";
        }

        //CHECK THAT THE COMMENT USERID MATCHES SESSION USER ID
        if ($row['userID'] == $_SESSION['userID']){
            $comments .=" <a href='delete_post.php?pID=" . $row['post_ID'] . "&previousURL=forum.php'>Delete</a>";
        }
        mysqli_free_result($result);
        mysqli_close($db_server); 
    }
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Leeds Indonesian Student Association</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div id="wrapper">
        <div id="main">
            <?php require_once('header_logged.php')?>

            <div class="main-info">
                <h1>Welcome to the discussion board!</h1>
                <h4></h4>
                <div class="login-register">
                    <form action="" method="post">
                        <h3>Comment</h3>
                        <p><?php echo $comments; ?></p>
                        <h4><?php echo $message; ?></h4>
                        <p class="forms">Reply: </p> 
                        <textarea rows="5" cols="50" name="comment" placeholder="Type your reply here"></textarea>
                        <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                        <input type="submit" id="submit" name="submit" value="Submit" /><br />
                    </form>
                </div>
            </div>
            <div id="footer">
                <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
            </div>
        </div>

    </div>
</body>

</html>