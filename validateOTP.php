<!doctype html>
<html>
<head>
    <title>Validate OTP</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
    <?php
        require_once 'loader.php';
        Loader::register('lib','RobThree\\Auth');
        use \RobThree\Auth\TwoFactorAuth;
        $tfa = new TwoFactorAuth('ICT3203');
        include_once 'dbconnect.php';
        include 'header.inc';
    ?>

    <h1>Please enter the OTP shown in the Google authenticator</h1>
        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
            <div class="form-group">
                <label class="control-label col-md-1" for="otpcode"><p>OTP Code:</p></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="otpcode" name="otpcode">
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </li>


<?php
if(isset($_POST['submit'])) {
    $userid = $_SESSION['user'];
    $res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE user_id='$userid'");
    $row = mysqli_fetch_array($res);
    $_SESSION['QRCODE'] = $row['qrValue'];
    $result = ($tfa->verifyCode($_SESSION['QRCODE'], $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
    // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
    if ($result == 'OK')
    {
        echo '<script language="javascript">';
        echo 'alert("OTP is coreect");';
        echo 'window.location.href = "index.php";';
        echo '</script>';
    }
    
    else 
    {
        echo '<script language="javascript">';
        echo 'alert("OTP is incorrect please try again");';
        echo '</script>';
    }
}
?>
</body>
</html>
