<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />

    </head>
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <?php
        include 'header.inc';
        if (isset($_GET['q']) == "") {
            header("Location: MainCinema.php");
        }
        include_once 'dbconnect.php';
        $result = mysqli_query($MySQLiconn, "SELECT * FROM `cinema` WHERE cinema_id ='" . $_GET['q'] . "'");
        $cinema = mysqli_fetch_assoc($result)
        ?>

        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li><a href="MainCinema.php" class="activeLink">Cinemas</a> <span class="divider"></span></li>
            <li class="active"><?php echo $cinema['cinema_name'] ?></li>
        </ul>
        <!--Beginning of Content-->
        <div class="container">
            <div class="row"> <!--Main Header-->
                <div class="col-md-12">
                    <h2><?php echo $cinema['cinema_name'];?></h2>
                </div>
            </div>
            <hr /> <!--White Line-->
            <div class="row"><!--This row contains the details of the movie-->
                <div class="row"><!--This row contains one row of details-->
                    <div class="col-xs-3">
                        <p>No of Screens</p>
                    </div>
                    <div class="col-xs-9">
                        <p><?php echo $cinema['No_Of_Screen'];?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <p>Address</p>
                    </div>
                    <div class="col-xs-9">
                        <p><?php echo $cinema['cinema_address'];?></p>
                        <!--https://support.google.com/maps/answer/3544418?hl=en Make sure you set the embed code to small-->
                        <?php echo $cinema['cinema_googleMap'];?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-3">
                        <p>Public Transport</p>
                    </div>
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-2">
                                <p>MRT</p>
                            </div>
                            <div class="col-xs-10">
                                <p><?php echo $cinema['cinema_mrt'];?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <p>BUS</p>
                            </div>
                            <div class="col-xs-10">
                                <p><?php echo $cinema['cinema_bus'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h3>Showtimes</h3>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php
                            $sql = "SELECT DISTINCT showInfo_date from showinfo where cinema_id = '".$_GET['q']."'";
                            $resultDate = mysqli_query($MySQLiconn, $sql);
                            
                            while ($date = mysqli_fetch_assoc($resultDate)) {
                                echo '<h4 class="Collapseh4">';
                                echo '<a data-toggle="collapse" data-parent="#accordion" class="activeLink" href="#collapse'.$date['showInfo_date'].'" class="">';
                                    echo'<span class="glyphicon glyphicon-collapse-down"></span>'.$date['showInfo_date']; 
                                echo '</a>';
                            echo'</h4>';
                            }
                            ?>
                        </div>
                        <div id="collapse" class="panel-collapse collapse">
                            <div class="panel-body"></div>
                        </div>                        
                        <?php
                            mysqli_data_seek($resultDate, 0);
                        while ($date = mysqli_fetch_assoc($resultDate)) {
                            echo '<div id="collapse'.$date['showInfo_date'].'" class="panel-collapse collapse">';
                            echo '<div class="panel-body">';
                            echo '<table class="tickets">';
                            $sqlMovie = "SELECT DISTINCT movie.movie_id, movie.movie_name, movie.movie_type FROM movie WHERE movie.movie_id in (SELECT showinfo.movie_id FROM showinfo WHERE showinfo.cinema_id ='".$_GET['q']."' AND showinfo.showInfo_date ='".$date['showInfo_date']."')";
                            $resultMovie = mysqli_query($MySQLiconn, $sqlMovie);
                            while ($Movie = mysqli_fetch_assoc($resultMovie)) {
                                echo '<tr><td>';
                                echo '<a href="movie.php?q='.$Movie['movie_id'].'"><h4>'.$Movie['movie_name'].'</h4></a>';
                                echo '<p class="Rating">'.$Movie['movie_type'].'</p>';
                                $sqlTime = "Select * from showinfo where cinema_id='".$_GET['q']."' and movie_id='".$Movie['movie_id']."' and showInfo_date='".$date['showInfo_date']."'";
                                $resultTime = mysqli_query($MySQLiconn, $sqlTime);
                                while ($time = mysqli_fetch_assoc($resultTime)) {
                                    echo '<a href="bookTicket.php?q='.$time['showInfo_id'].'" class="btn btn-primary">'.$time['showInfo_time'].'</a>';
                                }
                                echo '</td></tr>';
                            }
                            echo '</div>';
                            echo '</table>';
                            echo '</div>';
                            echo '</div>';
                        }

                        ?>
                    </div>
                </div>
    </body>
</html>