<html>
    <head>
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />
        <script src="js/override.js" type="text/javascript"></script> 
    </head>
    <body onLoad="backButtonOverride()">
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <?php
	//include_once __DIR__ .'../csrfp/libs/csrf/csrfprotector.php';	
	//csrfProtector::init();
        include 'header.inc';
        include_once 'dbconnect.php';
        $movid = $_COOKIE['movieID'];
        $stmt = $MySQLiconn->prepare("SELECT movie_name,movie_type,movie_cast,movie_director,movie_genre,movie_release,movie_runningTime,movie_distributor,movie_language,movie_synopsis,movie_TNC,movie_trailerLink,movie_websiteLink,movie_poster,movie_carousel FROM movie WHERE movie_id = ?");
        $stmt->bind_param('s', $movid);
        if (!$stmt->execute()) {
            header("Location:errorPage.php");
        }
        $result = $stmt->get_result();
        $movie = mysqli_fetch_assoc($result);
        ?>
        <form id="myform" action="bookTicket.php" method="POST">
            <input type="hidden" id="SIID" name="SIID">
        </form>
        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li><a href="MainMovie.php" class="activeLink">Movies</a> <span class="divider"></span></li>
            <li class="active"><?php echo $movie['movie_name'] ?></li>
        </ul>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php echo $movie['movie_name'] ?></h2>
                    <p class="Rating"><?php echo $movie['movie_type'] ?></p>
                </div>
            </div>
            <hr />
            <div class="row">
                <div class="col-md-4 text-center">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($movie['movie_poster']); ?>"> 
                    <br /><br />
                    <a class="btn btn-default" href="<?php echo $movie['movie_websiteLink']; ?>">Website</a>
                </div>

                <div class="col-md-8 col-pg-zero" id="moviedetails">
                    <h4>Details</h4>
                    <div class="row col-pg-zero">
                        <div class="col-md-6 col-xs-12 col-pg-zero">
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Cast:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_cast']; ?></p>
                            </div>
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Director:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_director']; ?></p>
                            </div>
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Genre:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_genre']; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12 col-pg-zero">
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Release:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_release']; ?></p>
                            </div>
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Running Time:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_runningTime']; ?></p>
                            </div>
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Distributor:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_distributor']; ?></p>
                            </div>
                            <div class="col-md-4 col-xs-5 col-pg-zero">
                                <p class="details-title">Language:</p>
                            </div>
                            <div class="col-md-8 col-xs-7">
                                <p><?php echo $movie['movie_language']; ?></p>
                            </div>
                        </div>
                    </div>
                    <h4>Synopsis</h4>
                    <p><?php echo $movie['movie_synopsis']; ?></p>
                    <table>
                        <tr>
                            <td class="termsTitle">Terms and Conditions</td>
                        </tr>
                        <?php
                        $tnc = explode("- ", $movie['movie_TNC']);
                        for ($i = 1; $i < count($tnc); $i++) {
                            echo '<tr><td class="terms">';
                            echo '-' . $tnc[$i];
                            echo '</td></tr>';
                        }
                        ?>
                    </table>
                    <div class="embed-responsive embed-responsive-16by9">

                        <iframe class="embed-responsive-item" src=<?php echo'"' . $movie['movie_trailerLink'] . '"' ?>></iframe>
                    </div>
                </div>
            </div>
            <div class="row">
                <h3>Buy Tickets</h3>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php
                            $sql = "SELECT DISTINCT cinema.cinema_id, cinema.cinema_name FROM `cinema` WHERE cinema.cinema_id in (SELECT showinfo.cinema_id FROM showinfo WHERE showinfo.movie_id =?)";
                            $stmt = $MySQLiconn->prepare($sql);
                            $stmt->bind_param('s', $movid);
                            if (!$stmt->execute()) {
                                header("Location:errorPage.php");
                            }
                            $resultCinema = $stmt->get_result();
                            while ($row = mysqli_fetch_assoc($resultCinema)) {
                                echo '<h4 class="Collapseh4">';
                                echo '<a data-toggle="collapse" data-parent="#accordion" class="activeLink" href="#collapse' . $row['cinema_id'] . '" class="">';
                                echo'<span class="glyphicon glyphicon-collapse-down"></span>' . $row['cinema_name'];
                                echo '</a>';
                                echo'</h4>';
                            }
                            ?>
                        </div>
                        <div id="collapse" class="panel-collapse collapse">
                            <div class="panel-body"></div>
                        </div>                        
<?php
mysqli_data_seek($resultCinema, 0);
while ($row = mysqli_fetch_assoc($resultCinema)) {
    echo '<div id="collapse' . $row['cinema_id'] . '" class="panel-collapse collapse">';
    echo '<div class="panel-body">';
    echo '<table class="tickets">';
    //echo '<tr><td><h4>'.$row['cinema_name'].'</h4></td></tr>';
    $sqlDate = "Select DISTINCT showInfo_date from showinfo where movie_id=? and cinema_id=?";
    $resultShow = mysqli_query($MySQLiconn, $sqlDate);
    $stmt = $MySQLiconn->prepare($sqlDate);
    $stmt->bind_param('ss', $movid, $row['cinema_id']);
    if (!$stmt->execute()) {
        header("Location:errorPage.php");
    }
    $resultShow = $stmt->get_result();

    while ($date = mysqli_fetch_assoc($resultShow)) {
        echo '<tr><td>';
        echo '<p>' . $date['showInfo_date'] . '</p>';

        $sqlTime = "Select showInfo_id, showInfo_time from showinfo where movie_id=? and cinema_id=? and showInfo_date=?";
        $stmt = $MySQLiconn->prepare($sqlTime);
        $stmt->bind_param('sss', $movid, $row['cinema_id'], $date['showInfo_date']);
        $stmt->execute();
        if (!$stmt->execute()) {
            header("Location:errorPage.php");
        }
        $resultTime = $stmt->get_result();

        while ($time = mysqli_fetch_assoc($resultTime)) {
            echo '<a href="javascript:redirectPaymentPage(' . $time['showInfo_id'] . ')" class="btn btn-primary">' . $time['showInfo_time'] . '</a>';
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
            </div>
                        <?php include "footer.inc"; ?>
    </body>
</html>