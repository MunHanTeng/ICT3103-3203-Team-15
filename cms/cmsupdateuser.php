<?php

// Declare some variable for error message
    $error=false;
    $errorUName=null;
    $errorUPassword=null;
    $errorURole=null;
    $errorCUPassword=null;
    $errorCUDPassword=null;
    $errorUEmail = null;
    $errorPhone = null;
    
    //echo '<script type="text/javascript">alert ("' . $_POST['fname'] . '")</script>';
    //Check if submit button is being pressed a not
    if (isset($_POST["submit"]))
    {
        $userName = trim($_POST['userName']);
        $userPassword = trim($_POST['userPassword']);
        $userRole = trim($_POST['userrole']);
        $userCPassword = trim($_POST['userCPassword']);
        $userEmail = trim($_POST['userEmail']);
        $userPhone = trim($_POST['userPhone']);
        
        
        //Special Characters
        $illegal = '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/';
        
        if (empty($userName))
        {
            $errorUName = "Please enter User Name";
            $error = true;
        }
        
        if (empty($userPhone))
        {
            $errorPhone = "Please enter Phone number";
            $error = true;
        }
        
        if (!is_numeric($userPhone)) 
        {
            $phoneNoErr = "Only numbers are allowed";
            $error = true;
        }
        
        if (!empty($userPassword) && empty($userCPassword))
        {
            $errorCUPassword = "Please enter Confirm Password";
            $error = true;
        }
        
        if (empty($userPassword) && !empty($userCPassword))
        {
            $errorUPassword = "Please enter Password";
            $error = true;
        }
        
        if (empty($userEmail))
        {
            $errorUEmail = "Please enter User Email";
            $error = true;
        }
        
        if ($_POST['userrole']=='--Please Select--')
        {
            $errorURole = "Please select at least one user role";
            $error = true;
        }
                
        //Check if string contain special Charcters Cinema Name
        if (preg_match($illegal, $userName)) {
            $errorUName = "Special character is not allowed in User name";
            $error = true;
        }
        
        //Check if string contain special Charcters Cinema Name
        if (preg_match($illegal, $userPassword)) {
            $errorUPassword = "Special character is not allowed in Password";
            $error = true;
        }
        
        if ($userPassword != $userCPassword)
        {
            $errorCUDPassword = "Confirm Password and Password are different";
            $error = true;
        }
        
        //Filter vaildate email is check email format
        if (!$_POST['userEmail'] || !filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL))
        {
            $errorUEmail = "Please enter a vaild email";
            $error = true;
        }
        
        
        if ($error == false) 
        {
            include_once ("../dbconnect.php");
            $uname = mysqli_real_escape_string($MySQLiconn, $_POST['userName']);
            if (empty($userPassword) && empty($userCPassword))
            {
                $uname = mysqli_real_escape_string($MySQLiconn, $_POST['userName']);   
                $upass = md5(mysqli_real_escape_string($MySQLiconn, $_POST['userCPassword']));
                $mysql_query=$MySQLiconn->query("Update user_list set username='$uname', user_role='$_POST[userrole]', phone='$_POST[userPhone]', user_email='$_POST[userEmail]' where user_id =".$_GET['update_id']);
                mysqli_query($MySQLiconn, $mysql_query);
    
                echo '<script language="javascript">';
                echo 'alert("Successfully Updated User"); location.href="cmsManageUser.php"';
                echo '</script>';
            }
            if (!empty($userPassword) && !empty($userCPassword))
            {
                $uname = mysql_real_escape_string($_POST['userName']);   
                $upass = md5(mysql_real_escape_string($_POST['userCPassword']));
                $sql_query=$MySQLiconn->query("Update user_list set username='$uname', user_password='$upass', user_role='$_POST[userrole]', user_email='$_POST[userEmail]' where user_id =".$_GET['update_id']);
                mysqli_query($MySQLiconn, $sql_query);
    
                echo '<script language="javascript">';
                echo 'alert("Successfully Updated User"); location.href="cmsManageUser.php"';
                echo '</script>';
            }
        }
    }
    
    elseif (isset($_POST["cancel"]))
    {
        header("Location: cmsManageUser.php");
    }

?>

