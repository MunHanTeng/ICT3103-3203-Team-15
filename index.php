<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />
        <script src="js/override.js" type="text/javascript"></script> 

    </head>
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <?php
        include 'header.inc';
        include_once 'dbconnect.php';
        $resultCarousel = mysqli_query($MySQLiconn, "SELECT movie_id,movie_carousel FROM `movie` WHERE movie_carousel IS NOT NULL");
        $resultMovie = mysqli_query($MySQLiconn, "select movie_id,movie_name,movie_type,movie_runningTime,movie_carousel,movie_poster from movie");
        ?>
        <div id="homeCarousel" class="carousel slide">
            <ol class="carousel-indicators">
                <?php
                $i = 0;
                while ($row = mysqli_fetch_assoc($resultCarousel)) {
                    if ($i == 0) {
                        echo '<li data-target="#homeCarousel" data-slide-to="' . $i . '" class="active"></li>';
                    } else {
                        echo '<li data-target="#homeCarousel" data-slide-to="' . $i . '"></li>';
                    }
                    $i++;
                }
                ?>

            </ol>
            <div class="carousel-inner">
                <?php
                $i = 0;
                mysqli_data_seek($resultCarousel, 0);
                while ($row = mysqli_fetch_assoc($resultCarousel)) {
                    if ($i == 0) {
                        echo '<div class="item active">';
                        $i++;
                    } else {
                        echo '<div class="item">';
                    }

                    echo '<a href="javascript:redirectMoviePage('. $row['movie_id'] .')"><img src="data:image/jpeg;base64,' . base64_encode($row['movie_carousel']) . '"></a>';
                    echo '</div>';
                }
                ?>
            </div>
            <a class="left carousel-control" href="#homeCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#homeCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <br />
        <div class="container">
            <?php
            $position = 0;
            while ($row = mysqli_fetch_assoc($resultMovie)) {

                if ($position == 0) {
                    echo '<div class="row row-eq-height">';
                }
                echo '<div class="col-md-4 col-sm-4">';
                echo '<div class="item">';
                echo '<a href="javascript:redirectMoviePage('. $row['movie_id'] .')"><img class="smallposter" src="data:image/jpeg;base64,' . base64_encode($row['movie_poster']) . '"></a>';
                echo '<p>' . $row["movie_name"] . '</p>';
                echo '<p class="Rating">' . $row["movie_type"] . '</p>';
                echo '<p>' . $row["movie_runningTime"] . '</p>';
                echo '<a class="btn btn-primary" href="javascript:redirectMoviePage('. $row['movie_id'] .')">Buy Tickets</a>';
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
