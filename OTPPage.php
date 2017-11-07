<!doctype html>
<html>
    <head>
        <title>Golden Village</title>
        <link href="css/ticketcollection.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    <?php
    require_once 'loader.php';
    Loader::register('lib', 'RobThree\\Auth');

    use \RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('ICT3203');
    include_once 'dbconnect.php';
    include 'header.inc';
    ?>
    <body>
        <?php
        if ($_SESSION['OTPSecret'] != '') {
            
            $qrValue = $_SESSION['OTPSecret'];
            $res1 = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection WHERE qrValue='$qrValue' AND ticket_collected = 0");
            
            if (mysqli_num_rows($res1) == 1) {
                $res = mysqli_query($MySQLiconn, "UPDATE ticketcollection SET ticket_collected=1, time_collected = now() WHERE qrValue='$qrValue'");
                $row = mysqli_affected_rows($MySQLiconn);
                if ($row == 1) {
                    echo '<center><img src="images/successbutton.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                    echo '<center><h1 style="color:yellow;">Tickets redeemed successfully</h1></center>';
                    echo '<center><h3 style="color: white"><u> Ticket Collection Details </u></h3></center>';
                    echo '<center>';
                    echo '<div class="container-fluid" style="color: white; padding-bottom: 10px">';
                    echo '<div class="row">';
                    ?>

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
                    echo '</div>';
                    echo '</div>';
                    echo '</center>';
                }
                require 'email/PHPMailerAutoload.php';

                $mail = new PHPMailer;
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = '3103.3203.team15@gmail.com';       // SMTP username
                $mail->Password = 'te@m15ssd';                        // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                $mail->setFrom('from@example.com', 'Golden Village');
                $mail->addAddress('' . $userResult['user_email'] . '');      // Add a recipient

                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = '' . $movie . ' tickets collected successfully!';
                $mail->Body = '<p>Dear ' . $userResult['username'] . ',</p>
                                    <p>Please be informed that you have redeemed the tickets successfully at the cinema gantry.</p>
                                    <p><b><u>Movie Ticket Details</u></b>
                                    <p>Booked Date: ' . $date . '</p> 
                                    <p>Booked Time: ' . $time . '</p>
                                    <p>Your seat(s) is/are ' . implode(',', $seat) . '</p>';
                if (!$mail->send()) {
                    echo 'Movie tickets details could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } 
                else{
                    echo '<script language="javascript">';
                    echo 'alert("We will be sending an email to notify tickets collected successfully!");';
                    echo '</script>';
                }
                
            } else {
                echo '<center><img src="images/unsucess.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                echo '<center><h1 style="color:yellow;">Ticket has already been collected</h1></center>';
            }
        } else {
            echo '<center><img src="images/unsucess.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
            echo '<center><h1 style="color:yellow;">Wrong OTP</h1></center>';
        }
        ?>     
    </body>
</html>
