<?php
    require_once('checklog.php');
    require_once('db_connect.php');
    require_once('function.php');
    $username=$_SESSION['username'];
    $fullname=$_SESSION['fullname'];
    $db_email=$_SESSION['email'];
    $db_university=$_SESSION['university'];
    $db_course=$_SESSION['course'];
    $db_level=$_SESSION['level'];

    mysqli_select_db($db_server, $db_database);
    $query = "SELECT * FROM comments JOIN Students ON comments.userID = Students.ID 
    WHERE Students.ID = " . $_SESSION['userID'] . " ORDER BY comments.commDate";

    $result = mysqli_query($db_server, $query);
    if (!$result) die("Database access failed: " . mysqli_error($db_server) );
    while($row = mysqli_fetch_array($result)){
        if ($row['reply_ID'] != NULL) {
            $comments .= "<p>(reply)</p>";
        }
        $comments = print_comment_and_replies($row, $comments, $db_server, 0, "account.php");
    }
    mysqli_free_result($result);
    mysqli_close($db_server); 
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Leeds Indonesian Student Association</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="stylesheet.css">
	</head>
	<body>
    	<div id="wrapper">
            <div id="main">
                <?php require_once('header_logged.php')?>
                
                <div class="hero">
                    <img src="images/hero-home.jpg" alt="Indonesian Student Association" width="100%">
                </div>

                <div class="main-info"> 
                    <h1><strong>Hello,  <?php echo $fullname; ?></strong></h1>
                    <p>Welcome to your account. You can manage your info, privacy, and security.</p>
                    
                    <div class="account-details-1">
                        <h3>Name:</h3>
                        <p><?php echo $fullname; ?></p>

                        <h3>Email:</h3>
                        <p><?php echo $db_email; ?></p>

                        <h3>Username:</h3>
                        <p><?php echo $username ?></p>

                    </div>
                    
                    <div class="account-details-2">
                        
                        <h3>University:</h3>
                        <p><?php echo $db_university; ?></p>

                        <h3>Level:</h3>
                        <p><?php echo $db_level; ?></p>

                        <h3>Course:</h3>
                        <p><?php echo $db_course; ?></p>
                    </div>
                    
                    <div class="account-links">
                        <h4><a href="edit_details.php">Edit your Details</a></h4>
                        <h4><a href="change_password.php">Change Password</a></h4>
                        <br/>
                        <h4><a href="delete.php">Delete Account</a></h4>
                        <h4><a href="logout.php">Log Out</a></h4>
                        <br/><br/><br/><br/><br/>
                    </div>
                    <hr>
                    <h1>Your Comments: </h1>
                    <p><?php echo $comments; ?></p>
                </div>
                
                <div id="footer">
                    <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
                 </div>
            </div>

        </div>
    </body>
</html>


