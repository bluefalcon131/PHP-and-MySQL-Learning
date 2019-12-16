<?php
    require_once 'db_connect.php';
    require_once('checklog.php');
    $username=$_SESSION['username'];
    require_once 'function.php';
    $submit = trim($_POST['submit']);
    $data = json_decode($response);
    $output = "";
    if ($submit=='Search'){
        $captcha=$_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $secretkey = "6Le4CAETAAAAAGQftFiDise1KTxFd6qTsowFR-TL"; //secret key
        $response =
        file_get_contents($url."?secret=".$secretkey."&response=".$captcha);
        $data = json_decode($response);
        if (isset($data->success) AND $data->success==true) {
            mysqli_select_db($db_server, $db_database);
            $category_input = clean_string($db_server, $_POST["category_input"]);
            $search_input = clean_string($db_server, $_POST["search_input"]);
            $query = "SELECT ID, FullName, Course, University, Level, Email FROM Students WHERE $category_input LIKE '%$search_input%'";
            // query the database
            mysqli_select_db($db_server, $db_database);
            $result = mysqli_query($db_server, $query);
            mysqli_query($db_server, "INSERT INTO Students FullName VALUES 'test'");
            if (!$result) die("Database access failed: " . mysqli_error($db_server));
            $message = "<strong>Your search found the following students:</strong>" . "<br/><br/>";
            $tableh ='<th width="450" align="left">Full Name</th>
                    <th width="150" align="left">Degree</th>
                    <th width="600" align="left">Course</th>
                    <th width="400" align="left">University</th>
                    <th width="250" align="left">Email</th>';
            while($row = mysqli_fetch_array($result)){ 
                $tabler .= "<tr><td>" . $row['FullName'] . "</td><td>" . $row['Level'] . "</td><td>" . $row['Course'] . "</td><td>" . $row['University'] . "</td><td>" . " <a href='mailto:" . $row['Email'] . "' target='_blank' >" . $row['Email'] . "</a>" . "</td></tr>";
            }
            mysqli_free_result($result);
            //echo $message . $output;
        }else{
            // What happens when the CAPTCHA was entered incorrectly
            $message = "The reCAPTCHA failed. (" . $data->{'error-codes'}[0] . ")";
        }
    }

?>
<html>

<head>
    <meta charset="utf-8">
    <title>Leeds Indonesian Student Association</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <div id="wrapper">
        <div id="main">
            <?php require_once('header_logged.php')?>
            <div class="main-info">
                <h1>Search for Members</h1>
                <p>You can use the Search bar to look for other members in the society based on their name, course, and university. Simply select a category from the dropdown and enter your search keyword. If you'd like to contact anyone, click on their email address to send them an email.</p>
                <form action="search.php" method="post">
                    <select class="category" name="category_input">
                        <option value="FullName">Name</option>
                        <option value="Course">Course</option>
                        <option value="University">University</option>
                        <option value="Level">Level</option>
                    </select><br />
                    <input class="category" type="text" name="search_input" placeholder="Enter your search keyword here"/><br />
                    <input type="submit" class="category" name="submit" value="Search" /><br/><br/>
                    <div class="g-recaptcha" data-sitekey="6Le4CAETAAAAAJ58ZxBrDGRawcYuHhjxIXJoZ45g">
                    </div>
                </form>
                <h3><?php echo $message; ?></h3>
                <p>
                    <?php echo "<table>" . $tableh . $tabler . "</table>"; ?>
                </p>
            </div>
            <div id="footer">
                <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>