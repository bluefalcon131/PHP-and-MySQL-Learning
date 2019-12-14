<?php
require("function.php");
include("checklog.php");
include("db_connect.php");

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];
$university = $_SESSION['university'];
$level = $_SESSION['level'];
$course = $_SESSION['course'];

$newusername = trim($_POST['newusername']);
$newfullname = trim($_POST['newfullname']);
$newemail = trim($_POST['newemail']);
$newuniversity = trim($_POST['newuniversity']);
$newlevel = trim($_POST['newlevel']);
$newcourse = trim($_POST['newcourse']);

session_start();
if(isset($_POST['submit'])){

        if (strlen($username)>25){
            $message = "Username is too long";
            
            }else{
                require_once("db_connect.php"); 

                if($db_server){ 

                    // Cleaning all string inputs
                    $fullname = clean_string($db_server, $fullname);
                    $email = clean_string($db_server, $email);
                    $university = clean_string($db_server, $university);
                    $level = clean_string($db_server, $level);
                    $course = clean_string($db_server, $course);
                    $username = clean_string($db_server, $username);

                    // Selecting Database
                    mysqli_select_db($db_server, $db_database);

                    // Check whether the new username exists
                    $query="SELECT username FROM Students WHERE username='$newusername'";
                    $result=mysqli_query($db_server, $query);
                    if (($row = mysqli_fetch_array($result)) && ($username != $newusername)){
                        $message = "Username already exists. Please try again.";
                    }else{
                        // Updating database values
                        $query = "UPDATE Students SET
                        Username = '$newusername',
                        FullName = '$newfullname',
                        Email = '$newemail',
                        University = '$newuniversity',
                        Level = '$newlevel',
                        Course = '$newcourse' 
                        WHERE ID =". $_SESSION['userID']."";
                        mysqli_query($db_server, $query) or
                        die("Insert failed. ". mysqli_error($db_server));

                        // Updating Session Values
                        $_SESSION['username'] = $newusername;
                        $_SESSION['fullname']= $newfullname;
                        $_SESSION['email'] = $newemail;
                        $_SESSION['university'] = $newuniversity;
                        $_SESSION['level'] = $newlevel;
                        $_SESSION['course'] = $newcourse;
                        
                        // Updating Page Values
                        $username = $newusername;
                        $fullname = $newfullname;
                        $email = $newemail;
                        $university = $newuniversity;
                        $level = $newlevel;
                        $course = $newcourse;

                        $message = "<strong>Profile updated successfully! <a href='account.php'> Click here </a>to go back to your account page. </strong>";
                }
                require_once("db_close.php");

            }
        }
}

?>

<html>
<script>
function resetSelections() {    // Resets all the selection into recorded values in this page
    document.getElementById("usernameInput").value = '<?php echo $username; ?>';
    document.getElementById("fullnameInput").value = '<?php echo $fullname; ?>';
    document.getElementById("emailInput").value = '<?php echo $email; ?>';
    document.getElementById("universitySelect").value = '<?php echo $university; ?>';
    document.getElementById("levelSelect").value = '<?php echo $level; ?>';
    document.getElementById("courseInput").value = '<?php echo $course; ?>';
}
</script>
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
                <?php require_once("header_logged.php")?>
                
                <div class="hero">
                    <img src="images/hero-home.jpg" alt="Indonesian Student Association" width="100%">
                </div>

                <div class="main-info"> 
                    <h1>Change User Details</h1>

                    <form method="post" action="edit_details.php">
                        <h4><?php echo $message; ?></h4> 
                        Username:<input id='usernameInput' type='text' name='newusername'><br />
                        
                        Full Name:<input id='fullnameInput' type='text' name='newfullname'><br />
                        
                        Email:<input id='emailInput' type='text' name='newemail'><br />
                        
                        University:<select id ="universitySelect" name="newuniversity">
                        <option value="University of Leeds">University of Leeds</option>
                        <option value="Leeds Beckett University">Leeds Beckett University</option>
                        <option value="Leeds Art University">Leeds Art University</option>
                        <option value="Leeds College of Music">Leeds College of Music</option>
                        <option value="University of Huddersfield">University of Huddersfield</option>
                        <option value="University of Bradford">University of Bradford</option>
                        </select><br />
                        
                        Level:<select id ="levelSelect" name="newlevel">
                        <option value="FoundationProgram">Foundation Program</option>
                        <option value="Undergraduate">Undergraduate</option>
                        <option value="Masters">Masters</option>
                        <option value="Phd">PHD</option>
                        </select><br />
                        
                        Course:<input id='courseInput' type='text' name='newcourse'><br />

                        <div> 
                            <input type='submit' name='submit' value='Submit'>
                            <button type='button' name='reset' onclick='resetSelections()'>Reset</button>
                        </div>
                        <script>resetSelections()</script>
                </form>
                </div>
                
                <div id="footer">
                    <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
                 </div>
            </div>

        </div>
    </body>
</html>