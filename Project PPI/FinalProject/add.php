<?php
    require_once("db_connect.php");
    require_once("function.php");
    // Grab the form data
    $submit = trim($_POST['submit']);
    $fullname = clean_string($db_server, $_POST["fullname"]);
    $email = trim($_POST['email']);
    $course = trim($_POST['course']);
    $university = trim($_POST['university']);
    $yearlevel = trim($_POST['yearlevel']);
    // Create some variables to hold output data
    $message = '';
    $s_username = '';
    echo $submit;

    if ($submit=='Register'){
        $captcha=$_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
        $response =
        file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
        $data = json_decode($response);
        if (isset($data->success) AND $data->success==true) {
            mysqli_select_db($db_server, $db_database);
            $query = "INSERT INTO Students (FullName, Email, University, Course, YearLevel) VALUES ('$fullname', '$email', '$university', '$course', '$yearlevel')";
            if (mysqli_query($db_server, $query)){
                $message = "<strong>Registration successful!</strong>";
            } else {
                $message = "Insert failed.<br />". mysqli_error($db_server);
            }
            
        } else {
            $message = "reCAPTCHA failed: ".$data->{'error-codes'}[0];
        }
    }
?>


<html>
	<head>
		<meta charset="utf-8">
		<title>Leeds Indonesian Student Association</title>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="stylesheet.css">
	</head>
	<body>
    	<div id="wrapper">
            <div id="main">
                <div id="header">
                    <div id = "logo">
                        <a href="index.php"><img src="images/logo.png" width="100%" alt="PPI Greater Leeds Logo"></a>
                    </div>
                    <div class="main-navbar" id="main-navbar">
                        <a href="account.php">Your Account (<?php echo $_SESSION['username'] ?>)</a>
                        <a href="forum.php">Forum</a>
                        <a href="add.php">Add Your Details</a>
                        <a href="search.php">Member Search</a>
                        <a class="current" href="home.php">Home</a>
                    </div>
                 </div>

                <div class="main-info">
                    <h1>Enter your details here:</h1>
                    <p>In accordance to the 2006 Constitution of the Republic of Indonesia No. 23 Article 4, every Indonesian citizen (Warga Negara Indonesia) who is currently living abroad is required to report their current address, status of residence permit, and other important events (such as birth, marriage, divorce, or death) to the Indonesian government. For this reason, everyone who plans on staying for more than 5 (five) days in the United Kingdom / Ireland, either for studying, travelling, working either temporarily or permanently, needs to report to the Indonesian Embassy in London.</p>
                    <p>This is done to help protect Indonesian citizens who are in the UK / Ireland, register to vote for the General Election and guarantees immigration and consular services at the Indonesian Embassy in London. More information about this policy can be found <a href="https://indonesianembassy.org.uk/consular/pelayanan-kekonsuleran-bagi-wni/lapor-diri">here</a>.</p>
                    <p>PPI Greater Leeds will be using this data to monitor the number of members for the 2019/2020 academic year, as well as the arrangements of organisation-wide events. This data will not be published or shared in any way, shape or form. The confidentiality of data will be maintained and accounted for by PPI Greater Leeds.</p>
                    <p>To add your details, please fill in all boxes below.</p>
                    <div class ="login-register">
                        <form action='add.php' method='post'>
                            <h4><?php echo $message; ?></h4>
                            Full Name:<input type='text' name='fullname'
                            value='<?php echo $fullname; ?>'><br />
                            Email:<input type='text' name='email'
                            value='<?php echo $email; ?>'><br />
                            University:<input type='text' name='university'
                            value='<?php echo $university; ?>'><br />
                            Course:<input type='text' name='course'
                            value='<?php echo $course; ?>'><br />
                            Year Level:<input type='text' name='yearlevel'
                            value='<?php echo $yearlevel; ?>'><br />
                            <div class="g-recaptcha"
                            data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g"></div>
                            <input type='submit' name='submit' value='Register'>
                            <input name='reset' type='reset' value='Reset'>
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

    
