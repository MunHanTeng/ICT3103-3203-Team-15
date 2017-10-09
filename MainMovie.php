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
        include_once 'dbconnect.php';
        $resultCarousel = mysqli_query($MySQLiconn, "SELECT * FROM `movie` WHERE movie_carousel IS NOT NULL");
        $resultMovie = mysqli_query($MySQLiconn, "select * from movie");
        ?>
        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li class="active">Movies</li>
        </ul>


        <div class="container">
            <?php
            $position = 0;
            while ($row = mysqli_fetch_assoc($resultMovie)) {

                if ($position == 0) {
                    echo '<div class="row row-eq-height">';
                }
                echo '<div class="col-md-4 col-sm-4">';
                echo '<div class="item">';
                echo '<a href="movie.php?q=' . $row['movie_id'] . '"><img class="smallposter" src="data:image/jpeg;base64,' . base64_encode($row['movie_poster']) . '"></a>';
                echo '<p>' . $row["movie_name"] . '</p>';
                echo '<p class="Rating">' . $row["movie_type"] . '</p>';
                echo '<p>' . $row["movie_runningTime"] . '</p>';
                echo '<a class="btn btn-primary" href="movie.php?q=' . $row['movie_id'] . '">Buy Tickets</a>';
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
    </div>
    <?php include 'footer.inc'; ?>
</body>
</html>
