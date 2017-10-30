<!doctype html>
<html>
<head>
    <title>Golden Village</title>
    <link href="css/ticketcollection.css" rel="stylesheet">
</head>
    <?php
        require_once 'loader.php';
        Loader::register('lib','RobThree\\Auth');
        use \RobThree\Auth\TwoFactorAuth;
        $tfa = new TwoFactorAuth('ICT3203');
        include_once 'dbconnect.php';
        session_start();
    ?>

    <?php
        $result = ($tfa->verifyCode($_SESSION['QRCODE'], $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
        $qrValue = mysqli_real_escape_string($MySQLiconn, $_SESSION['QRCODE']);
        $res = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection WHERE qrValue='$qrValue'");

        // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
        $userid = $_SESSION['user'];
        $OTPCode = $tfa->getCode($_SESSION['QRCODE']);
        if ($result == 'OK')
        {
            $res2 = mysqli_query($MySQLiconn, "SELECT * FROM otptable WHERE OTP_Code='$OTPCode'");
            if (mysqli_num_rows($res2) == 1) 
            {
                echo '<center><img src="images/unsucess.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                echo '<center><h1 style="color:yellow;">Tickets has already redempted</h1></center>';
            }
            else 
            {
                echo '<center><img src="images/successbutton.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                //Save to otp table
                $sql_otptable = $MySQLiconn->query("INSERT INTO otptable(OTP_Code, OTP_UserID) VALUES ('$OTPCode', '$userid')");
                mysqli_query($MySQLiconn, $sql_otptable);
                
                $res = mysqli_query($MySQLiconn, "UPDATE ticketcollection SET ticket_collected=1 WHERE qrValue='$qrValue'");
                $row = mysqli_affected_rows($MySQLiconn);
                if ($row == 1) 
                {
                    echo '<center><h1 style="color:yellow;">Tickets redeemed successfully</h1></center>';
                    echo '<center><h3 style="color: white"><u> Ticket Collection Details </u></h3></center>';
                    echo '<center>
                        <div class="container-fluid" style="color: white; padding-bottom: 10px">
                            <div class="row">';
    ?>
                        <?php
                            $user_result = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection AS TC INNER JOIN user_list AS UL ON TC.user_id = UL.user_id WHERE TC.qrValue = '$qrValue' ");
                            $userResult = mysqli_fetch_assoc($user_result);

                            $ticket_result = mysqli_query($MySQLiconn, "SELECT * FROM booking AS B INNER JOIN showinfo AS SI ON B.showInfo_id = SI.showInfo_id INNER JOIN movie AS M ON B.movie_id = M.movie_id WHERE collection_id = " . $userResult['collection_id'] . "");
                            // $ticketResult = mysqli_fetch_assoc($ticket_result);
                            $seat = array();
                            while ($row = mysqli_fetch_array($ticket_result)) 
                            {
                                $seat[] = $row['seat_no'];
                                $movie = $row['movie_name'];
                                $date = $row['showInfo_date'];
                                $time = $row['showInfo_time'];
                            }
                            echo '<p><h4>Movie: ' . $movie . '</h4></p>';
                            echo '<p><h4>Booked Date: ' . $date . '</h4></p>';
                            echo '<p><h4>Booked Time: ' . $time . '</h4></p>';
                            echo '<p><h4>Booked Seat(s): ' . implode(',', $seat) . '</h4></p>';
                    echo '</div>';
                echo '</div>';
            echo '</center>';
            }
        }
    }
    else 
    {
        echo '<center><img src="images/unsucess.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
        echo '<center><h1 style="color:yellow;">Wrong OTP</h1></center>';
    }
    
?>           
</body>
</html>
