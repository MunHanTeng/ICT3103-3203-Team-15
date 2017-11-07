<!doctype html>
<html>
<head>
    <title>Validate OTP</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#myModal").modal('show');
        });
    </script>
</head>
<body>
    <?php
        require_once 'loader.php';
        Loader::register('lib','RobThree\\Auth');
        use \RobThree\Auth\TwoFactorAuth;
        $tfa = new TwoFactorAuth('ICT3203');
        include_once 'dbconnect.php';
        include 'header.inc';
    ?>


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
                            <input type="text" class="form-control" id="otpcode" name="otpcode">
                        </div>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
                <br />
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['submit'])) {
    $userid = $_SESSION['user'];
    $res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE user_id='$userid'");
    $row = mysqli_fetch_array($res);
    $_SESSION['name'] = $row['username'];
    $_SESSION['email'] = $row['user_email'];
    $result = ($tfa->verifyCode($row['otpSecretKey'], $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
    // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
    if ($result == 'OK')
    {
        echo '<script language="javascript">';
        echo 'alert("Login successfully");';
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