<html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>CMS Manage User</title>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../images/gv32x32.ico" rel="shortcut icon" />
        
        <script type="text/javascript">
            function delete_id(id)
            {
                if(confirm('Are you sure you want to delete this record ?'))
                {
                    window.location.href='cmsManageUser.php?delete_id='+id;
                }
            }
            
            function update_id(id)
            {
                document.getElementById("UserEditForm").style.display="block";
                window.location.href='cmsManageUser.php?update_id='+id;
            }
            
            function onload()
            {
                
                if (window.location.href.indexOf("update_id") > -1)
                {
                   document.getElementById("UserEditForm").style.display="block";
                }
                else 
                {
                    document.getElementById("UserEditForm").style.display="none";
                }
            }
            
        </script>
        
    </head>
    
    <body onload="onload()">
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/scripts.js"></script>
        <?php
            session_start();
            if(!isset($_SESSION['user']))
            {
                header("Location: index.php");
            }
            include_once ("../dbconnect.php");
            $reclimit = 5; //Set Record Limit
     
            
            if(isset($_GET['update_id']))
            {
                echo '<script language="javascript">';
                echo 'document.getElementById("UserEditForm").style.display="block"';
                echo '</script>';

                $rec2 = "SELECT username, user_email, user_role, phone from user_list where user_id =".$_GET['update_id'];
                $records2 = $MySQLiconn->query($rec2);
            }
        ?>
        
         <?php $resultUser = $MySQLiconn->query("SELECT  ( SELECT COUNT(*) FROM   user_list ) AS numUser,
                        (SELECT COUNT(*) FROM   user_list where user_role = 'admin') AS numUserAdmin, 
                        (SELECT COUNT(*) FROM   user_list where user_role = 'user') AS numUserReg FROM dual");
                        $resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
                        $userRow = $resultUser->fetch_array();
        ?>
        
        <?php include 'cmsheader.inc';?>
        
        <h1>Update User Page</h1>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form name="UserEditForm" id="UserEditForm" enctype="multipart/form-data" method="POST">
                        <?php
                            while($row = $records2->fetch_array())
                            {
                        ?>
                        <p>**NOTE: Updating an empty password will result in updating everything without password</p>
                        <div>
                            <?php if ($error) {echo "<p class='text-danger'>$errorUEmail</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorUName</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorURole</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCUDPassword</p>";} else echo "<p class='text-danger'></p>"?>
                        </div>
                    
                        <div class="form-group">
                            <label class='control-label col-sm-12'>User Name: </label>
                            <div class="col-sm-12">
                                <input type="text" name="userName" class="form-control" id="userName" placeholder="Enter User Name"
                                    accept=""value="<?php echo $row[0]; ?>">
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/> 
                        
                        <div class="form-group">
                            <label class='control-label col-md-12'>User Email: </label>
                            <div class="col-sm-12">
                                <input type="text" name="userEmail" class="form-control" id="userEmail" placeholder="Enter User Email"
                                       accept=""value="<?php echo $row[1]; ?>">
                            </div>
                        </div>
                        
                        <br/><br/><br/><br/>  
                        
                        <div class="form-group">
                            <label class='control-label col-md-12'>User Phone: </label>
                            <div class="col-sm-12">
                                <input type="text" name="userPhone" class="form-control" id="userPhone" maxlength="8" placeholder="Enter User Phone"
                                       accept=""value="<?php echo $row[3]; ?>">
                            </div>
                        </div>
                        
                        <br/><br/><br/><br/>
                            
                        <div class="form-group">
                            <label class='control-label col-md-12'>User Password: </label>
                            <div class="col-sm-12">
                                <input type="password" name="userPassword" class="form-control" id="userEmail" placeholder="Enter User Password"
                                    accept=""value="<?php if ($error) echo $userPassword; ?>">
                            </div>
                        </div>
                            
                        <br/><br/><br/><br/>  
                            
                        <div class="form-group">
                            <label class='control-label col-md-12'>User Confirm Password: </label>
                            <div class="col-sm-12">
                                <input type="password" name="userCPassword" class="form-control" id="userCPassword" placeholder="Enter User Confirm Password"
                                    accept=""value="<?php if ($error) echo $userCPassword; ?>">
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/>  
                            
                        <div class="form-group">
                            <label class='control-label col-md-12'>User Role: </label>
                            <div class="col-sm-12">
                                <?php
                                    // Set a variable of the pre-selected option.  This can come from a database or a form submission, etc.
                                    $item = '--Please Select--';
 
                                    // Create the array of role
                                    $userrole = array('--Please Select--', 'User', 'Admin');
 
                                    //Now echo out a select tag and make sure to give it a name
                                    echo '<select name="userrole" class="form-control">';
 
                                    //Now we use a foreach loop and build the option tags
                                    foreach($userrole as $r)
                                    {
                                        $sel= 'User'; // Set $sel to empty initially
                                        $tag = 'selected="selected"';
                                        $_POST['userrole'] = $row[2];
                                        if(isset($_POST['userrole']) && $_POST['userrole'] == $r) // Here we check if the form has been posted so an error isn't thrown and then check it's value against $c
                                        { 
                                            $sel = $tag; 
                                        }
                                        elseif(!isset($_POST['userrole']) && $item == $r) // So that the $item doesn't override the posted value we need to check to make sure the form has NOT been submitted also in the elseif()
                                        { 
                                            $sel = $tag; 
                                        }
	
                                        echo '<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
                                    }   
                                        
                                    //Echo the closing select tag
                                    echo '</select>';
                                ?>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                        <br /><br /><br /><br />
                        <hr />
                        <div class="form-group">
                            <p>**NOTE: For security reason purpose, All data will be revel back once you click submit</p>
                            <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            <button type='cancel' name='cancel' class='btn btn-primary'>Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>