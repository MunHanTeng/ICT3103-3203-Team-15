<?php
include_once 'dbconnect.php';
session_start();

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
        $username = mysqli_real_escape_string($MySQLiconn, $_POST['email']);
        $upass = mysqli_real_escape_string($MySQLiconn, $_POST['pwd']);
        $res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE user_email='$username' and user_role='User'");
        $row = mysqli_fetch_array($res);
        if ($row['password'] == md5($upass)) {
            $_SESSION['user'] = $row['user_id'];
            $_SESSION['name'] = $row['username'];
            $_SESSION['email'] = $row['user_email'];
            header("Location: index.php");
        } else {
            $passwordErr = "Incorrect Username/Password or Account has not been activated yet";
            ?>
            <script>alert('Incorrect Password or Username');</script>
 
            <?php
        }
    }
}
 
//header("Location: index.php");
?>