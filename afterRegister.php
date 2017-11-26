<?php
    require_once 'loader.php';
    Loader::register('lib','RobThree\\Auth');
    use \RobThree\Auth\TwoFactorAuth;
    $tfa = new TwoFactorAuth('ICT3203');
    include_once 'dbconnect.php';
        
?>


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
    
    <body>
    <?php  include 'header.inc'; ?>
        <form id="msform" method="POST" action="processUserAcc.php">
        <!-- progressbar -->
        <ul id="progressbar">
            <li>Register User</li>
            <li class="active">Scan QR Code</li>
            <li>Enter OTP</li>
        </ul>
        
        <!-- fieldsets Page 1 -->
        <fieldset>
            <h2 class="fs-title">Scan the QR Code</h2>
            <h3 class="fs-subtitle">This is the first step</h3>
            <?php
            $id = $_SESSION['dummy_id'];
            $stmt = $MySQLiconn->prepare("SELECT dummy_otpSecret FROM dummy_table WHERE dummy_id = ?");
            $stmt->bind_param('s', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $userResult = mysqli_fetch_assoc($result);

            
            if ($stmt->execute())
            {
                $id = $stmt->insert_id;
                
                echo 'Please scan the following QR code and click next<br><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $tfa->getQRText('Movie%20Account%20Authentication', $userResult['dummy_otpSecret']) . '"><br>';
            }
            else 
            {
                unset($_SESSION['dummy_id']);
            ?>
                <script>
                    alert('Error');
                    window.location.href='errorPage.php'
                </script>
            <?php 
            }
            ?>
                
            <input type="button" name="next" class="next action-button" value="Next" />
        </fieldset>
       
        <!-- fieldsets Page 2 -->
        <fieldset>
            <h2 class="fs-title">Scan QR Code</h2>
            <h3 class="fs-subtitle">This is the second step</h3>
            <br>Please enter the OTP Code generated from your Google Authenticator and submit to verify
            <br /><br />
            <input type="text" style="width: 100%" class="form-control" id="otpcode" name="otpcode">
            <br />
            <button type="submit" name="submit" class="btn action-button">Submit</button>
            <input type="button" name="previous" class="previous action-button" value="Previous" />
        </fieldset>
        
        <!-- fieldsets Page 3 -->
        <fieldset>    
            <h2 class="fs-title">Enter OTP</h2>
            <h3 class="fs-subtitle">This is the second step</h3>
            <br>Please enter the OTP Code generated from your Google Authenticator and submit to verify
            <br /><br />
            <input type="text" style="width: 100%" class="form-control" id="otpcode" name="otpcode">
            <br />
            <button type="submit" name="submit" class="btn action-button">Submit</button>
            <input type="button" name="previous" class="previous action-button" value="Previous" />
        </fieldset>
    </form>

<!-- jQuery --> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<!-- jQuery easing plugin --> 
<script src="js/jquery.easing.min.js" type="text/javascript"></script> 
<script src="js/otpScript.js"></script>
</body>
</html>
