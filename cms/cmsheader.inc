<?php
    include_once '../dbconnect.php';
    $page_url = $_SERVER['REQUEST_URI'];
    //$active1 = (strpos($page_url, "index.php") !== false ? " class=\"active\"" : ""); 
    //$active2 = (strpos($page_url, "register.php") !== false ? " class=\"active\"" : ""); 
    $directoryURI =basename($_SERVER['SCRIPT_NAME']);        
    if(!isset($_SESSION['user']))
    {
        header("Location: index.php");
    }
?>
<link href="../images/gv32x32.ico" rel="shortcut icon" />
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                Golden Village
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse" aria-expanded="false" style="height: 1px;">
            <ul class="nav navbar-nav">
                <li <?php if (strpos($directoryURI,'cmsHome.php') !== false) {echo 'class="active"';}?>><a href="cmsHome.php"><span class="glyphicon glyphicon-home blue"></span> Home</a></li>
                <li <?php if (strpos($directoryURI,'cmsMovie.php') !== false) {echo 'class="active"';}?>><a href="cmsMovie.php"><span class="glyphicon glyphicon-film blue"></span>Manage Movie</a></li>
                <li <?php if (strpos($directoryURI,'cmsCinema.php') !== false) {echo 'class="active"';}?>><a href="cmsCinema.php"><span class="glyphicon glyphicon-facetime-video blue"></span>Manage Cinema</a></li>
                <li <?php if (strpos($directoryURI,'cmsManageShowtime.php') !== false) {echo 'class="active"';}?>><a href="cmsManageShowtime.php"><span class="glyphicon glyphicon-time blue"></span>Manage Showtime</a></li>
                <li <?php if (strpos($directoryURI,'cmspromo.php') !== false) {echo 'class="active"';}?>><a href="cmspromo.php"><span class="glyphicon glyphicon-usd blue"></span>Manage Promotions</a></li>
                <li <?php if (strpos($directoryURI,'cmsManageUser.php') !== false) {echo 'class="active"';}?>><a href="cmsManageUser.php"><span class="glyphicon glyphicon-user blue"></span>Manage Users</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
         <p class="labelWel">Welcome <?php echo $userRow['username']; ?> | <a href="cmsLogout.php">Logout</a></p>
        <br />
