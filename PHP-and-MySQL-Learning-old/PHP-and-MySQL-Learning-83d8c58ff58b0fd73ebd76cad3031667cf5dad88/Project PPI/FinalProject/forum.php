<?php
    require_once('checklog.php');
    require_once "function.php";
    require_once "db_connect.php";
    $comments = '';
    $message = $message = $comments = $output = "";
    if (!$db_server){
        die("Unable to connect to MySQL: " . mysqli_connect_error());
    }else{
    //Test whether form has been submitted
        mysqli_select_db($db_server, $db_database);
        if(trim($_POST['submit']) == "Submit"){
            // Your code here to handle a successful verification
            $comment = clean_string($db_server, $_POST['comment']);
            if($comment != ""){
                $query = "INSERT INTO comments (userID, comment) VALUES (" .
                $_SESSION['userID'] . ", '$comment')";
                mysqli_query($db_server, $query) or
                die("Insert failed: " . mysqli_error($db_server) );
                $message = "Thanks for your comment!";
            }else{
                $message = "Invalid form submission";
            }
        }
    }

//Create comments with or without submission
mysqli_select_db($db_server, $db_database);
$query = "SELECT * FROM comments JOIN Students ON comments.userID = Students.ID";
$result = mysqli_query($db_server, $query);
if (!$result) die("Database access failed: " . mysqli_error($db_server) );
while($row = mysqli_fetch_array($result)){
$comments .= "<p><em>" . $row['Username'] . ": (" . $row['commDate'] .
")</em><br /> " .$row['comment'] . "</p><hr />";
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
                        <p>Type your comments in the box below. </p>
                        <h4><?php echo $message; ?></h4>
                        Comment: <textarea rows="2" cols="30" name="comment"></textarea>
                        <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                        <input type="submit" id="submit" name="submit" value="Submit" /><br/>
                        <h3>Comments</h3>
                        <?php echo $comments; ?>
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