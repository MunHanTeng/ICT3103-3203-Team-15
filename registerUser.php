<?php
    require_once 'loader.php';
    Loader::register('lib','RobThree\\Auth');
    use \RobThree\Auth\TwoFactorAuth;
    $tfa = new TwoFactorAuth('ICT3203');
    include_once 'dbconnect.php';
    require('Base2n.php');
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<?php
    
    function trim_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    
    $nameErr = $emailErr = $passwordErr = $confirmPwdErr = $phoneNoErr = $NRIC = "";
    $regularExpNRIC = "/^[STFG]\d{7}[A-Z]$/";
    if (isset($_POST['submit'])) 
    {
        $okay = True;
        if (empty(trim_input($_POST["name"]))) 
        {
            $nameErr = "Name is required";
            $okay = False;
        }
        else if(!preg_match('/^[a-zA-Z0-9]+$/', trim_input($_POST["name"])))
        {
            $nameErr = "Only accept alphanumeric username!";
            $okay = False;
        }
        
        $email = trim_input($_POST["email"]);
    
        if (empty(trim_input($_POST["email"]))) 
        {
            $emailErr = "Email is required";
            $okay = False;
        }
    
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            $emailErr = "Invalid email format";
            $okay = False;
        }
        
        if (empty(trim_input($_POST["pwd"]))) 
        {
            $passwordErr = "Password is required";
            $okay = False;
        }
        
        else if (strlen(trim_input($_POST["pwd"])) < 8) 
        {
            $passwordErr = "Password must be longer than 8 characters";
            $okay = False;
        }
        else if(!preg_match('/^[a-zA-Z0-9@!#$%^&*._]+$/', trim_input($_POST["pwd"])))
        {
            $passwordErr = "Password only can contain alphanumeric and @!#$%^&*._";
            $okay = False;
        }
    
        if (empty(trim_input($_POST["confirmpwd"]))) 
        {
            $confirmPwdErr = "Confirm Password is required";
            $okay = False;
        }
        else if (trim_input($_POST["confirmpwd"]) != trim_input($_POST["pwd"])) 
        {
            $confirmPwdErr = "Passwords must match";
            $okay = False;
        }
        else if(!preg_match('/^[a-zA-Z0-9@!#$%^&*._]+$/', trim_input($_POST["confirmpwd"])))
        {
            $confirmPwdErr = "Password only can contain alphanumeric and @!#$%^&*._";
            $okay = False;
        }
        
        if (empty(trim_input($_POST["phone"]))) 
        {
            $phoneNoErr = "Phone is required";
            $okay = False;
        }
        else if (!is_numeric(trim_input($_POST["phone"]))) 
        {
            $phoneNoErr = "Only numbers are allowed";
            $okay = False;
        } 
        else if (strlen(trim_input($_POST["phone"])) != 8) 
        {
            $phoneNoErr = "The number need to be 8 digits";
            $okay = False;
        }
        
        if (empty(trim_input($_POST["nric"]))) 
        {
            $NRIC = "NRIC is required";
            $okay = False;
        }
    
        if (!preg_match($regularExpNRIC, trim_input($_POST["nric"])))
        {
            $NRIC = "NRIC format incorrect";
            $okay = False;
        }
            
        //Check Duplicate EMAIL
        $email = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['email']));
        $stmtCount = $MySQLiconn->prepare("SELECT COUNT(user_email) As RegisteredEmail FROM user_list where user_email = ?");
        $stmtCount->bind_param('s', $email);
        if (!$stmtCount->execute())
        {
            header("Location:errorPage.php");
        }
        $result = $stmtCount->get_result();
        $row = mysqli_fetch_assoc($result);

        if ($row['RegisteredEmail'] != 0)
        {
            $emailErr = "Email Already Registered";
            $okay = False;
        }
        
        $stmtCount->free_result();
        $stmtCount->close();
        
        
        //Check Duplicate NRIC
        $nric = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['nric']));    
        $stmtCount = $MySQLiconn->prepare("SELECT COUNT(user_nric) As RegisteredNRIC FROM user_list where user_nric = ?");
        $stmtCount->bind_param('s', $nric);
        if (!$stmtCount->execute())
        {
            header("Location:errorPage.php");
        }
        $result = $stmtCount->get_result();
        $row = mysqli_fetch_assoc($result);
        
        if ($row['RegisteredNRIC'] != 0)
        {
            $NRIC = "NRIC Already Registered";
            $okay = False;
        }
        
        $stmtCount->free_result();
        $stmtCount->close();
        
        if ($okay) 
        {
            $uname = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['name'])); 
            $uphone = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['phone'])); 
            $unric = mysqli_real_escape_string($MySQLiconn, trim_input($_POST['nric']));
            $upass = password_hash(trim_input($_POST['pwd']), PASSWORD_DEFAULT);
            $accType = 'User';
            $validatedNot = 'Not Validated';
        
            //QR Code
            $randmd = md5(uniqid(rand(), true));
            $fixedvalue = 123456;
            $qrcode = (string) ($randmd . $unric . $uname. trim_input($_POST["email"]));
            $base32 = new Base2n(5, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567', FALSE, TRUE, TRUE);
            $secret = $tfa->createSecret(160);
            $code = $tfa->getCode($secret);
  
            //Prepared Statement For Register Insert to dummy
            $stmt = $MySQLiconn->prepare("INSERT INTO dummy_table(dummy_username, dummy_email, dummy_pass, dummy_user_role, dummy_phone, dummy_NRIC, dummy_status, dummy_otpSecret) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $uname, $email, $upass, $accType, $uphone, $unric, $validatedNot, $secret);

            if ($stmt->execute())
            {
                $id = $stmt->insert_id;
                $_SESSION['dummy_id'] = $id;
    ?>
                <script>
                    alert('You may proceed to second step!');
                    window.location.href='afterRegister.php';
                </script>
    <?php
            }
            else 
            {
   header("Location:errorPage.php");
            }
    ?>

    <?php
    
        } 
        else 
        {
            ?>
            <script>alert('error while registering you...');</script>
            <?php
        }
    }
    
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
        <?php include 'header.inc'; ?>
        <form id="msform" method="POST" action="registerUser.php">
            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active">Register User</li>
                <li>Scan QR Code</li>
                <li>Enter OTP</li>
            </ul>
        
            <!-- fieldsets Page 1 -->
            <fieldset>
                <h2 style="color: red;">PLEASE ENSURE THAT YOU HAVE GOOGLE AUTHENTICATOR APP INSTALL</h2>

                <h2 class="fs-title">Register user</h2>
                <h3 class="fs-subtitle">This is the first step</h3>
                <div class="form-group">
                <label class="control-label col-md-3" for="firstname"><p>Username:</p></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name ="name" autocomplete="off" required>
                        <span style="float: left;" class="text-danger"><?php echo $nameErr; ?></span>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-md-3" for="email" name="email"><p>Email:</p></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="email" autocomplete="off" required>
                        <span style="float: left;" class="text-danger"><?php echo $emailErr; ?></span>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-md-3" for="pwd" name="pwd"><p>Password:</p></label>
                    <div class="col-md-9">          
                        <input type="password" class="form-control" name="pwd" autocomplete="off" required>
                        <span style="float: left;" class="text-danger"><?php echo $passwordErr; ?></span>
                    </div>
                </div>
                        
                <div class="form-group">
                    <label class="control-label col-md-3" for="pwd"><p>Password Confirm:</p></label>
                    <div class="col-md-9">          
                        <input type="password" class="form-control" autocomplete="off" name="confirmpwd" required>
                        <span style="float: left;" class="text-danger"><?php echo $confirmPwdErr; ?></span>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-md-3" for="phone"><p>Phone Number:</p></label>
                    <div class="col-md-9">          
                        <input type="number" class="form-control" autocomplete="off" name="phone" maxlength="8" required>
                        <span style="float: left;" class="text-danger"><?php echo $phoneNoErr; ?></span>
                    </div>
                </div>
            
                <div class="form-group">
                    <label class="control-label col-md-3" for="nric"><p>NRIC :</p></label>
                    <div class="col-md-9">          
                        <input type="text" class="form-control" autocomplete="off" name="nric" maxlength="9" required>
                        <span style="float: left;" class="text-danger"><?php echo $NRIC; ?></span>
                    </div>
                </div>
                
                <div class="form-group"> 
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" name="submit" id="btnSubmit" class="btn btn-primary" >Submit</button>
                    </div>
                </div>
                <input type="button" name="next" id="nextBtn" class="next action-button" value="Next" style="display: none;"/>
            </fieldset>
        </form>
    </body>

    <!-- jQuery --> 
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- jQuery easing plugin --> 
    <script src="js/jquery.easing.min.js" type="text/javascript"></script> 
   
    <script src="js/otpScript.js" type="text/javascript"></script> 
</html>
