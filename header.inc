<?php
include_once 'dbconnect.php';
$directoryURI =basename($_SERVER['SCRIPT_NAME']);
$result = mysqli_query($MySQLiconn, "select * from cinema");
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>


<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <script type= "text/javascript">
                function redirectCinemaPage(cinemaid){
                    document.cookie = "cinemaid =" + cinemaid;
                    window.location = "cinema.php";
                }
            </script>
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
                <li <?php if (strpos($directoryURI,'index.php') !== false) {echo 'class="active"';}?>><a href="index.php"><span class="glyphicon glyphicon-home blue"></span>Home</a></li>
                <li <?php if (strpos($directoryURI,'MainMovie.php') !== false) {echo 'class="active"';}?>><a href="MainMovie.php"><span class="glyphicon glyphicon-film"></span>Movie</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"><span class="glyphicon glyphicon-facetime-video"></span>Cinemas <span class="caret"></span></a>
                    <ul class="dropdown-menu inverse">
                        <li <?php if (strpos($directoryURI,'MainCinema.php') !== false) {echo 'class="active"';} ?>><a href="MainCinema.php">Cinema List</a></li>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            if (strpos($directoryURI,'cinema.php') !== false) {   
                                if (strpos($_COOKIE['cinemaid'], $row['cinema_id']) !== false) {
                                        echo '<li class="active"><a href="javascript:redirectCinemaPage('. $row['cinema_id'] .')" >'.$row['cinema_name'].'</a></li>';
                                        //echo '<li class="active"><a href="cinema.php?q='.$row['cinema_id'].'">'.$row['cinema_name'].'</a></li>';
                                    }
                                    else {
                                        echo '<li><a href="javascript:redirectCinemaPage('. $row['cinema_id'] .')" >'.$row['cinema_name'].'</a></li>';
                                        //echo '<li><a href="cinema.php?q='.$row['cinema_id'].'">'.$row['cinema_name'].'</a></li>';
                                    }
                                }
                            else {
                                echo '<li><a href="javascript:redirectCinemaPage('. $row['cinema_id'] .')" >'.$row['cinema_name'].'</a></li>';

                                //echo '<li><a href="cinema.php?q='.$row['cinema_id'].'">'.$row['cinema_name'].'</a></li>';
                            }
                        }
?>
                    </ul>
                </li>
                <li <?php if (strpos($directoryURI,'MainPromotion.php') !== false) {echo 'class="active"';}?>><a href="MainPromotion.php"><span class="glyphicon glyphicon-usd"></span>Promotions</a></li>                
            </ul>
            <?php 
            if(isset($_SESSION['name'])!= '') { 
                echo '<ul class="nav navbar-nav navbar-right"><li><p class="navbar-text">Welcome, '.$_SESSION['name'].' | <a href="logout.php?logout">Logout</a></p></li></ul>';
            }
            else {
                echo '
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown" id="menuLogin">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown" id="navLogin">Login</a>
                        <div class="dropdown-menu" style="background-color: #333;">
                            <form class="form" method="post" action="login.php">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name="pwd" placeholder="Password" required>
                                </div>
                                <button name="submit" type="submit" class="btn btn-primary">Sign In</button>
                                <p><a href="registerUser.php">Register</a></p>    
                            </form>
                        </div>
                    </li>
                </ul>
                '; 
            }
?>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>