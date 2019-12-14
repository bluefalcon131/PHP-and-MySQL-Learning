<?php
    require_once('checklog.php');
    include_once('function.php');
    $sess_userID = $_SESSION['userID'];
    if(trim($_POST['submit'])=='submit'){
        if(trim($_POST['delete'] )==1) {
            require_once("db_connect.php");
            if (!$db_server){
                die("Unable to connect to MySQL: " .
                mysqli_connect_error($db_server));
            }else{
                mysqli_select_db($db_server, $db_database) or
                die("<h1>Couldn't find db</h1>");
                //DELETE record from comments table
                $query="DELETE FROM comments WHERE userID=$sess_userID";
                mysqli_query($db_server, $query) or
                die("Delete 1 failed".mysqli_error($db_server));
                //DELETE record from users table
                $query = "DELETE FROM Students WHERE ID=$sess_userID";
                mysqli_query($db_server, $query) or
                die("Delete 2 failed".mysqli_error($db_server));
                //LOGOUT AND DESTROY SESSION
                $_SESSION = array();
                session_destroy();
                header('Location: index.php');
            }
                require_once("db_close.php");
        }else{
                header('location: home.php');
        }
    }
    //include_once('header_logged.php');
?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Leeds Indonesian Student Association</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
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
                    <h1><?php echo $_SESSION['fullname']; ?>, are you sure you want to delete your account?</h1>

                    <p>Deleting your account is permanent and will remove all content including comments and profile settings.</p>
                    <form action="delete.php" method="post">
                        Yes:<input type="radio" name="delete" value="1" /><br />
                        No: <input type="radio" name="delete" value="0" checked="checked" /><br />
                        <input type="submit" name="submit" value="submit" />
                    </form>
                </div>
                
                <div id="footer">
                    <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
                 </div>
            </div>

        </div>
    </body>
</html>

