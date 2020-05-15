<?php
    
    //includes necessary files for program to run
    require_once('checklog.php'); //checks if session variables have been set --> user has logged in
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions

    // Grab the form data, cleans input data
    $username = $_SESSION['username'];
    $oldpassword = trim($_POST['oldpassword']); 
    $newpassword = trim($_POST['newpassword']);
    $repeatnewpassword = trim($_POST['repeatnewpassword']);

    //Star to use PHP session
    session_start();
    //check if form has been submitted
    if(isset($_POST['submit'])){
        //check if all form fields have been filled
        if ($oldpassword&&$newpassword&&$repeatnewpassword){ 
            //check if both passwords match
            if ($newpassword==$repeatnewpassword){
                //check if username is between 6-25 characters long
                if (strlen($newpassword)>25||strlen($newpassword)<6) { 
                    $message = "<h4>Password must be 6-25 characters long</h4>";
                }else{
                    if($db_server){ 
                        //Clean the input after DB Connection and Form Validation
                        $oldpassword = clean_string($db_server, $oldpassword);
                        $newpassword = clean_string($db_server, $newpassword); 
                        $repeatnewpassword = clean_string($db_server, $repeatnewpassword); 
                        mysqli_select_db($db_server, $db_database);

                        if($newpassword <> ''){
                            //encrypt password with built in hash function
                            $newpassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                            //updates database with new password
                            $query="UPDATE Students SET Password ='$newpassword' WHERE ID=". $_SESSION['userID']." ";
                            mysqli_query($db_server, $query) or die("Insert failed. ". mysqli_error($db_server)); 
                            $message = "<h3><strong>Password updated successfully!</strong></h3>";
                        } else {
                            $message="<h4>Please enter a new password</h4>";
                        }

                    }else{
                        $message="<h4>Error: could not connect to the database.</h4>";
                    }
                    //closes connection with database
                    require_once("db_close.php");
                }
            } else {
                $message="<h4>New passwords don't match</h4>";
            }  
        }else{
            $message="<h4>Please fill in all fields</h4>";
        }
    }
?>

<!---------------------- HTML code ------------------------->

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
            <?php require_once("header_logged.php")?>

            <div class="hero">
                <img src="images/hero-home.jpg" alt="Indonesian Student Association" width="100%">
            </div>

            <div class="main-info">
                <h1>Change Password</h1>
                <p><strong><a href='account.php'><i class="fa fa-arrow-left"></i>    Back to account</a></strong></p>

                <form method="post" action="change_password.php">
                    <?php echo $message; ?>
                    <p class="forms">
                        Old Password:<input type='password' name='oldpassword' value='<?php echo $password; ?>'>
                        New Password: <input type='password' name='newpassword'>

                        Repeat New Password: <input type='password' name='repeatnewpassword'>

                        <input type='submit' name='submit' value='Submit'>
                        <input name='reset' type='reset' value='Reset'>
                    </p>
                </form>
            </div>
             <?php require_once('footer.php')?>
        </div>

    </div>
</body>

</html>