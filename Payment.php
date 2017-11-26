<html lang="en">
    <head>
        <meta charset="UTF-8">
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
        <script src="js/paymentValidate.js" type="text/javascript"></script> 
        <?php
        include 'header.inc';
        include_once 'dbconnect.php';

        function trim_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (!isset($_SESSION['name'])) {
            echo '<script language="javascript">';
            echo 'alert("Please Login in order to be able to buy ticket sucessfully"); location.href="index.php"';
            echo '</script>';
        }

        $check_list = $_SESSION['check_list'];
        $PaymentMode = $_SESSION['PaymentMode'];
        $showInfoID = $_SESSION['show_id'];
        ?>

        <?php
        // RETRIEVE SHOW INFO
        $showInfoQuery = $MySQLiconn->prepare("SELECT showInfo_date, showInfo_time, cinema_id, movie_id FROM showinfo WHERE showInfo_id = ?");
        $showInfoQuery->bind_param('i', $showInfoID);
        if (!$showInfoQuery->execute()) {
            unset($_SESSION['PaymentMode']);
            unset($_SESSION['check_list']);
            unset($_SESSION['show_id']);
            unset($_SESSION['CCN']);
            unset($_SESSION['CCE']);
            unset($_SESSION['CVV2']);
            unset($_SESSION['CCName']);
            unset($_SESSION['payment']);
            unset($_SESSION["session_check_list"]);
            unset($_SESSION["buy_ticket"]);
            header("Location:errorPage.php");
        }
        $showInfoResult = $showInfoQuery->get_result();

        $showinfo = mysqli_fetch_assoc($showInfoResult);

        // RETRIEVE MOVIE 
        $movieQuery = $MySQLiconn->prepare("SELECT movie_name, movie_poster, movie_websiteLink FROM movie WHERE movie_id = ?");
        $movieQuery->bind_param('i', $showinfo['movie_id']);
        if (!$movieQuery->execute()) {
            unset($_SESSION['PaymentMode']);
            unset($_SESSION['check_list']);
            unset($_SESSION['show_id']);
            unset($_SESSION['CCN']);
            unset($_SESSION['CCE']);
            unset($_SESSION['CVV2']);
            unset($_SESSION['CCName']);
            unset($_SESSION['payment']);
            unset($_SESSION["session_check_list"]);
            unset($_SESSION["buy_ticket"]);
            header("Location:errorPage.php");
        }
        $movieResult = $movieQuery->get_result();

        $movie = mysqli_fetch_assoc($movieResult);

        // RETRIEVE CINEMA 
        $cinemaQuery = $MySQLiconn->prepare("SELECT cinema_name FROM cinema WHERE cinema_id = ?");
        $cinemaQuery->bind_param('i', $showinfo['cinema_id']);
        if (!$cinemaQuery->execute()) {
            unset($_SESSION['PaymentMode']);
            unset($_SESSION['check_list']);
            unset($_SESSION['show_id']);
            unset($_SESSION['CCN']);
            unset($_SESSION['CCE']);
            unset($_SESSION['CVV2']);
            unset($_SESSION['CCName']);
            unset($_SESSION['payment']);
            unset($_SESSION["session_check_list"]);
            unset($_SESSION["buy_ticket"]);
            header("Location:errorPage.php");
        }
        $cinemaResult = $cinemaQuery->get_result();

        $cinema = mysqli_fetch_assoc($cinemaResult);

        $PaymentModeValue = array(
            "Standard Price - $12.50" => 12.50,
            "Visa Checkout- $12.00" => 12,
            "DBS/POSB Credit & Debit - $7.50" => 7.50
        );
        ?>
        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li><a href="MainMovie.php" class="activeLink">Movies</a> <span class="divider"></span></li>
            <li class="active"><?php echo $movie['movie_name'] ?></li>
        </ul>

        <div class="container">

            <div class="col-md-4 text-center">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($movie['movie_poster']); ?>"> 
                <br /><br />
                <a class="btn btn-default" href="<?php echo $movie['movie_websiteLink']; ?>">Website</a>
            </div>

            <div class="col-md-8 text-center">
                <div class="container-fluid" style="background-color:#303030 ;padding-bottom: 10px">
                    <div class="row"> 
<?php
echo '<p>You have selected movie: <span style="font-size:2.5em;color:yellow;">' . $movie['movie_name'] . '</span>&nbsp&nbsp&nbsp&nbsp&nbsp</p>';
echo '<hr>';
$timestamp = strtotime($showinfo['showInfo_date']);
$day = date('l', $timestamp);
echo '<p>Date:<span style="font-size:1em;color:yellow;">' . $day . ',' . $showinfo['showInfo_date'] . '</span>&nbsp&nbsp&nbsp&nbsp&nbsp';
echo 'Time:<span style="font-size:1em;color:yellow;">' . $showinfo['showInfo_time'] . '</span>&nbsp&nbsp&nbsp&nbsp&nbsp';
echo 'Cinema:<span style="font-size:1em;color:yellow;">' . $cinema['cinema_name'] . '</span></p>';
echo '<p>Seats Selected:<span style="font-size:1em;color:yellow;">' . trim_input(implode(', ', $check_list)) . '</span></p>';
?>
                    </div> 
                </div>


                <div class="row">  
<?php
if ($PaymentMode % 12 == 0 || $PaymentMode % 12.50 == 0 || $PaymentMode % 7.50 == 0) {
    $TotAmnt = $PaymentMode * count($check_list);
    echo '<p style="text-align:left;" >Total Amount: &nbsp<span style="font-size:1.5em;color:yellow;">S$' . $TotAmnt . '</span></p>';
} else {
    unset($_SESSION['PaymentMode']);
    unset($_SESSION['check_list']);
    unset($_SESSION['show_id']);
    unset($_SESSION['CCN']);
    unset($_SESSION['CCE']);
    unset($_SESSION['CVV2']);
    unset($_SESSION['CCName']);
    unset($_SESSION['payment']);
    unset($_SESSION["session_check_list"]);
    unset($_SESSION["buy_ticket"]);
    header("Location:errorPage.php");
}
?>
                </div>
                    <?php
                    if (!isset($_SESSION['user'])) {
                        echo '<button type="button" name="STARTLOGIN" id="STARTLOGIN" class="btn btn-danger btn-md" onClick ="validate()">Login</button>';
                        echo '<div class="row">';
                        echo '<form id="form1" action="login.php" method="POST" style="display:none;">';
                        echo 'Username: <input type="text" name="email">';
                        echo 'Password: <input type="password" name="pwd">';
                        echo '<button type="submit" name="submit" class="btn btn-danger btn-md" style="padding-left:2em;padding-right:2em;float: right;">submit</button>';
                        echo '</form>';
                        echo '<div>';
                    } else {
                        echo '<form action="Payment2.php" method="POST">';
                        echo '<button type="submit" name="Next" id="Next" class="btn btn-danger btn-md" >Next</button>';
                        echo '</form>';
                    }
                    ?>
            </div>
        </div> 

    </body>
</html>
