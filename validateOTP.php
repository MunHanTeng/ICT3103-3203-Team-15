
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


function trim_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//Check if submit button is being pressed a not
if (isset($_POST["submit"])) {
    $okay = True;
    if (empty(trim_input($_POST["email"]))) {
        $emailErr = "Email is required";
        $okay = False;
    }
    if (empty(trim_input($_POST["pwd"]))) {
        $passwordErr = "Password is required";
        $okay = False;
    }
    if ($okay) {
        $username = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['email']));
        $upass = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['pwd']));

        //Delete rows older than 5 minutes
        $stmtDelete = $MySQLiconn->prepare("DELETE FROM failed_logins WHERE User = ? and Timestamp <= (now() - interval 15 minute)");
        $stmtDelete->bind_param('i', $username);
        if (!$stmtDelete->execute()) {
            ?>
            <script>
                alert('Error Login!');
                window.location.href = 'errorPage.php'
            </script>
            <?php
        }
        //Check row count of failed login, if >5, notify user login attempts too many
        $stmtCount = $MySQLiconn->prepare("SELECT COUNT(User) as userFails FROM failed_logins WHERE User = ?");
        $stmtCount->bind_param('s', $username);
        if (!$stmtCount->execute()) {
            ?>
            <script>
                alert('Error Login!');
                window.location.href = 'errorPage.php'
            </script>
            <?php
        }
        $result = $stmtCount->get_result();
        $row = mysqli_fetch_assoc($result);

        $userFails = $row['userFails'];
        if ($userFails >= 5) {
            echo "<script>";
            echo "alert('Incorrect Username or Password, please try again later');";
            echo 'window.location = "index.php";';
            echo '</script>';
        }
        else {
            $accType = 'User';

            $resSelect = $MySQLiconn->prepare("SELECT status, password, user_id FROM user_list WHERE user_email = ? and user_role = ?");
            $resSelect->bind_param('ss', $username, $accType);
            if (!$resSelect->execute()) {
                ?>
                <script>
                    alert('Error Login!');
                    window.location.href = 'errorPage.php'
                </script>
                <?php
            }
            $result = $resSelect->get_result();
            $row = mysqli_fetch_assoc($result);

            if ($row['status'] == 'Not Validated') {
                echo "<script>";
                echo "alert('This account has not yet validatd');";
                echo 'window.location = "index.php";';
                echo '</script>';
            } else if ((password_verify($upass, $row['password']))) {
                $_SESSION['user'] = $row['user_id'];
                //If login successful, delete from fail logins

                $stmtDeleteFL = $MySQLiconn->prepare("DELETE FROM failed_logins WHERE User = ?");
                $stmtDeleteFL->bind_param('i', $username);
                if (!$stmtDeleteFL->execute()) {
                    ?>
                    <script>
                        alert('Error Login!');
                        window.location.href = 'errorPage.php'
                    </script>
                    <?php
                }

                echo "<script type='text/javascript'>";
                echo 'window.location = "validateOTP.php";';
                echo '</script>';
            } else {
                //If login not successful, create new row in fail logins
                $resExists = $MySQLiconn->prepare("SELECT user_id FROM user_list WHERE user_email = ?");
                $resExists->bind_param('s', $username);
                if (!$resExists->execute()) {
                    ?>
                    <script>
                        alert('Error Login!');
                        window.location.href = 'errorPage.php'
                    </script>
                    <?php
                }
                $resExists->store_result();


                if ($resExists->num_rows > 0) {
                    $remainingTry = 5 - ($userFails + 1);

                    //For Insert
                    $stmt3 = $MySQLiconn->prepare("INSERT INTO failed_logins(User,Timestamp) VALUES(?, now())");
                    $stmt3->bind_param('s', $username);

                    if (!$stmt3->execute()) {
                        ?>
                        <script>
                            alert('Error Login!');
                            window.location.href = 'errorPage.php'
                        </script>
                        <?php
                    }

                    $id = $stmt3->insert_id;

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

    $userQuery = $MySQLiconn->prepare("SELECT username, user_email, otpSecretKey FROM user_list WHERE user_id=?");
    $userQuery->bind_param('i', $userid);
    if (!$userQuery->execute()) {
        ?>
        <script>
            alert('Error Login!');
            window.location.href = 'errorPage.php'
        </script>
        <?php
    }

    $userResult = $userQuery->get_result();
    $userRow = mysqli_fetch_array($userResult);
    $otpResult = ($tfa->verifyCode($userRow['otpSecretKey'], trim_input($_POST['otpcode'])) === true ? 'OK' : 'Wrong OTP');
    // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
    if ($otpResult == 'OK') {
        echo '<script language="javascript">';
        echo 'alert("Login successfully");';
        echo 'window.location.href = "index.php";';
        echo '</script>';
        $_SESSION['name'] = $userRow['username'];
        $_SESSION['email'] = $userRow['user_email'];
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
                                    <input type="text" class="form-control" id="otpcode" autocomplete="off" name="otpcode">
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
    <script src="js/validateOTPModal.js" type="text/javascript"></script> 
</html>
