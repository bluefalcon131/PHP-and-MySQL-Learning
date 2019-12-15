<?php
require("function.php");
include("checklog.php");
include("db_connect.php");      


$username = $_SESSION['username'];
$oldpassword = trim($_POST['oldpassword']); 
$newpassword = trim($_POST['newpassword']);
$repeatnewpassword = trim($_POST['repeatnewpassword']);

session_start();
if(isset($_POST['submit'])){
    if ($oldpassword&&$newpassword&&$repeatnewpassword){ 

        if ($newpassword==$repeatnewpassword){

            if (strlen($newpassword)>25||strlen($newpassword)<6) { 
                $message = "Password must be 6-25 characters long";
            }else{
                require_once("db_connect.php"); 

                if($db_server){ 

                    $oldpassword = clean_string($db_server, $oldpassword);
                    $newpassword = clean_string($db_server, $newpassword); 
                    $repeatnewpassword = clean_string($db_server, $repeatnewpassword); 
                    mysqli_select_db($db_server, $db_database);

                    if($newpassword <> ''){
            
                        $newpassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                        $query="UPDATE Students SET Password ='$newpassword' WHERE ID=". $_SESSION['userID']." ";
                        mysqli_query($db_server, $query) or die("Insert failed. ". mysqli_error($db_server)); 
                        $message = "<strong>Password updated successfully! <a href='account.php'> Click here</a> to go back to your account page. </strong>";
                    } else {
                        $message="Please insert a password";
                    }

                }else{
                    $message="Error: could not connect to the database.";
                }
                require_once("db_close.php");

            }


        } else {
            $message="New passwords don't match";
        }  
    }else{
        $message="Please fill in all fields";
    }
}

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
            <?php require_once("header_guest.php")?>

            <div class="hero">
                <img src="images/hero-home.jpg" alt="Indonesian Student Association" width="100%">
            </div>

            <div class="main-info">
                <h1>Change Password</h1>

                <form method="post" action="change_password.php">
                    <h4><?php echo $message; ?></h4>
                    <p class="forms">
                        Old Password:<input type='text' name='oldpassword' value='<?php echo $password; ?>'>
                        New Password: <input type='password' name='newpassword'>

                        Repeat New Password: <input type='password' name='repeatnewpassword'>

                        <input type='submit' name='submit' value='Submit'>
                        <input name='reset' type='reset' value='Reset'>
                    </p>
                </form>
            </div>

            <div id="footer">
                <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
            </div>
        </div>

    </div>
</body>

</html>