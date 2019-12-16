<html>

<head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
</head>

<body>
    <div id="header">
        <div id="logo">
            <a href="home.php"><img src="images/logo.png" width="100%" alt="PPI Greater Leeds Logo"></a>
        </div>
        <div class="main-navbar" id="main-navbar">
            <a class="nav" href="account.php">Your Account (<?php echo $_SESSION['username'] ?>)</a>
            <a class="nav" href="forum.php">Forum</a>
            <a class="nav" href="search.php">Member Search</a>
            <a class="nav" href="home.php">Home</a>
        </div>
    </div>
</body>

</html>