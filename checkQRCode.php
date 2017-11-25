<?php
    require_once 'loader.php';
    Loader::register('lib','RobThree\\Auth');
    use \RobThree\Auth\TwoFactorAuth;
    $tfa = new TwoFactorAuth('ICT3203');
    session_start();
    include_once 'dbconnect.php';
   
    if (!isset($_GET['qrCode'])) 
    {
        header("Location: index.php");
    }

    function trim_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['submit']))
    {
        $qrValue = mysqli_real_escape_string($MySQLiconn, trim_input($_GET['qrCode']));
        
        //Count num of column where qrvalue = to db qr
            $stmt = $MySQLiconn->prepare("SELECT booking_time FROM ticketcollection WHERE qrValue = ?");
            $stmt->bind_param('s', $qrValue);
		if (!$stmt->execute())
		{
	?>
		   <script>
                        alert('Error Displaying Sucess Information!');
                        window.location.href='errorPage.php'
                    </script>
	<?php
		}
            $stmt->store_result();

        
        if ($stmt->num_rows == 1) 
        {
           //GET OTP Secret
            $stmt2 = $MySQLiconn->prepare("SELECT otpSecretKey FROM ticketcollection AS TC INNER JOIN user_list AS UL ON TC.user_id = UL.user_id WHERE TC.qrValue = ?");
            $stmt2->bind_param('s', $qrValue);
		if (!$stmt2->execute())
		{
	?>
		   <script>
                        alert('Error Displaying Sucess Information!');
                        window.location.href='errorPage.php'
                    </script>
	<?php
		}
            $result2 = $stmt2->get_result();
            $userResult = mysqli_fetch_assoc($result2);
        }
        
        //Check OTP Code
         $result = ($tfa->verifyCode($userResult['otpSecretKey'], trim_input($_POST['otpcode'])) === true ? 'OK' : 'Wrong OTP');
        // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
        if ($result == 'OK')
        {
            $_SESSION['OTPSecret'] = $qrValue;
        }
        else 
        {
            $_SESSION['OTPSecret'] = '';
        }
        
        echo "<script>";
        echo 'window.location = "OTPPage.php";';
        echo '</script>';
                        
    }    
?>
<!doctype html>
<html>
<head>
    <title>Validate OTP</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/validateOTPModal.js" type="text/javascript"></script> 
</head>
<body>

<?php  include 'header.inc'; ?>
<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color: white">&times;</button>
                <h4 class="modal-title">Enter OTP</h4>
            </div>
            <div class="modal-body">
                <h3>Enter the OTP shown in the Google authenticator</h3>
                <form action="" method="POST">
                    <div class="form-group">
                        <label class="control-label col-md-2" for="otpcode"><p>OTP Code:</p></label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="otpcode" autocomplete="off" name="otpcode">
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
                <br />
            </div>
        </div>
    </div>
</div>

    
</body>
</html>






