<?php 
    // Declare some variable for error message
    $error=false;
    $errorUName=null;
    $errorUPassword=null;
    $errorURole=null;
    $errorCUPassword=null;
    $errorCUDPassword=null;
    $errorUEmail = null;
    $errorUPhone=null;
    
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
            $errorUPhone = "Please enter Phone";
            $error = true;
        }
        
        if (!is_numeric($userPhone)) 
        {
            $errorUPhone = "Only numbers are allowed";
            $error = true;
        }
        
        if (empty($userPassword))
        {
            $errorUPassword = "Please enter User Password";
            $error = true;
        }
        
        if (empty($userCPassword))
        {
            $errorCUPassword = "Please enter User Confirm Password";
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
            $upass = md5(mysqli_real_escape_string($MySQLiconn, $_POST['userCPassword']));
            $sql_query=$MySQLiconn->query("INSERT INTO user_list(username, password, user_role, user_email, phone) VALUES
                ('$uname','$upass','$_POST[userrole]','$_POST[userEmail]','$_POST[userPhone]')");
            mysqli_query($MySQLiconn, $sql_query);
    
            echo '<script language="javascript">';
            echo 'alert("Successfully Added new User"); location.href="cmsManageUser.php"';
            echo '</script>';
            
            
        }
    }
    
    elseif (isset($_POST["cancel"]))
    {
        header("Location: cmsManageUser.php");
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CMS Add User</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" >
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>  
        <script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
        <div class="container">
            <div class="col-md-6 col-sm-6">
                <form enctype="multipart/form-data" method="POST" action="cmsAddUser.php">
                    <br />
                    <h1>Add User</h1>
                    <?php if ($error) {echo "<p class='text-danger'>$errorCUDPassword</p>";} else echo "<p class='text-danger'></p>"?>
                    <div class="form-group">
                        <p for="name">User Name: </p>
                        <input type="text" name="userName" class="form-control" required id="userName" placeholder="Enter User Name"
                            value="<?php if ($error) echo $userName; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorUName</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="name">User Email: </p>
                        <input type="text" name="userEmail" required class="form-control" id="userEmail" placeholder="Enter User Email"
                            value="<?php if ($error) echo $userEmail; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorUEmail</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="name">User Phone: </p>
                        <input type="text" name="userPhone" required class="form-control" id="userPhone" maxlength="8" placeholder="Enter User Phone"
                            value="<?php if ($error) echo $userPhone; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorUPhone</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                                    
                    <div class="form-group">
                        <p for="name">User Password: </p>
                        <input type="password" name="userPassword" required class="form-control" id="userPassword" placeholder="Enter User Password"
                            value="<?php if ($error) echo $userPassword; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorUPassword</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="name">User Confirm Password: </p>
                        <input type="password" name="userCPassword" required class="form-control" id="userCPassword" placeholder="Enter User Confirm Password"
                            value="<?php if ($error) echo $userCPassword; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCUPassword</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="name">User Role: </p>
                        
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
                                $sel=''; // Set $sel to empty initially
                                $tag = 'selected="selected"';
	
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
                        
                        <?php if ($error) {echo "<p class='text-danger'>$errorURole</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                                    
                    <br />
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <button type='cancel' name='cancel' class='btn btn-primary'>Cancel</button>
                    </div>
                    
                   <br /><br />
                </form>
            </div>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>