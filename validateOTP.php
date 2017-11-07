
<?php
include_once 'dbconnect.php';
session_start();
require_once 'loader.php';
Loader::register('lib', 'RobThree\\Auth');

use \RobThree\Auth\TwoFactorAuth;

$tfa = new TwoFactorAuth('ICT3203');

// LOGIN
// Declare some variable for error message
$emailErr = null;
$passwordErr = null;
//echo '<script type="text/javascript">alert ("' . $_POST['fname'] . '")</script>';
//Check if submit button is being pressed a not
if (isset($_POST["submit"])) {
    $okay = True;
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $okay = False;
    }
    if (empty($_POST["pwd"])) {
        $passwordErr = "Password is required";
        $okay = False;
    }
    if ($okay) {
        //echo '<script type="text/javascript">alert("hello!");</script>';
        $username = mysqli_real_escape_string($MySQLiconn, $_POST['email']);
        $upass = mysqli_real_escape_string($MySQLiconn, $_POST['pwd']);

        //Delete rows older than 5 minutes
        $res = mysqli_query($MySQLiconn, "DELETE FROM failed_logins WHERE User='$username' and Timestamp<= (now() - interval 15 minute)");

        //Check row count of failed login, if >5, notify user login attempts too many
        $res = mysqli_query($MySQLiconn, "SELECT COUNT(User) as userFails FROM failed_logins WHERE User='$username'");
        $row = mysqli_fetch_array($res);
        $userFails = $row['userFails'];
        if ($userFails >= 5) {
            echo "<script>";
            echo "alert('Incorrect Username or Password, please try again later');";
            echo 'window.location = "index.php";';
            echo '</script>';
        }
        //$data['userFails']
        else {
            $res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE user_email='$username' and user_role='User'");
            $row = mysqli_fetch_array($res);
            if ($row['status'] == 'Not Validated') {
                echo "<script>";
                echo "alert('This account has not yet validatd');";
                echo 'window.location = "index.php";';
                echo '</script>';
            } else if ((password_verify($upass, $row['password']))) {
                $_SESSION['user'] = $row['user_id'];
                //If login successful, delete from fail logins
                $res = mysqli_query($MySQLiconn, "DELETE FROM failed_logins WHERE User='$username'");

                echo "<script type='text/javascript'>";
                echo 'window.location = "validateOTP.php";';
                echo '</script>';
            } else {
                //If login not successful, create new row in fail logins
                $existsQuery = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE user_email='$username'");
                if (mysqli_num_rows($existsQuery) > 0) {
                    $remainingTry = 5 - ($userFails + 1);
                    $res = mysqli_query($MySQLiconn, "INSERT INTO failed_logins(User,Timestamp) VALUES('$username',now())");
                    echo "<script>";
                    echo "alert('Incorrect Username or Password, $remainingTry attempts left');";
                    echo 'window.location = "index.php";';
                    echo '</script>';
                } else {
                    $passwordErr = "Incorrect Username/Password or Account has not been activated yet";
                    echo "<script>";
                    echo "alert('Incorrect Username or Password');";
                    echo 'window.location = "index.php";';
                    echo '</script>';
                }
            }
        }
    }
}
// OTP
if (isset($_POST['validate'])) {
    $userid = $_SESSION['user'];
    $res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE user_id='$userid'");
    $row = mysqli_fetch_array($res);
    $result = ($tfa->verifyCode($row['otpSecretKey'], $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
    // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
    if ($result == 'OK') {
        echo '<script language="javascript">';
        echo 'alert("Login successfully");';
        echo 'window.location.href = "index.php";';
        echo '</script>';
        $_SESSION['name'] = $row['username'];
        $_SESSION['email'] = $row['user_email'];
    } else {
        echo '<script language="javascript">';
        echo 'alert("OTP is incorrect please try again");';
        echo '</script>';
    }
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

    </head>
    <body>
<?php
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
                            <button type="submit" name="validate" class="btn btn-primary">Submit</button>
                        </form>
                        <br />
                    </div>
                </div>
            </div>
        </div>


    </body>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#myModal").modal('show');
        });
    </script>
</html>
