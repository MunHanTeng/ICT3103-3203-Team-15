<?php

include_once 'dbconnect.php';
session_start();
if (isset($_SESSION['user']) != "") {
    header("Location: index.php");
}
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
            if ($row['status'] == 'Not Validated')
            {
                echo "<script>";
                echo "alert('This account has not yet validatd');";
                echo 'window.location = "index.php";';
                echo '</script>';
            }
            else if ((password_verify($upass, $row['password']))) 
            {
                $_SESSION['user'] = $row['user_id'];
//                $_SESSION['name'] = $row['username'];
//                $_SESSION['email'] = $row['user_email'];
                //If login successful, delete from fail logins
                $res = mysqli_query($MySQLiconn, "DELETE FROM failed_logins WHERE User='$username'");
                
                echo "<script type='text/javascript'>";
                echo "alert('Login successful');";
                echo 'window.location = "validateOTP.php";';
                $_SESSION["SUCESS"] = "YES";
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
?>