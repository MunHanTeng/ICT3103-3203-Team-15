<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />
        <script type= "text/javascript">
            function redirectCinemaPage(cinemaid){
                document.cookie = "cinemaid =" + cinemaid;
                window.location = "cinema.php";
            }
        </script>
    </head>
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <?php
        include 'header.inc';
        include_once 'dbconnect.php';
        $resultCinema = mysqli_query($MySQLiconn, "SELECT * FROM cinema");
        ?>
        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li class="active">Movies</li>
        </ul>

        <div class="container">
            <br />
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="panel-group mobileHidden" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4>
                                About Golden Village Cinemas
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse">
                            <div class="panel-body">
                                <p>
                                    Golden Village is Singapore's leading cinema exhibitor with 11 multiplexes housing
                                    92 screens with locations at Yishun (1992), Bishan Junction 8 (1993), Tiong Bahru
                                    Plaza (1994), Jurong Point (1995), Tampines Mall (1996), Plaza Singapura (1998),
                                    Great World City (1999), VivoCity home to GV’s flagship and Singapore’s only
                                    megaplex, (2006), 112 Katong (2011, the world’s first Peranakan inspired cinema),
                                    City Square (2012) and it’s latest downtown flagship Suntec City (2014) with a total
                                    capacity of 1,390 seats, consisting of 8 auditoriums and 3 Gold Class cinemas.

                                    <br /><br />
                                    Golden Village was established to develop and operate modern, luxurious multiplex
                                    cinemas and is the first local cinema to personalize the movie-going experience
                                    through its Movie Club program. The prime mover in the introduction of the
                                    multiplex to Asia, Golden Village’s first imprint in Singapore was made on 28 May
                                    1992 with the successful opening of the Yishun 10 cinema. Today, Golden Village has
                                    a reputation of offering the widest choice of movies, unparalleled comfort,
                                    state-of-the-art cinema design, prime locations and technological capabilities
                                    such as Quick tix™, iGV app and the auto-gate system to enhance the movie-going
                                    experience for customers.
                                    VHS.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br />
            <br />

            <h4>To view cinema information and showtimes, please click on the cinema below.</h4>


                <?php
                $position = 0;
                while ($row = mysqli_fetch_assoc($resultCinema)) {

                    if ($position == 0) {
                        echo '<div class="row row-eq-height">';
                    }
                    echo '<div class="col-md-4 col-sm-4">';
                    echo '<div class="mainCinemaBG"><h4>'.$row['cinema_name'].'</h4></div>';
                    echo '<div class="item">';
                    echo '<a href="javascript:redirectCinemaPage('. $row['cinema_id'] .')"><img class="cinema-poster" src="data:image/jpeg;base64,' . base64_encode($row['cinema_image']) . '"></a>';
                    //echo '<a href="cinema.php?q=' . $row['cinema_id'] . '"><img class="cinema-poster" src="data:image/jpeg;base64,' . base64_encode($row['cinema_image']) . '"></a>';
                    $address = explode(",", $row['cinema_address']);
                    echo '<br><br><p>';
                    for ($i = 0; $i < count($address); $i++) {
                        if ($i != 0) {
                            echo '<br>';
                        }
                        echo $address[$i];
                    }
                    if (count($address) < 4) {
                        for($i = -1; $i < (4 - count($address)); $i++) {
                            echo '<br>';
                        }
                    }
                    echo '</p>';
                    echo '<a href="javascript:redirectCinemaPage('. $row['cinema_id'] .')" class="btn btn-primary">Buy Tickets</a>';
                    //echo '<a class="btn btn-primary" href="cinema.php?q=' . $row['cinema_id'] . '">Buy Tickets</a>';
                    echo '</div></div>';
                    if ($position == 2) {
                        echo '</div>';
                        $position = 0;
                    } else {
                        $position++;
                    }
                }
                if ($position != 2) {
                    echo '</div>';
                }
                ?>

        </div>
        <?php include 'footer.inc'; ?>
    </body>
</html>
