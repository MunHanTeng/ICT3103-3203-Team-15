<?php

// Declare some variable for error message
    $error=false;
    $errorCName=null;
    $errorCNumScreen=null;
    $errorCAddress=null;
    $errorCGMAP=null;
    $errorMRTToC=null;
    $errorBUSToC=null;
    $ErroremptyFile=null;
    $errorCinemaRow=null;
    $errorCinemaColumn=null;
    $errorUploadImage=null;
    //echo '<script type="text/javascript">alert ("' . $_POST['fname'] . '")</script>';
    //Check if submit button is being pressed a not
    if (isset($_POST["submit"]))
    {
        $cinemaName = trim($_POST['cinemaName']);
        $cinemaNoOfScreen = trim($_POST['cinemaNoOfScreen']);
        $cinemaAddress = trim($_POST['cinemaAddress']);
        $cinemaGMAP = trim($_POST['cinemaGMAP']);
        $MRTToCinema = trim($_POST['MRTToCinema']);
        $BUSToCinema = trim($_POST['BUSToCinema']);
        $CinemaRow = trim($_POST['CinemaRow']);
        $CinemaColumn = trim($_POST['CinemaColumn']);
        
        //Special Characters
        $illegal = '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/';
        
        $illegalAddress = '/[a-zA-Z][\'^£$%&*()}{@~?><>|=_+¬]/';
        
        $illegalMRT = '/[\'^£$%&*}{@#~?><>|=_+¬-]/';
        
        $illegalBus = '/[\'^£$%&*}{@#~?><>|=_+¬-]/';

        $illegalNum = '/[a-zA-Z][\'^£$%&*()}{@#~?><>,|=_+¬-]/';
        
        if (empty($cinemaName))
        {
            $errorCName = "Please enter Cinema name";
            $error = true;
        }
        
        if (empty($cinemaNoOfScreen))
        {
            $errorCNumScreen = "Please enter Cinema Num Of Screen";
            $error = true;
        }
        
        if (empty($cinemaAddress))
        {
            $errorCAddress = "Please enter Cinema Address";
            $error = true;
        }
        
        if (empty($cinemaGMAP))
        {
            $errorCGMAP = "Please enter Cinema Google Map URL";
            $error = true;
        }
        
        if (empty($MRTToCinema))
        {
            $errorMRTToC = "Please enter MRT to Cinema";
            $error = true;
        }
        
        if (empty($BUSToCinema))
        {
            $errorBUSToC = "Please enter BUS to Cinema";
            $error = true;
        }
        
        if (empty($CinemaRow))
        {
            $errorCinemaRow = "Please enter Cinema Row";
            $error = true;
        }
        
        if (empty($CinemaColumn))
        {
            $errorCinemaColumn = "Please enter Cinema Column";
            $error = true;
        }
        
        //Check if string contain special Charcters Cinema Name
        if (preg_match($illegal, $cinemaName)) {
            $errorCName = "Special character is not allowed in Cinema Name";
            $error = true;
        }
        
        if (preg_match($illegalNum, $cinemaNoOfScreen)) {
            $errorCNumScreen = "Only numeric is allowed in number of screen";
            $error = true;
        }
        
        if (preg_match($illegalNum, $CinemaRow)) {
            $errorCinemaRow = "Only numeric is allowed in cinema row";
            $error = true;
        }
        
        if (preg_match($illegalNum, $CinemaColumn)) {
            $errorCinemaColumn = "Only numeric is allowed in cinema column";
            $error = true;
        }
        
        //Check if string contain special Charcters Cinema Address
        if (preg_match($illegalAddress, $cinemaAddress)) {
            $errorCAddress = "Special character is not allowed in Cinema Address";
            $error = true;
        }
        
        //Filter vaildate URL is check email format
        if (!$_POST['cinemaGMAP'] || !filter_var($_POST['cinemaGMAP'], FILTER_VALIDATE_URL))
        {
            $errorCGMAP = "Please enter a vaild Google Map URL";
            $error = true;
        }
        
        //Check if string contain special Charcters MRT to Cinema
        if (preg_match($illegalMRT, $MRTToCinema)) {
            $errorMRTToC = "Special character is not allowed in MRT To Cinema";
            $error = true;
        }
        
        //Check if string contain special Charcters Bus to Cinema
        if (preg_match($illegalBus, $BUSToCinema)) 
        {
            $errorBUSToC = "Special character is not allowed in Bus To Cinema";
            $error = true;
        }
        
        
        if ($error == false) 
        {
            include_once ("../dbconnect.php");
            if ($_FILES['uploadFile']['name']!="")
            {
                $image = file_get_contents($_FILES['uploadFile']['tmp_name']);
                $image = mysqli_real_escape_string($MySQLiconn, $image);
                $sql_query=$MySQLiconn->query("update cinema set cinema_name='$_POST[cinemaName]' "
                    . ", No_Of_Screen='$_POST[cinemaNoOfScreen]' , cinema_address='$_POST[cinemaAddress]' "
                    . ", cinema_googleMap='$_POST[cinemaGMAP]' , cinema_rows='$_POST[CinemaRow]' "
                    . ", cinema_column='$_POST[CinemaColumn]' , cinema_mrt='$_POST[MRTToCinema]'"
                    . ", cinema_bus='$_POST[BUSToCinema]' , cinema_image='$image' "
                    . "where cinema_id=".$_GET['update_id']);
                mysqli_query($MySQLiconn, $sql_query);
            }
            else 
            {
                $sql_query=$MySQLiconn->query("update cinema set cinema_name='$_POST[cinemaName]' "
                    . ", No_Of_Screen='$_POST[cinemaNoOfScreen]' , cinema_address='$_POST[cinemaAddress]' "
                    . ", cinema_googleMap='$_POST[cinemaGMAP]' , cinema_rows='$_POST[CinemaRow]' "
                    . ", cinema_column='$_POST[CinemaColumn]' , cinema_mrt='$_POST[MRTToCinema]'"
                    . ", cinema_bus='$_POST[BUSToCinema]' "
                    . "where cinema_id=".$_GET['update_id']);
                mysqli_query($MySQLiconn, $sql_query);
            }
            echo '<script language="javascript">';
            echo 'alert("Successfully Updated the record"); location.href="cmscinema.php"';
            echo '</script>';
        }
    }
    
    elseif (isset($_POST["cancel"]))
    {
        header("Location: cmsCinema.php");
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
            //Vaild File extentions
            var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];
            function readURL(input) 
            {
                //Check if input is file
                if (input.type == "file") 
                {
                    //get File Name
                    var sFileName = input.value;
                    if (sFileName.length > 0) 
                    {
                        var blnValid = false;
                        for (var j = 0; j < _validFileExtensions.length; j++) 
                        {
                            var sCurExtension = _validFileExtensions[j];
                            if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) 
                            {
                                blnValid = true;
                                break;
                            }
                        }
             
                        if (!blnValid) 
                        {
                            alert("Uploaded File extention does not match");
                            input.value = "";
                            return false;
                        }
                    }
                }
                if (input.files && input.files[0]) 
                {
                    var reader = new FileReader(); //Read the file
                    reader.onload = function (e) 
                    {
                        //Load the following function after successfully read the file
                        $('#uploadImage').attr('style', "display: ");
                        $('#uploadImage').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
                var pic_size = input.files[0].size / 1024; //Convert file size to KB
                if (pic_size > 2048) 
                {
                    alert('File size must be less than 2MB');
                    input.value = "";
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
            
            if(isset($_GET['update_id']))
            {
                echo '<script language="javascript">';
                echo 'document.getElementById("EditForm").style.display="block"';
                echo '</script>';

                $rec2 = "SELECT cinema_name, No_Of_Screen, cinema_address, 
            cinema_googleMap, cinema_mrt, cinema_bus, cinema_rows, cinema_column, cinema_image FROM cinema where cinema_id =".$_GET['update_id'];
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
                    <form name="editForm" id="EditForm" enctype="multipart/form-data" method="POST">
                        <?php
                            while($row = $records2->fetch_array())
                            {
                        ?>
                        <div>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCName</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCNumScreen</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCAddress</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCGMAP</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCinemaRow</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCinemaColumn</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorMRTToC</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorBUSToC</p>";} else echo "<p class='text-danger'></p>"?>
                        </div>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Cinema Name: </label>
                            <div class="col-sm-12">
                                <input type="text" name="cinemaName" class="form-control" id="cinemaName" placeholder="Enter Cinema Name"
                                    value="<?php echo $row[0]; ?>">
                            </div>
                        </div>
                        
                        <br/><br/><br/><br/>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Number of Screen: </label>
                            <div class="col-sm-12">
                                <input type="text" name="cinemaNoOfScreen" class="form-control" id="cinemaNoOfScreen" placeholder="Enter Cinema Number of Screen"
                                    value="<?php echo $row[1]; ?>">
                            </div>
                        </div>
            
                        <br/><br/><br/><br/>
                    
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Cinema Address: </label>
                            <div class="col-sm-12">
                                <input type="text" name="cinemaAddress" class="form-control" id="cinemaAddress" placeholder="Enter Cinema Address"
                                    value="<?php echo $row[2]; ?>">
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Google Map Addess: </label>
                            <div class="col-sm-12">
                                <input type="text" name="cinemaGMAP" class="form-control" id="cinemaGMAP" placeholder="Enter Cinema Google Map URL"
                                    value="<?php echo $row[3]; ?>">
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Row: </label>
                            <div class="col-sm-12">
                                <input type="text" name="CinemaRow" class="form-control" id="CinemaRow" placeholder="Enter Cinema total row"
                                    value="<?php echo $row[6]; ?>">
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Column: </label>
                            <div class="col-sm-12">
                                <input type="text" name="CinemaColumn" class="form-control" id="CinemaColumn" placeholder="Enter Cinema total column"
                                    value="<?php echo $row[7]; ?>">
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/>
                    
                        <div class="form-group">
                            <label class='control-label col-sm-12'>MRT To Cinema: </label>
                            <div class="col-sm-12">
                                <textarea rows="4" cols="79" name="MRTToCinema" class="form-control" id="MRTToCinema" placeholder="Enter MRT to Cinema"/><?php echo $row[4]; ?></textarea>
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/><br/><br/>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Bus To Cinema: </label>
                            <div class="col-sm-12">
                                <textarea rows="4" cols="79" name="BUSToCinema" class="form-control" id="BUUSToCinema" placeholder="Enter BUS to Cinema"/><?php echo $row[5]; ?></textarea>
                            </div>
                        </div>
                
                        <br /><br /><br /><br/>
            
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Cinema Image: </label>
                            <div class="col-sm-12">
                                <input type='file' id="uploadFile" name="uploadFile" onchange="readURL(this);" />
                                <img id="uploadImage" src="#" alt="Your Uploaded Image" style="display: none;" />
                                <?php echo "<p class='text-danger'>$ErroremptyFile</p>"; ?>                    
                            </div>
                        </div>
                
                        <br/><br/><br/><br/>
                
                        <div class="form-group">
                            <label class='control-label col-sm-12'>Current Cinema Image: </label>
                            <div class="col-sm-12">
                                <?php echo "<img src='data:image/jpeg;base64," . base64_encode($row[8]) . "' alt='' class='cinemaImage' />" ?>
                            </div>
                        </div>
                    
                        <br/><br/><br/><br/>
                
                        <?php
                            }
                        ?>
                
                        <br/><br/><br/><br/>
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