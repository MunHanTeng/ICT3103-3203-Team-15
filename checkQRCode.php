<?php
session_start();
include_once 'dbconnect.php';
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />

    </head>
    <body>
        <?php
        include 'header.inc';
        ?>


        <?php
        if (!isset($_GET['qrCode'])) {
            header("Location: index.php");
        }

        $qrValue = mysqli_real_escape_string($MySQLiconn, $_GET['qrCode']);

        $res = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection WHERE qrValue='$qrValue'");
        if (mysqli_num_rows($res) == 1) {
            $res = mysqli_query($MySQLiconn, "UPDATE ticketcollection SET ticket_collected=1 WHERE qrValue='$qrValue'");
            $row = mysqli_affected_rows($MySQLiconn);
            if ($row == 1) {
                ?>

            <center><h1 style="color:yellow;">Tickets redeemed successfully</h1></center>
            <h3><u> Ticket Collection Details </u></h3>
            <div class="container-fluid" style="background-color:#303030 ; padding-bottom: 10px">
                <div class="row">
                    <?php
                    $user_result = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection AS TC INNER JOIN user_list AS UL ON TC.user_id = UL.user_id WHERE TC.qrValue = '$qrValue' ");
                    $userResult = mysqli_fetch_assoc($user_result);

                    $ticket_result = mysqli_query($MySQLiconn, "SELECT * FROM booking AS B INNER JOIN showinfo AS SI ON B.showInfo_id = SI.showInfo_id INNER JOIN movie AS M ON B.movie_id = M.movie_id WHERE collection_id = " . $userResult['collection_id'] . "");
                    // $ticketResult = mysqli_fetch_assoc($ticket_result);
                    $seat = array();
                    while ($row = mysqli_fetch_array($ticket_result)) {
                        $seat[] = $row['seat_no'];
                        $movie = $row['movie_name'];
                        $date = $row['showInfo_date'];
                        $time = $row['showInfo_time'];
                    }
                    echo '<p><h4>Movie: ' . $movie . '</h4></p>';
                    echo '<p><h4>Booked Date: ' . $date . '</h4></p>';
                    echo '<p><h4>Booked Time: ' . $time . '</h4></p>';
                    echo '<p><h4>Booked Seat(s): ' . implode(',', $seat) . '</h4></p>';
                } else {
                    ?>
                    <center><h1 style="color:yellow;">Error redeeming ticket</h1></center>

                    <?php
                }
            }
            ?>
            <!--//  echo 'QR Code is ' . $qrValue;-->
        </div>
    </div>



    <?php include 'footer.inc'; ?>
</body>
</html>
