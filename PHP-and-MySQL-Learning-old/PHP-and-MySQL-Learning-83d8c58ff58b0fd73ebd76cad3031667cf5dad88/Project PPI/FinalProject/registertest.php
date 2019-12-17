<?php
require_once("function.php");
// Grab the form data
$submit = trim($_POST['submit']);
$fullname = trim($_POST['fullname']);
$email = trim($_POST['email']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$repeatpassword = trim($_POST['repeatpassword']);
// Create some variables to hold output data
$message = '';
$s_fullname = '';
$s_email = '';
$s_username = '';

// Start to use PHP session
session_start();
// Determine whether user is logged in - test for value in $_SESSION
if (isset($_SESSION['logged'])){
$s_username = $_SESSION['username'];
    $s_fullname = $_SESSION['fullname'];
    $s_email = $_SESSION['email'];

$message = "You are already logged in as $s_fullname.
Please <a href='logout.php'>logout</a> before
trying to register.";
}else{
// Next block of code will go here
    if ($submit=='Register'){
// Process submission here
$captcha=$_POST['g-recaptcha-response'];
 $url = 'https://www.google.com/recaptcha/api/siteverify';
$secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
$response =
file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
 $data = json_decode($response);
if (isset($data->success) AND $data->success==true) {
// Process valid submission data here
    if ($username&&$password&&$repeatpassword){
if ($password==$repeatpassword){
if (strlen($username)>25) {
$message = "Username is too long";
}else{
if (strlen($password)>25||strlen($password)<6) {
$message = "Password must be 6-25 characters long";
}else{
// Process details here
    require_once("db_connect.php"); //include file to do db connect
if($db_server){
//clean the input now that we have a db connection
$username = clean_string($db_server, $username);
$password = clean_string($db_server, $password);
$repeatpassword = clean_string($db_server, $repeatpassword);
mysqli_select_db($db_server, $db_database);
// check whether username exists
$query="SELECT username FROM users WHERE username='$username'";
$result=mysqli_query($db_server, $query);
if ($row = mysqli_fetch_array($result)){
$message = "Username already exists. Please try again.";
}else{
// Process further here
    $hash = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO users (fullname, email, username, password) VALUES
('$fullname', '$email', '$username', '$hash')";
mysqli_query($db_server, $query) or
die("Insert failed. ". mysqli_error($db_server));
$message = "<strong>Registration successful! Welcome $s_fullname </strong>";
    }
mysqli_free_result($result);
}else{
$message = "Error: could not connect to the database.";
}
require_once("db_close.php"); //include file to do db close
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
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </head>
    <body>
        <form action='registertest.php' method='post'>
 <h1>Register</h1>
 <h4><?php echo $message; ?></h4>
Fullname:<input type='text' name='fullname'
value='<?php echo $fullname; ?>'><br />
    Email:<input type='text' name='email'
value='<?php echo $fullname; ?>'><br />
Username:<input type='text' name='username'
value='<?php echo $username; ?>'><br />
 Password: <input type='password' name='password'><br />
 Repeat Password: <input type='password' name='repeatpassword'>
<div class="g-recaptcha"
 data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
 <input type='submit' name='submit' value='Register'>
 <input name='reset' type='reset' value='Reset'>
</form>
    </body>
</html>

