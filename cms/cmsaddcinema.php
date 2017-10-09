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
        //if (!$_POST['cinemaGMAP'] || !filter_var($_POST['cinemaGMAP'], FILTER_VALIDATE_URL))
        //{
        //    $errorCGMAP = "Please enter a vaild Google Map URL";
        //    $error = true;
        //}
        
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
        
        if ($_FILES['uploadFile']['name']=="")
        {
            $ErroremptyFile = "Cinema Image cannot be empty";
            $error = true;
        }
        
        
        if ($error == false && $_FILES['uploadFile']['name']!="") 
        {
            include_once ("../dbconnect.php");
            $image = file_get_contents($_FILES['uploadFile']['tmp_name']);
            $image = mysqli_real_escape_string($MySQLiconn, $image);
            $sql_query=$MySQLiconn->query("INSERT INTO cinema(cinema_name, No_Of_Screen, cinema_address, cinema_googleMap, cinema_mrt, cinema_bus, cinema_image, cinema_rows, cinema_column) VALUES
                ('$_POST[cinemaName]','$_POST[cinemaNoOfScreen]','$_POST[cinemaAddress]','$_POST[cinemaGMAP]','$_POST[BUSToCinema]','$_POST[MRTToCinema]','$image','$_POST[CinemaRow]','$_POST[CinemaColumn]')");
            mysqli_query($MySQLiconn, $sql_query);
    
            echo '<script language="javascript">';
            echo 'alert("Successfully Inserted"); location.href="cmscinema.php"';
            echo '</script>';
        }
    }
    
    elseif (isset($_POST["cancel"]))
    {
        header("Location: cmsCinema.php");
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>CMS Add Cinema</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" >
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        
        <script>
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
    
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>  
        <script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
        <h1>Add Cinema</h1>
        <div class="container">
            <div class="col-md-6 col-sm-6">
                <form enctype="multipart/form-data" method="POST" action="cmsaddcinema.php">
                    <br />
                    <div class="form-group">
                        <p for="name">Cinema Name: </p>
                        <input type="text" name="cinemaName" required class="form-control" id="cinemaName" placeholder="Enter Cinema Name"
                            value="<?php if ($error) echo $cinemaName; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCName</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                                    
                    <div class="form-group">
                        <p for="name">Number of Screen: </p>
                        <input type="text" name="cinemaNoOfScreen" required class="form-control" id="cinemaNoOfScreen" placeholder="Enter Cinema Number of Screen"
                            value="<?php if ($error) echo $cinemaNoOfScreen; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCNumScreen</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="name">Cinema Address: </p>
                        <input type="text" name="cinemaAddress" required class="form-control" id="cinemaAddress" placeholder="Enter Cinema Address"
                            value="<?php if ($error) echo $cinemaAddress; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCAddress</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                                    
                    <div class="form-group">
                        <p for="name">Google Map Address: </p>
                        <input type="text" name="cinemaGMAP" required class="form-control" id="cinemaGMAP" placeholder="Enter Cinema Google Map URL"
                            value="<?php if ($error) echo $cinemaGMAP; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCGMAP</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                                    
                    <div class="form-group">
                        <p for="name">MRT to Cinema: </p>
                        <textarea rows="4" cols="79" name="MRTToCinema" required class="form-control" id="MRTToCinema" placeholder="Enter MRT to Cinema"/><?php if ($error) echo $MRTToCinema; ?></textarea>
                        <?php if ($error) {echo "<p class='text-danger'>$errorMRTToC</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                                    
                    <div class="form-group">
                        <p for="name">Bus to Cinema: </p>
                        <textarea rows="4" cols="79" name="BUSToCinema" required class="form-control" id="BUSToCinema" placeholder="Enter Bus to Cinema"/><?php if ($error) echo $BUSToCinema; ?></textarea>
                        <?php if ($error) {echo "<p class='text-danger'>$errorBUSToC</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group col-md-6 col-sm-6">
                        <p for="name">Row: </p>
                        <input type="text" name="CinemaRow" required class="form-control" id="CinemaRow" placeholder="Enter Cinema total row"
                            value="<?php if ($error) echo $CinemaRow; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCinemaRow</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group col-md-6 col-sm-6">
                        <p for="name">Column: </p>
                        <input type="text" name="CinemaColumn" required class="form-control" id="CinemaColumn" placeholder="Enter Cinema total column"
                            value="<?php if ($error) echo $CinemaColumn; ?>">
                            <?php if ($error) {echo "<p class='text-danger'>$errorCinemaColumn</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="name">Cinema Image: </p>
                        <p>**NOTE: Maximum file size will be 2MB</p>
                        <input type='file' id="uploadFile" name="uploadFile" onchange="readURL(this);" />
                        <img id="uploadImage" src="#" alt="Your Uploaded Image" style="display: none;" />
                        <?php echo "<p class='text-danger'>$ErroremptyFile</p>"; ?>
                    </div>
                    
                    <br />
                    <hr />
                    
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