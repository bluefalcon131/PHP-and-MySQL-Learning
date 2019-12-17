<?php
    require_once('checklog.php');
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
                    <h1><strong>Hello  <?php echo $_SESSION['fullname']?>, Welcome to PPI Greater Leeds!</strong></h1>
                    <p><strong>Perhimpunan Pelajar Indonesia Greater Leeds (PPI Greater Leeds, also known as the Indonesian Student Association in Leeds)</strong> is a Leeds-based Indonesian student organisation that has been actively promoting Indonesian culture and diversity in Leeds. We constantly organise events for our members to get together throughout the year. We are suprvised by the Indonesian Embassy in London. Our mission is to strengthen and cultivate better communication and relations within current students, alumni, as well as the local Indonesian community in Leeds, Huddersfield and Bradford.</p>
                    <p>Here, you'll be able to search and network with other Indonesian students in Leeds, register your details into our system, and have meaningful discussions in our online forum! These are all accessible on the navigation bar above.</p>
                </div>
                <div id="footer">
                    <p class="footer">© 2019 <a class="footer-link" href="http://www.corinagunawidjaja.myportfolio.com">Corina Gunawidjaja</a>. All Rights Reserved.</p>
                 </div>
            </div>

        </div>

                

    </body>
</html>


