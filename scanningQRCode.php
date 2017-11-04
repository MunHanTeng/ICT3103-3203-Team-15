<?php
    require_once 'loader.php';
    Loader::register('lib','RobThree\\Auth');
    use \RobThree\Auth\TwoFactorAuth;
    $tfa = new TwoFactorAuth('ICT3203');
    include_once 'dbconnect.php';
    include 'header.inc';
?>

<!doctype html>
<html>
    <head>
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
    </head>
    
    <body>
        <h1>Please enter the OTP shown in the Google authenticator</h1>
        <form id="msform" method="POST" action="OTPPage.php">
            <div class="form-group">
                <label class="control-label col-md-1" for="otpcode"><p>OTP Code:</p></label>
                <div class="col-md-6">
                    <input type="text" class="form-control" id="otpcode" name="otpcode">
                </div>
            </div>
            <button type="submit" name="submit" class="btn action-button">Submit</button>
        </form>
    </body>

    <!-- jQuery --> 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</html>
