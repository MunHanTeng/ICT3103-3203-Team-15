<!doctype html>
<>
<head>
    <title>Golden Village</title>
    <link href="css/ticketcollection.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
</head>
    <?php
        require_once 'loader.php';
        Loader::register('lib','RobThree\\Auth');
        use \RobThree\Auth\TwoFactorAuth;
        $tfa = new TwoFactorAuth('ICT3203');
        include_once 'dbconnect.php';
        include 'header.inc';
    ?>

    <?php
            //For Retriving value
            $idDummy = $_SESSION['dummy_id'];
            $stmt = $MySQLiconn->prepare("SELECT dummy_username, dummy_email, dummy_pass, dummy_user_role, dummy_phone, dummy_NRIC, dummy_status, dummy_otpSecret FROM dummy_table WHERE dummy_id = ?");
            $stmt->bind_param('s', $idDummy);
            $stmt->execute();
            $result = $stmt->get_result();
            $userResult = mysqli_fetch_assoc($result);

            if (!$stmt->execute())
            {
     ?>

                <script>
                    alert('Error');
                    //window.location.href='errorPage.php'
                </script>
    <?php
            }
            
            $stmt->free_result();
            $stmt->close();
            
            
            
            //$stmt3 = $MySQLiconn->prepare("INSERT INTO user_list(username, user_email, password, user_role, phone, user_nric, status, otpSecretKey) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            //$stmt3->bind_param('dddddddd', $uname, $uemail, $upass, $urole, $uphone, $unric, $validNot, $usecretKey);

            
            //$user_result = mysqli_query($MySQLiconn, "SELECT * FROM dummy_table WHERE dummy_id='$id'");
            //$userResult = mysqli_fetch_assoc($user_result); 
            
            $uname = $userResult['dummy_username'];
            $uemail = $userResult['dummy_email'];
            $upass = $userResult['dummy_pass'];
            $urole = $userResult['dummy_user_role'];
            $uphone = $userResult['dummy_phone'];
            $unric = $userResult['dummy_NRIC'];
            $secret = $userResult['dummy_otpSecret'];
            $validNot = 'Validate';

            $result = ($tfa->verifyCode($secret, $_POST['otpcode']) === true ? 'OK' : 'Wrong OTP');
            //echo $qrValue;
            //$res = mysqli_query($MySQLiconn, "SELECT * FROM user_list WHERE otpSecretKey='$qrValue'");

            //Prepared Statement For Register Insert to user table
                
            // alert for testing purpose, real operation should be storing the secret into the database together with user account from session.
            //$userid = $_SESSION['user'];
            if ($result == 'OK')
            {
                
            
                //For Insert
                $stmt3 = $MySQLiconn->prepare("INSERT INTO user_list(username, user_email, password, user_role, phone, user_nric, status, otpSecretKey) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt3->bind_param('ssssssss', $uname, $uemail, $upass, $urole, $uphone, $unric, $validNot, $secret);
                
                if ($stmt3->execute())
                {
                    $id = $stmt3->insert_id;
                
                    //Delete Table
                    echo '<center><img src="images/successbutton.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
                    echo '<center><h1 style="color:yellow;">User Account Validated Successfully</h1></center>';
                    
                    $stmt2 = $MySQLiconn->prepare("delete from dummy_table WHERE dummy_id = ?");
                    $stmt2->bind_param('s', $idDummy);
                    $stmt2->execute();
                
                    if (!$stmt2->execute())
                    {
    ?>
                    <script>
                        alert('Error');
                        window.location.href='errorPage.php'
                    </script>
    <?php
                    }
                    else 
                    {
                        ?>
                    
                    <script>
                    alert('Sucessfully registered!');
                    window.setTimeout(function(){window.location.href='index.php';}, 5);

                </script>
                    
                    <?php
                    }
                
                
                //$sql_deletedummy = $MySQLiconn->query("delete from dummy_table where dummy_id = '$id'");
                //mysqli_query($MySQLiconn, $sql_deletedummy);
            }
            else 
            {
                echo $stmt3->error;
            ?>
                <script>
                    alert('Error registered!');
                    //window.location.href='errorPage.php'
                </script>
    <?php 
            }
    ?>
                
                <?php
                
                //$sql_adduser = $MySQLiconn->query("INSERT INTO user_list( username, user_email, password, user_role, phone, user_nric, status, otpSecretKey) VALUES ('$uname','$uemail','$upass','$urole', '$uphone', '$unric', 'Validated', '$usecretKey')");
                //mysqli_query($MySQLiconn, $sql_adduser);
            

            }
        else 
        {
            echo '<center><img src="images/unsucess.png" align="middle" alt="Sucess Image" style="margin-top: 10%; width: 10%; height: 10%;"></center>';
            echo '<center><h1 style="color:yellow;">Wrong OTP</h1></center>';
            sleep(3);
            ?>
            <script>
                window.setTimeout(function(){window.location.href='afterRegister.php';}, 5);

            </script>
        <?php
        }
    
    ?>           
</body>
</html>
