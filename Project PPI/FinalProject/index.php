<?php
    require_once("function.php");
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    if ($username && $password){
        session_start();
        require_once("db_connect.php");
        mysqli_select_db($db_server, $db_database) or
        die("Couldn't find db");
        $username = clean_string($db_server, $username);
        $password = clean_string($db_server, $password);

        $query = "SELECT * FROM Students WHERE username='$username'";
        $result = mysqli_query($db_server, $query);
        if($row = mysqli_fetch_array($result)){
            $db_password = $row['Password'];
            $db_id = $row['ID'];
            $db_fullname = $row['FullName'];
            $db_email = $row['Email'];
            $db_level = $row['Level'];
            $db_university = $row['University'];
            $db_course = $row['Course'];
            if (password_verify($password, $db_password)) {
                $_SESSION['username']=$username;
                $_SESSION['fullname']=$db_fullname;
                $_SESSION['email']=$db_email;
                $_SESSION['university']=$db_university;
                $_SESSION['level']=$db_level;
                $_SESSION['course']=$db_course;
                $_SESSION['userID']=$db_id;
                $_SESSION['logged']="logged";
                header('Location: home.php');
            }else{
               $message = "Incorrect password! ";
                "Please <a href='index.php'>try again</a>";                
            }
        }else{
            $message = "That user does not exist! " .
            "Please <a href='index.php'>try again</a>";
        }    
    mysqli_free_result($result);
    mysqli_close($db_server); 
    } else if ($username || $password){ // If only one input is filled
        $message = "Username and password must both be filled! " .
        "Please <a href='index.php'>try again</a>";
    }
?>

<html>

<head>
    <meta charset="utf-8">
    <title>Leeds Indonesian Student Association</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <link rel='icon' href='favicon.ico' type='image/x-icon' />
</head>

<body>
    <div id="wrapper">
        <div id="main">
            <?php require_once("header_guest.php")?>
            <div class="hero">
                <img src="images/hero-home.jpg" alt="Indonesian Student Association" width="100%">
            </div>

            <div class="main-info">
                <div class="login-register">
                    <form action='index.php' method='POST'>
                        <h1><strong>Welcome to PPI Greater Leeds!</strong></h1>
                        <p>This website contains personal information about our members. Please log in to continue.</p>
                        <h4><?php echo $message; ?></h4>
                        <p class="forms">
                            Username: <input type='text' name='username'><br />
                            Password: <input type='password' name='password'><br />
                            <input type='submit' name='submit' value='Login'>
                            <input name='reset' type='reset' value='Reset'><br />
                        </p>

                    </form>
                </div>
                <div>
                    <br />
                    <h3>Not yet a member?</h3>
                    <p><a href="register.php">Sign up here</a> to access the full website.</p>
                </div>
            </div>
            <div id="footer">
                <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
            </div>
        </div>

    </div>



</body>

</html>