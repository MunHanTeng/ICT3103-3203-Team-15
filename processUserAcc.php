<!doctype html>
<html>
<head>
    <title>Golden Village</title>
    <link href="css/ticketcollection.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</head>
    <?php
        require_once 'loader.php';
        Loader::register('lib','RobThree\\Auth');
        use \RobThree\Auth\TwoFactorAuth;
        $tfa = new TwoFactorAuth('ICT3203');
        include_once 'dbconnect.php';
        include 'header.inc';
    ?>

    <?php
        $result = ($tfa->verifyCode($_SESSION['QRCODE'], $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
        $qrValue = mysqli_real_escape_string($MySQLiconn, $_SESSION['QRCODE']);
        $res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE qrValue='$qrValue'");

        // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
        //$userid = $_SESSION['user'];
        $OTPCode = $tfa->getCode($_SESSION['QRCODE']);
        if ($result == 'OK')
        {
            $res2 = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE qrValue='$qrValue' and status = 'Validated'");
            if (mysqli_num_rows($res2) == 1) 
            {
                echo '<center><img src="images/unsucess.png" align="middle" alt="unSucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                echo '<center><h1 style="color:yellow;">This QR Code have already been used before</h1></center>';
            }
            else 
            {
                echo '<center><img src="images/successbutton.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                //Save to otp table
                $res = mysqli_query($MySQLiconn, "UPDATE user_list SET status='Validated' WHERE qrValue='$qrValue'");
                $row = mysqli_affected_rows($MySQLiconn);
                if ($row == 1) 
                {
                    echo '<center><h1 style="color:yellow;">User Account Validated Successfully</h1></center>';
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
