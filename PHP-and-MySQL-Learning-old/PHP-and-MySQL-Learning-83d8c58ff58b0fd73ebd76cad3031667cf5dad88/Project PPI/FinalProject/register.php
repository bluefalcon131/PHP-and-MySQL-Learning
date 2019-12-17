<?php
    require_once("db_connect.php");
    require_once("function.php");
    // Grab the form data
    $submit = trim($_POST['submit']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $university = trim($_POST['university']);
    $level = trim($_POST['level']);
    $course = trim($_POST['course']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $repeatpassword = trim($_POST['repeatpassword']);
    // Create some variables to hold output data
    $message = '';
    $s_username = '';
    $s_fullname = '';
    $s_email = '';
    $s_course = '';
    $s_level = '';
    $s_university = '';

    // Start to use PHP session
    session_start();
    // Determine whether user is logged in - test for value in $_SESSION
    if (isset($_SESSION['logged'])){
        $s_username = $_SESSION['username'];
        $s_fullname = $_SESSION['fullname'];
        $s_email = $_SESSION['email'];
        $s_university = $_SESSION['university'];
        $s_level = $_SESSION['level'];
        $s_course = $_SESSION['course'];
        $message = "You are already logged in as $s_username.
        Please <a href='logout.php'>logout</a> before
        trying to register.";
    }else{
    // Next block of code will go here
        if ($submit=='Register'){
            $captcha=$_POST['g-recaptcha-response'];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
            $response =
            file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
            $data = json_decode($response);
            if (isset($data->success) AND $data->success==true) {
                if ($fullname&&$email&&$username&&$password&&$repeatpassword&&$university&&$course&&$level){
                    if ($password==$repeatpassword){
                        if (strlen($username)>25) {
                            $message = "Username is too long";
                        }else{
                            if (strlen($password)>25||strlen($password)<6) {
                                $message = "Password must be 6-25 characters long";
                            }else{
                                
                                if($db_server){
                                    //clean the input now that we have a db connection
                                    $fullname = clean_string($db_server, $fullname);
                                    $email = clean_string($db_server, $email);
                                    $university = clean_string($db_server, $university);
                                    $level = clean_string($db_server, $level);
                                    $course = clean_string($db_server, $course);
                                    $username = clean_string($db_server, $username);
                                    $password = clean_string($db_server, $password);
                                    $repeatpassword = clean_string($db_server, $repeatpassword);
                                    mysqli_select_db($db_server, $db_database);
                                    // check whether username exists
                                    $query="SELECT username FROM Students WHERE username='$username'";
                                    $result=mysqli_query($db_server, $query);
                                    if ($row = mysqli_fetch_array($result)){
                                        $message = "Username already exists. Please try again.";
                                    }else{
                                        $hash = password_hash($password, PASSWORD_DEFAULT);
                                        $query = "INSERT INTO Students (username, password, fullname, email, university, level, course) VALUES
                                        ('$username', '$hash','$fullname', '$email', '$university', '$level', '$course')";
                                        mysqli_query($db_server, $query) or
                                        die("Insert failed. ". mysqli_error($db_server));
                                        $message = "<strong>Thanks " . $fullname . ", your registration was successful! <a href='index.php'> Click here</a> to login. </strong>";
                                    }
                                    mysqli_free_result($result);
                                }else{
                                    $message = "Error: could not connect to the database.";
                                }
                                mysqli_close($db_server); //include file to do db close
                            }
                        }

                    }else{
                        $message = "Both password fields must match";
                    }
                }else{
                        $message = "Please fill in all fields";
                }
            } else {
                $message = "reCAPTCHA failed: ".$data->{'error-codes'}[0];
            } 
        }
    }
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
            <?php require_once("header_guest.php")?>

            <div class="hero">
                <img src="images/hero-home.jpg" alt="Indonesian Student Association" width="100%">
            </div>

            <div class="main-info">
                <h1>User Registration</h1>
                <div class="register-form">
                    <form action='register.php' method='post'>
                        <h4> <?php echo $message; ?></h4>
                        Username:<input type='text' name='username' value='<?php echo $username; ?>'><br />

                        Password: <input type='password' name='password'><br />

                        Repeat Password: <input type='password' name='repeatpassword'> <br />

                        Full Name:<input type='text' name='fullname' value='<?php echo $fullname; ?>'><br />

                        Email:<input type='text' name='email' value='<?php echo $email; ?>'><br />

                        University:<select name="university">
                            <option value="University of Leeds">University of Leeds</option>
                            <option value="Leeds Beckett University">Leeds Beckett University</option>
                            <option value="Leeds Art University">Leeds Art University</option>
                            <option value="Leeds College of Music">Leeds College of Music</option>
                            <option value="University of Huddersfield">University of Huddersfield</option>
                            <option value="University of Bradford">University of Bradford</option>
                        </select><br />

                        Level:<select name="level">
                            <option value="FoundationProgram">Foundation Program</option>
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Masters">Masters</option>
                            <option value="Phd">PHD</option>
                        </select><br />

                        Course:<input type='text' name='course' value='<?php echo $course; ?>'><br />


                        <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                        <input type='submit' name='submit' value='Register'>
                        <input name='reset' type='reset' value='Reset'><br/><br/><br/>
                    </form>
                    
                    
                </div>

                <div class="register-information">
                    <p>In accordance to the 2006 Constitution of the Republic of Indonesia No. 23 Article 4, every Indonesian citizen (Warga Negara Indonesia) who is currently living abroad is required to report their current address, status of residence permit, and other important events (such as birth, marriage, divorce, or death) to the Indonesian government. This is done to help protect Indonesian citizens who are in the UK / Ireland, register to vote for the General Election and guarantees immigration and consular services at the Indonesian Embassy in London. More information about this policy can be found <a href="https://indonesianembassy.org.uk/consular/pelayanan-kekonsuleran-bagi-wni/lapor-diri">here</a>.</p>
                    <p>PPI Greater Leeds will be using this data to monitor the number of members for the 2019/2020 academic year, as well as the arrangements of organisation-wide events. This data will not be published or shared in any way, shape or form. The confidentiality of data will be maintained and accounted for by PPI Greater Leeds.</p>
                    
                    <h3>Already a member?</h3>
                    <p><a href='index.php'>Click here</a> to login.</p>
                </div>

                <div>
                    
                </div>

            </div>
            <div id="footer">
                <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
            </div>
        </div>

    </div>
</body>

</html>