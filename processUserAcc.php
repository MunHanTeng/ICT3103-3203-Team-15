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
            $id = $_SESSION['dummy_id'];
            $user_result = mysqli_query($MySQLiconn, "SELECT * FROM dummy_table WHERE dummy_id='$id'");
            $userResult = mysqli_fetch_assoc($user_result); 
            
            $uname = $userResult['dummy_username'];
            $uemail = $userResult['dummy_email'];
            $upass = $userResult['dummy_pass'];
            $urole = $userResult['dummy_user_role'];
            $uphone = $userResult['dummy_phone'];
            $unric = $userResult['dummy_NRIC'];
            $usecretKey = $userResult['dummy_otpSecret'];
        
            $result = ($tfa->verifyCode($usecretKey, $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
            //echo $qrValue;
            //$res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE otpSecretKey='$qrValue'");

            // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
            //$userid = $_SESSION['user'];
            $OTPCode = $tfa->getCode($usecretKey);
            if ($result == 'OK')
            {
                $sql_adduser = $MySQLiconn->query("INSERT INTO user_list( username, user_email, password, user_role, phone, user_nric, status, otpSecretKey) VALUES ('$uname','$uemail','$upass','$urole', '$uphone', '$unric', 'Validated', '$usecretKey')");
                mysqli_query($MySQLiconn, $sql_adduser);
            
                echo '<center><img src="images/successbutton.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                echo '<center><h1 style="color:yellow;">User Account Validated Successfully</h1></center>';
                        
                $sql_deletedummy = $MySQLiconn->query("delete from dummy_table where dummy_id = '$id'");
                mysqli_query($MySQLiconn, $sql_deletedummy);
            }
        else 
        {
            echo '<center><img src="images/unsucess.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
            echo '<center><h1 style="color:yellow;">Wrong OTP</h1></center>';
        }
    
    ?>           
</body>
</html>
