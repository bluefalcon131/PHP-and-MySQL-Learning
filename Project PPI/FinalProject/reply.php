<?php
    require_once('checklog.php');
    require_once "function.php";
    require_once "db_connect.php";
    
    $post_id = clean_string($db_server, $_GET['postid']);
    $like_id = clean_string($db_server, $_GET['likeid']);
    $message = $comments = $output = "";
    if (!$db_server){
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }else{
    //Test whether form has been submitted
        mysqli_select_db($db_server, $db_database);
        
        if ($like_id and $like_id != ""){
            $_SESSION['liked_' . $like_id] = $like_id;
            $query = "UPDATE replies SET sentiment = sentiment + 1 WHERE post_ID=$like_id";
            mysqli_query($db_server, $query) or die ("like failed");
        }
        
        if(trim($_POST['submit']) == "Submit"){
            // Your code here to handle a successful verification
            $comment = clean_string($db_server, $_POST['comment']);
            if($comment != ""){
                $query = "INSERT INTO replies (post_ID, userID, comment, sentiment) VALUES ('$post_id', " . $_SESSION['userID'] . ", '$comment', 0)";
                mysqli_query($db_server, $query) or
                die("Insert failed: " . mysqli_error($db_server));
                $message = "Thanks for your reply!";
            }else{
                $message = "Invalid form submission";
            }
        }
    }

    mysqli_select_db($db_server, $db_database); // Comment out after success, or understand why needed
    $query = "SELECT * FROM comments JOIN Students ON comments.userID = Students.ID WHERE post_ID = $post_id";
    $result = mysqli_query($db_server, $query);
    if (!$result) die("Database access failed: " . mysqli_error($db_server) );
    while($row = mysqli_fetch_array($result)){
        // Open divider per comment
            $comments .=  "<div class = 'replies'><p>" . "<strong>" . $row['FullName'] . "</strong>" ."<em> (" . $row['Username'] . ")" . " - " . $row['commDate'] . "</em></br>" .  $row['comment'] .    "<br/>    <i class='fa fa-thumbs-up'></i> " . $row['sentiment'] . "   |"; 
            if(!isset($_SESSION["liked_" . $row['post_ID']])){
                $comments .= "<a href='forum.php?likeid=" . $row['post_ID'] . "'>Like</a>&nbsp";
            }else{
                $comments .= " liked";
            }
            $comments .= "         <a href='reply.php?postid=" . $row['post_ID'] . "'>Reply</a>   ";
            // CHECK THAT THE COMMENT USERID MATCHES SESSION USER ID
            if ($row['userID'] == $_SESSION['userID']){
                $comments .=" <a href='delete_post.php?pID=" . $row['post_ID'] . "&previousURL=forum.php'>Delete</a>   ";
            }
            // Close divider per comment
            $comments .= "</p><hr/></div>";
        }
    //CHECK THAT THE COMMENT USERID MATCHES SESSION USER ID
    if ($row['userID'] == $_SESSION['userID']){
        $comments .=" <a href='delete_post.php?pID=" . $row['post_ID'] . "&previousURL=forum.php'>Delete</a>";
    }

    mysqli_free_result($result);
    mysqli_close($db_server); 
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Leeds Indonesian Student Association</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
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
                    <form action="forum.php" method="post">
                        <h3>Comment</h3>
                        <p><?php echo $comments; ?></p>
                        <p>Type your reply below. </p>
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