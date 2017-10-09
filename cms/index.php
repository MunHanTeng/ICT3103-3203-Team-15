<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<!-- Declare some variable in php -->
<?php 
    session_start();
    include_once '../dbconnect.php';

    if(isset($_SESSION['user'])!="")
    {
        header("Location: cmshome.php");
    }
    // Declare some variable for error message
    $error=false;
    $Errorusername=null;
    $errorPasswd=null;
    //echo '<script type="text/javascript">alert ("' . $_POST['fname'] . '")</script>';
    //Check if submit button is being pressed a not
    if (isset($_POST["submit"]))
    {
        
        //Filter vaildate email is check email format
        
            $username = trim($_POST['username']);
            $upass = trim($_POST['passwd']);
            $res=$MySQLiconn->query("SELECT * FROM user_list WHERE username='$username' and user_role='Admin'");
            $row = $res->fetch_array();
            if($row['password']==md5($upass))
            {
                $_SESSION['user'] = $row['user_id'];
                header("Location: cmshome.php");
            }
            else
            {
                ?>
                <script>alert('Incorrect Password or Username');</script>
                <?php
            }
    }
    
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../images/gv32x32.ico" rel="shortcut icon" />
    </head>
    
    <body>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/scripts.js"></script>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">
                        Golden Village CMS Site
                    </a>
                </div>
            </div><!--/.container-fluid -->
        </nav>
        <div class="container">
            <div class="col-md-3 col-sm-3">
                
            </div>
      
            <div class="col-md-11 col-sm-11">
                <form class="form-horizontal" name="registerForm" method="post">
                    <div class="loginFormHeader">
                        <h2 class="loginFormH2">Golden Village CMS Site</h2>
                        <hr />
                    </div>
                    
                    <div class="loginForm">
                        <h2 class="loginFormH2_login">Login Account</h2>
                    
                                            
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="email">Username: </label>
                            <div class="col-sm-9">
                            <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username: "
                                value="<?php if ($error) echo $username; ?>">
                                <?php if ($error) {echo "<p class='text-danger'>$Errorusername</p>";} else echo "<p class='text-danger'></p>"?>
                            </div>
                        </div>
                        
                    
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="email">Password: </label>
                            <div class="col-sm-9">
                                <input type="password" name="passwd" class="form-control" id="passwd" placeholder="Enter Password"
                                value="<?php if ($error) echo $passwd; ?>">
                                <?php if ($error) {echo "<p class='text-danger'>$errorPasswd</p>";} else echo "<p class='text-danger'></p>"?>
                            </div>
                        </div>
                    
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-md-5">
                                <button type="submit" name="submit" class="btn btn-primary">Login</button>
                            </div>
                        </div>
                        <br />
                    </div>
                </form>
            </div>
        </div>
        <br /><br />
        <?php include 'cmsfooter.inc';?>
        <?php
        // put your code here
        ?>
    </body>
</html>
