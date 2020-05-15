<?php
session_start();
// Unset all of the session variables.
$_SESSION = array();
// Destroy the session
session_destroy();
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
                <h1><strong>You have logged out.</strong></h1>
                <p>
                    <p>Click <a href='index.php'>here</a> to go back to the homepage.</p>
            </div>
            <?php require_once('footer.php')?>
        </div>

    </div>



</body>

</html>