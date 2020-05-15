<?php

    //includes necessary files for program to run
    require_once('db_connect.php'); //establishes database connection
    require_once('functions.php'); //includes all necessary functions

    // Grab the form data, cleans input data
    $submit = trim($_POST['submit']);
    $fullname = test_input($_POST['fullname']);
    $email = test_input($_POST['email']);
    $university = trim($_POST['university']);
    $level = trim($_POST['level']);
    $course = test_input($_POST['course']);
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
        $message = "<h4>You are already logged in as $s_username</h4>.
        Please <a href='logout.php'>logout</a> before
        trying to register.";
        
    }else{
    // Check if form is submitted
        if ($submit=='Register'){
            $captcha=$_POST['g-recaptcha-response'];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
            $response =
            file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
            $data = json_decode($response);
            //If ReCAPTCHA is successfully completed, validate each form field
            if (isset($data->success) AND $data->success==true) {
                //Checks if all fields are filled
                if ($fullname&&$email&&$username&&$password&&$repeatpassword&&$university&&$course&&$level){
                    //Checks if both passwords match
                    if ($password==$repeatpassword){
                        //Check if username is shorter than 25 characters 
                        if (strlen($username)>25) {
                            $message = "<h4>Username is too long</h4>";
                        }else{
                            //Check if the password is between 6-25 characters long
                            if (strlen($password)>25||strlen($password)<6) {
                                $message = "<h4>Password must be 6-25 characters long</h4>";
                            }else{
                                //Check if full name only contains letters and white space
                                if (!preg_match("/^[a-zA-Z ]*$/",$fullname)){
                                    $message = "<h4>Invalid full name. Only letters and white space allowed.</h4>";
                                }else{
                                    //check if email in entered in valid format using PHP's filter_var() function.
                                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                                        $message = "<h4>Invalid  email format.</h4>";
                                    }else{
                                        //Check if course only contains letters and white space
                                        if (!preg_match("/^[a-zA-Z ]*$/",$course)){
                                        $message = "<h4>Invalid course. Only letters and white space allowed.</h4>";
                                        }else{
                                            if($db_server){
                                                //Clean the input after DB Connection and Form Validation
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
                                                    $message = "<h4>Username already exists. Please try again.</h4>";
                                                }else{
                                                    //encrypt password with built in hash function
                                                    $hash = password_hash($password, PASSWORD_DEFAULT);
                                                    //Inserting user input into Students Table
                                                    $query = "INSERT INTO Students (username, password, fullname, email, university, level, course) VALUES
                                                    ('$username', '$hash','$fullname', '$email', '$university', '$level', '$course')";
                                                    mysqli_query($db_server, $query) or
                                                    die("Insert failed. ". mysqli_error($db_server));
                                                    $message = "<h3><strong>Thanks " . $fullname . ", your registration was successful! <a href='index.php'> Click here</a> to login. </strong></h3>";
                                                }
                                                mysqli_free_result($result);
                                            }else{
                                                $message = "<h4>Error: could not connect to the database.</h4>";
                                            }
                                            mysqli_close($db_server); //Close Connection
                                        }
                                    }
                                }
                            }
                    }
                }else{
                     $message = "<h4>Both password fields must match</h4>";
                }
            } else {
                $message = "<h4>Please fill in all fields</h4>";
            } 
        } else {
                $message = "<h4>reCAPTCHA failed: </h4>".$data->{'error-codes'}[0];
            } 
    }
    }
?>

<!---------------------- HTML code ------------------------->

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
                <p>In accordance to the 2006 Constitution of the Republic of Indonesia No. 23 Article 4, every Indonesian citizen (Warga Negara Indonesia) who is currently living abroad is required to report their current address and status of residence permit to the Indonesian government. This is done to help protect Indonesian citizens who are in the UK / Ireland, register to vote for the General Election and guarantees immigration and consular services at the Indonesian Embassy in London. More information about this policy can be found <a href="https://indonesianembassy.org.uk/consular/pelayanan-kekonsuleran-bagi-wni/lapor-diri">here</a>.</p>

                <p>PPI Greater Leeds will be using this data to monitor the number of members for the 2019/2020 academic year, as well as the arrangements of organisation-wide events. This data will not be published or shared in any way, shape or form. The confidentiality of data will be maintained and accounted for by PPI Greater Leeds.</p>
                <form action='register.php' method='post'>
                    <?php echo $message; ?>
                    <p class="forms">
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
                            <option value="Foundation Program">Foundation Program</option>
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Masters">Masters</option>
                            <option value="Phd">PHD</option>
                        </select><br />

                        Course:<input type='text' name='course' value='<?php echo $course; ?>'><br />
                    </p>



                    <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                    <input type='submit' name='submit' value='Register'>
                    <input name='reset' type='reset' value='Reset'><br /><br /><br />
                </form>




                <h3>Already a member?</h3>
                <p><a href='index.php'>Click here</a> to login.</p>

                <div>

                </div>

            </div>
            <?php require_once('footer.php')?>
        </div>

    </div>
</body>

</html>