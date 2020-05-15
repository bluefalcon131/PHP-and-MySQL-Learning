<?php
    //includes necessary files for program to run
    require_once('checklog.php'); //checks if session variables have been set --> user has logged in
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions
    
    //creats variables to store input
    $message = $comments = $output = "";

    if (!$db_server){
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }else{
        //Test whether form has been submitted
        if(trim($_POST['submit']) == "Submit"){
            $captcha=$_POST['g-recaptcha-response'];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
            $response =
            file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
            $data = json_decode($response);
            //test whether recaptcha is completed
            if (isset($data->success) AND $data->success==true) {
                //cleans comment input
                $comment = clean_string($db_server, $_POST['comment']);
                //checks if comments has value
                if($comment != ""){
                    //comment is inserted into database
                    $query = "INSERT INTO comments (userID, comment) VALUES (" .
                    $_SESSION['userID'] . ", '$comment')";
                    mysqli_query($db_server, $query) or
                    die("Insert failed: " . mysqli_error($db_server));
                    $message = "<h3>Thanks for your comment!</h3>";
                }else{
                    $message = "Invalid form submission";
                }
            }else{
                $message = "<h4>reCAPTCHA failed: </h4>".$data->{'error-codes'}[0];
            }
        }

        //selects all posts from database that is not a reply
        $query = "SELECT * FROM comments INNER JOIN Students ON comments.userID = Students.ID WHERE comments.reply_ID IS NULL ORDER BY comments.commDate";
        $result = mysqli_query($db_server, $query);
        if (!$result) die("Database access failed: " . mysqli_error($db_server) );
        while($row = mysqli_fetch_array($result)){
            //stores all posts into comments variable
            $comments = print_comment_and_replies($row, $comments, $db_server, 1, "forum.php");
        }
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
                <div>
                    <form action="forum.php" method="post">
                        <p>Start a discussion by posting something below! </p>
                        <h4><?php echo $message; ?></h4>
                        <p class="forms">
                            Post: </p> <textarea rows="5" cols="50" name="comment" placeholder="Type your post here"></textarea>
                            <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                            <input type="submit" id="submit" name="submit" value="Submit" /><br />
                        <h3>Posts</h3>
                        <p><?php echo $comments; ?></p>
                    </form>
                </div>
            </div>
             <?php require_once('footer.php')?>
            
        </div>

    </div>
</body>

</html>