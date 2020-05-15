<?php
    //includes necessary files for program to run
    require_once('checklog.php'); //checks if session variables have been set --> user has logged in
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions


    $post_id = clean_string($db_server, $_GET['postid']);
    $message = $comments = $output = "";

    if (!$db_server){
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }else{
        // Print out Comment and replies
        $query = "SELECT * FROM comments JOIN Students ON comments.userID = Students.ID WHERE comments.post_ID = $post_id";
        $result = mysqli_query($db_server, $query);
        if (!$result) die("Database access failed1: " . mysqli_error($db_server));
        if($row = mysqli_fetch_array($result)){
            $comments = print_comment_and_replies($row, $comments, $db_server, 0, "reply.php?postid=" . $post_id);
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
                    $query = "INSERT INTO comments (reply_ID, userID, comment) VALUES ('$post_id', " . $_SESSION['userID'] . ", '$comment')";
                    mysqli_query($db_server, $query) or
                    die("Insert failed: " . mysqli_error($db_server));
                    $message = "Thanks for your reply!";
                    header('location: forum.php');
                }else{
                    $message = "Invalid form submission";
                }
                echo "<meta http-equiv='refresh'>"; //refreshes the pages to load reply
            }else{
                $message = "reCAPTCHA failed: ".$data->{'error-codes'}[0];
            }
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
                <div>
                    <form action="" method="post">
                        <h3>Posts</h3>
                        <p><strong><a href='forum.php'><i class="fa fa-arrow-left"></i>    Back to forum</a></strong></p>
                        <p><?php echo $comments; ?></p>
                        <h4><?php echo $message; ?></h4>
                        <p class="forms">Reply: </p> 
                        <textarea rows="5" cols="50" name="comment" placeholder="Type your reply here"></textarea>
                        <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                        <input type="submit" id="submit" name="submit" value="Submit" /><br />
                    </form>
                </div>
            </div>
            <?php require_once('footer.php')?>
        </div>

    </div>
</body>

</html>