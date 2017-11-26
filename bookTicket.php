<html>
    <head>
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
    </head>
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/bookTicket.js" type="text/javascript"></script> 

        <?php
        include 'header.inc';
	include_once __DIR__ .'../csrfp/libs/csrf/csrfprotector.php';
	csrfProtector::init();

        function trim_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        //After 15 Min delete the record
        $stmtDelete = $MySQLiconn->prepare("DELETE FROM locked_seat WHERE timestamp <= (now() - interval 15 minute)");
        if (!$stmtDelete->execute()) {
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

        if (isset($_POST['submit'])) {
            $arraySeat = $_POST["check_list"];
            $_SESSION["session_check_list"] = $arraySeat;
            $_SESSION["buy_ticket"] = trim_input($_POST["BuyTicket"]);

            //Make Sure seat start with letter and follow by alphabet
            $checkseats = true;
            for ($i = 0; $i < sizeof($arraySeat); $i++) {
                if (!preg_match('/^[ABCDE](\d{1}|10)$/', $arraySeat[$i])) {
                    $checkseats = false;
                    break;
                }
            }

            if ($checkseats == true) {

                //Make sure seat not booked
                foreach ($arraySeat as $seat) {
                    //Check Booking
                    $bookQuery2 = $MySQLiconn->prepare("SELECT booking_id FROM booking WHERE showInfo_id = ? and movie_id = ? and seat_no = ?");
                    $bookQuery2->bind_param('sss', $_COOKIE['showinfoID'], trim_input($_POST['movie_id']), $seat);
                    if (!$bookQuery2->execute()) {
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
                    $bookQuery2->store_result();

                    if ($bookQuery2->num_rows != 0) {
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
                        exit;
                    } else {
                        header("Location:proccessBookTicket.php");
                    }
                }
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
        }

        if (!isset($_SESSION['name'])) {
            echo '<script language="javascript">';
            echo 'alert("Please Login in order to be able to buy ticket sucessfully"); location.href="index.php"';
            echo '</script>';
        } else {
            include_once 'dbconnect.php';

            // RETRIEVE SHOW INFO
            $showInfoQuery = $MySQLiconn->prepare("SELECT showInfo_date, showInfo_time, movie_id FROM showinfo WHERE showInfo_id = ?");
            $showInfoQuery->bind_param('i', $_COOKIE['showinfoID']);
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
            $movieQuery = $MySQLiconn->prepare("SELECT movie_name, movie_id, movie_poster, movie_websiteLink FROM movie WHERE movie_id = ?");
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
        }
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
                <div class="row">
                    <div class="container-fluid" style="background-color:#303030;">
                        <?php
                        $timestamp = strtotime($showinfo['showInfo_date']);
                        $day = date('l', $timestamp);
                        echo "<center><h5>You have selected movie : </h5><h4>" . $movie['movie_name'] . "</h4>";
                        echo "<h5> on </h5><h4>" . $day . "  " . $showinfo['showInfo_date'] . "  " . $showinfo['showInfo_time'] . "</h4></center>";
                        ?>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 text-center">
                    <div class="container-fluid" style="width:475px;height:235px">
                        <!--Create Button-->
                        <form method="POST">
                            <div class="btn-toolbar" name="SeatSelection" data-toggle="buttons" name="SelectedSeats" style="padding:10px">   
                                <?PHP
                                $dic = array(0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E');
                                $var = 0;
                                $q = 1;
                                $bookQuery = $MySQLiconn->prepare("SELECT seat_no FROM booking WHERE showInfo_id = ?");
                                $bookQuery->bind_param('i', $_COOKIE['showinfoID']);
                                if (!$bookQuery->execute()) {
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
                                $bookResult = $bookQuery->get_result();

                                $bookedseats = array();
                                if (count($bookResult) > 0) {

                                    while ($seatNo = mysqli_fetch_assoc($bookResult)) {
                                        array_push($bookedseats, $seatNo['seat_no']);
                                    }
                                }

                                echo '<center><h5>screen</h5></center>';
                                $checked = 0;
                                for ($row = 0; $row < 5; $row++) {
                                    echo '<h5>' . $dic[$row] . '</h5>';
                                    for ($col = 0; $col < 10; $col++) {
                                        if (in_array('' . $dic[$row] . ($col + 1) . '', $bookedseats)) {
                                            echo '<label class="btn btn-primary btn-lg" disabled="disabled">';
                                            echo '<input type="checkbox"  name="check_list[]" id="check" value="' . $dic[$row] . ($col + 1) . '">';
                                            echo '</label>';
                                        } else {
                                            echo '<label class="btn btn-primary btn-lg">';
                                            echo '<input type="checkbox"  name="check_list[]" onchange = "updateAmount(this);" value="' . $dic[$row] . ($col + 1) . '">';
                                            echo '</label>';
                                        }
                                    }
                                    echo '<br>';
                                }
                                ?>
                            </div>
                    </div>

                    <div id="SeatSelection">
                        <div class="container-fluid" style="background-color:#303030;padding-bottom: 10px">
                            <center> <h3 style="display:inline;"> You have selected </h3> <h3 id="demo" name="demo"></h3> <h3> seats!</h3></center>

                            <hr>

                            <Label style="color:white;">Select Ticket Price: </Label>

                            <select name="BuyTicket" id="BuyTicket" onchange="TicketType()" class="form-control">
                                <option value="" selected disabled hidden>Please Select your Payment Mode</option>
                                <option value="Standard Price - $12.50">Standard Price - $12.50</option>
                                <option value="Visa Checkout - $12.00">Visa Checkout- $12.00</option>
                                <option value="DBS/POSB Credit & Debit - $7.50">DBS/POSB Credit & Debit - $7.50</option>
                            </select>

                            <hr>

                            <div id="StartBooking" style="overflow-x:auto;">
                                <table style="width: 100%">
                                    <tr>
                                        <td><h5 style="margin-left: 5px" id="Pay">Ticket Type</h5></td>
                                        <td><h5 id="Pay">Ticket Price</h5></td>
                                        <td><h5 id="Pay">Qty</h5></td>
                                        <td><h5 id="Pay">Total Amount</h5></td>
                                    </tr>
                                    <tr>
                                        <td><h5 style="margin-left: 5px" id="TicketType" class="popup"></h5></td>
                                        <td><h5 id="TicketPrice" class="popup">Ticket Price</h5></td>
                                        <td><h5 id="Qty" class="popup">Qty</h5></td>
                                        <td><h5 id="TotalAmount" class="popup">Total Amount</h5></td>
                                    </tr>
                                </table>    
                                <input type="hidden" name="show_id" value="<?php echo $_COOKIE['showinfoID']; ?>">
                                <input type="hidden" name="movie_id" id="movie_id" value="<?php echo $movie['movie_id']; ?>">
                                <br>
                                <button type="submit" name="submit" class="btn btn-primary">Start Payment</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </body>
</html>