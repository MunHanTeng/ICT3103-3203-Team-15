<?php
session_start();

include_once 'dbconnect.php';
$nameErr = $emailErr = $passwordErr = $confirmPwdErr = $phoneNoErr = "";
if (isset($_POST['submit'])) {
    $okay = True;
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $okay = False;
    }
    $email = $_POST["email"];
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $okay = False;
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
        $okay = False;
    }
    if (empty($_POST["pwd"])) {
        $passwordErr = "Password is required";
        $okay = False;
    }
    else if (strlen($_POST["pwd"]) < 8) {
        $passwordErr = "Password must be longer than 8 characters";
        $okay = False;
    }
    if (empty($_POST["confirmpwd"])) {
        $confirmPwdErr = "Confirm Password is required";
        $okay = False;
    }
    else if ($_POST["confirmpwd"] != $_POST["pwd"]) {
        $confirmPwdErr = "Passwords must match";
        $okay = False;
    }
    if (empty($_POST["phone"])) {
        $phoneNoErr = "Phone is required";
        $okay = False;
    }
    else if (!is_numeric($_POST["phone"])) {
        $phoneNoErr = "Only numbers are allowed";
        $okay = False;
    } else if (strlen($_POST["phone"]) != 8) {
        $phoneNoErr = "The number need to be 8 digits";
        $okay = False;
    }
    
    $email = mysqli_real_escape_string($MySQLiconn, $_POST['email']);
    $result = mysqli_query($MySQLiconn, "SELECT COUNT(*) As RegisteredEmail FROM user_list where user_email='".$email."'");
    $row = mysqli_fetch_array($result);
    if ($row['RegisteredEmail' != 0]) {
        $emailErr = "Email Already Registered";
        $okay = False;
    }
    
    //if (mysqli_query("SELECT COUNT(* "))
    if ($okay) {
        $uname = mysqli_real_escape_string($MySQLiconn, $_POST['name']); 
        $uphone = mysqli_real_escape_string($MySQLiconn, $_POST['phone']);      
        $upass = md5(mysqli_real_escape_string($MySQLiconn, $_POST['pwd']));
        $hash = md5(rand(0,1000));
        if (mysqli_query($MySQLiconn, "INSERT INTO user_list(username,user_email,password,user_role,phone) VALUES('$uname','$email','$upass','User', '$uphone')")) {
            ?>
            <script>alert('Successfully registered!');
                window.location.href='index.php'</script>
            <?php
           // header("Location: index.php");
        } else {
            ?>
            <script>alert('error while registering you...');</script>
            <?php
        }
    }
}
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />

    </head>
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <?php include "header.inc" ?>
            <div class="container">
                <div class="row">
                    <h1>Register</h1>
                    <form class="form-horizontal" role ="form" method = "post" action="">
                        <div class="form-group">
                            <label class="control-label col-md-3" for="firstname"><p>Name:</p></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name ="name" >
                                <span class="text-danger"><?php echo $nameErr; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="email" name="email"><p>Email:</p></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="email" required>
                                <span class="text-danger"><?php echo $emailErr; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="pwd" name="pwd"><p>Password:</p></label>
                            <div class="col-md-9">          
                                <input type="password" class="form-control" name="pwd" required>
                                <span class="text-danger"><?php echo $passwordErr; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="pwd"><p>Password Confirm:</p></label>
                            <div class="col-md-9">          
                                <input type="password" class="form-control" name="confirmpwd">
                                <span class="text-danger"><?php echo $confirmPwdErr; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" for="phone"><p>Phone Number:</p></label>
                            <div class="col-md-9">          
                                <input type="number" class="form-control" name="phone" maxlength="8">
                                <span class="text-danger"><?php echo $phoneNoErr; ?></span>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php include "footer.inc" ?>
    </body>
</html>