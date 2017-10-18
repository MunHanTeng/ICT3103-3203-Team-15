<?php 
    include_once ("../dbconnect.php");
    // Declare some variable for error message
    $error=false;
    $errorSDate=null;
    $errorSTime=null;
    $errorMovieID=null;
    $errorCinemaID=null;
    
    //echo '<script type="text/javascript">alert ("' . $_POST['fname'] . '")</script>';
    //Check if submit button is being pressed a not
    if (isset($_POST["update"]))
    {
        $showtimeDateHidden = trim($_POST['showtimeDateHidden']);
        $showTime = trim($_POST['showTime']);
        $cinema = trim($_POST['cinema']);
        $movie = trim($_POST['movie']);
        
        if (empty($showtimeDateHidden))
        {
            $errorSDate = "Please enter Show Date";
            $error = true;
        }
        
        if (empty($showTime))
        {
            $errorSTime = "Please enter Show TIme";
            $error = true;
        }
        
        if ($movie=='--Please Select--')
        {
            $errorMovieID = "Please select at least one movie";
            $error = true;
        }
        
        if ($cinema=='--Please Select--')
        {
            $errorCinemaID = "Please select at least one cinema";
            $error = true;
        }
       
        $resultCinema = $MySQLiconn->query("select cinema_id from cinema where cinema_name='$_POST[cinema]'");
        while($row = $resultCinema->fetch_array())
        {
            $cinemaName = $row[0];
        }
        
        $resultMovie = $MySQLiconn->query("select movie_id from movie where movie_name='$_POST[movie]'");
        while($row2 = $resultMovie->fetch_array())
        {
            $movieName = $row2[0];
        }
        
        if ($error == false) 
        {
            include_once ("../dbconnect.php");
            
            $sql_query=$MySQLiconn->query("update showinfo set showInfo_date='$_POST[showtimeDateHidden]', showInfo_time='$_POST[showTime]', cinema_id='$cinemaName', movie_id='$movieName' where showInfo_id =".$_GET['update_id']);
            mysqli_query($MySQLiconn, $sql_query);
    
            echo '<script language="javascript">';
            echo 'alert("Successfully Update new Show Info"); location.href="cmsManageShowtime.php"';
            echo '</script>';
        }
    }
    
    elseif (isset($_POST["cancel"]))
    {
        header("Location: cmsManageShowtime.php");
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>CMS Add Show Time</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" >
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        
        <!-- Time Picker js and css -->
        <script type="text/javascript" src="../js/jquery.timepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.css" />
        <script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap-datepicker.css" />
        
        <!-- Date picker js and css -->
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    </head>
    
    <body>
        <?php
            session_start();
            if(!isset($_SESSION['user']))
            {
                header("Location: index.php");
            }
            include_once ("../dbconnect.php");
            
            //execute the SQL query and return records
            $resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
            $userRow = $resultUser->fetch_array();
            $resultCount = $MySQLiconn->query("select count(*) from showinfo");   
            
            if(isset($_GET['update_id']))
            {
                echo '<script language="javascript">';
                echo 'document.getElementById("ShowInfoEditForm").style.display="block"';
                echo '</script>';

                $rec2 = "SELECT s.showInfo_date, s.showInfo_time, c.cinema_name, m.movie_name from showInfo s 
                        Inner join cinema c on c.cinema_id = s.cinema_id
                        Inner join movie m on m.movie_id = s.movie_id
                        where s.showInfo_id =".$_GET['update_id'];
                $records2 = $MySQLiconn->query($rec2);
            }
        ?>
        
        <?php include 'cmsheader.inc';?>
        
        <h1>Show Information Page</h1>
            <div class="col-md-12 col-sm-12">
                <form name="ShowInfoEditForm" id="ShowInfoEditForm" enctype="multipart/form-data" method="POST">
                    <?php

                        while($row2 = $records2->fetch_array())
                        {
                    ?>
                        <div>
                            <?php if ($error) {echo "<p class='text-danger'>$errorSDate</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorSTime</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCinemaID</p>";} else echo "<p class='text-danger'></p>"?>
                            <?php if ($error) {echo "<p class='text-danger'>$errorCUDPassword</p>";} else echo "<p class='text-danger'></p>"?>
                        </div>
                        
                        <div class="form-group">
                        <p for="showtimeDate">Show Date: </p>
                        <input type="text" name="showtimeDate" class="form-control" id="datepicker" placeholder="Choose Showtime Date" value="<?php echo $row2[0]; ?>">
                        <br /><br />
                        <p for="showtimeDate">Your Select Date is : </p><input type="text" name="showtimeDateHidden" class="showtimeDateHidden form-control" id="showtimeDateHidden" value="<?php echo $row2[0]; ?>">
                        <!-- Javascript for date picker -->
                        <script type="text/javascript">
                            $( document ).ready(function() 
                            {
                                $("#datepicker").datepicker
                                ({
                                    dateFormat: "dd-mm-yy",
                                    onSelect: function(dateText, inst) 
                                    {
                                        var date = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
                                        var dateText = $.datepicker.formatDate("DD", date, inst.settings);
                                        document.getElementById("showtimeDateHidden").value = datepicker.value + ' ' + dateText; // Just the day of week
                                    }
                                });
                            });
                        </script>
                    </div>

                    <br /><br />
                    <div class="form-group">
                        <p for="showtimeDate">Show Time: </p>
                        <p><input id="showTime" type="text" placeholder="Select Show Time" style="color: black;" class="showTime form-control" name="showTime" value="<?php echo $row2[1]; ?>"/></p>

                        <script>
                            $(function() {
                                $('#showTime').timepicker();
                            });
                        </script>
                    </div>
                    
                    <br /><br />
                    <!-- Cinema Dropdown -->
                    <div class="form-group">
                        <p for="showtimeDate">Choose Cinema: </p>
                        <?php
                            $MySQLiconn = new MySQLi($DB_host,$DB_user,$DB_pass,$DB_name);
                            $resultCount = $MySQLiconn->query("select cinema_id, cinema_name from cinema"); 
                        ?>
                    
                        <?php
                            // Set a variable of the pre-selected option.  This can come from a database or a form submission, etc.
                            $item = '--Please Select--';
                        
                            // Create the array of role
                            $cinema = array('--Please Select--');
                            while($row = $resultCount->fetch_array())
                            {
                                array_push($cinema, $row[1]);
                            }
                        
                            //Now echo out a select tag and make sure to give it a name
                            echo '<select name="cinema" class="form-control">';
 
                            //Now we use a foreach loop and build the option tags
                            foreach($cinema as $r)
                            {
                                $sel=''; // Set $sel to empty initially
                                $tag = 'selected="selected"';
                                $_POST['cinema'] = $row2[2];
                                if(isset($_POST['cinema']) && $_POST['cinema'] == $r) // Here we check if the form has been posted so an error isn't thrown and then check it's value against $c
                                { 
                                    $sel = $tag; 
                                }
                                
                                elseif(!isset($_POST['cinema']) && $item == $r) // So that the $item doesn't override the posted value we need to check to make sure the form has NOT been submitted also in the elseif()
                                { 
                                    $sel = $tag; 
                                }	
                                echo '<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
                            }   
                            //Echo the closing select tag
                            echo '</select>';
                        ?>
                    </div>
                    
                    <br /><br />
                    <!-- Movie Dropdown -->
                    <div class="form-group">
                        <p for="showtimeDate">Choose Movie: </p>
                        <?php
                            $resultCount = $MySQLiconn->query("select movie_id, movie_name from movie"); 
                        ?>
                    
                        <?php
                            // Set a variable of the pre-selected option.  This can come from a database or a form submission, etc.
                            $item = '--Please Select--';
                        
                            // Create the array of role
                            $movie = array('--Please Select--');
                            while($row = $resultCount->fetch_array())
                            {
                                array_push($movie, $row[1]);
                            }
                        
                            //Now echo out a select tag and make sure to give it a name
                            echo '<select name="movie" class="form-control">';
 
                            //Now we use a foreach loop and build the option tags
                            foreach($movie as $r)
                            {
                                $sel=''; // Set $sel to empty initially
                                $tag = 'selected="selected"';
                                $_POST['movie'] = $row2[3];
                                if(isset($_POST['movie']) && $_POST['movie'] == $r) // Here we check if the form has been posted so an error isn't thrown and then check it's value against $c
                                { 
                                    $sel = $tag; 
                                }
                                
                                elseif(!isset($_POST['movie']) && $item == $r) // So that the $item doesn't override the posted value we need to check to make sure the form has NOT been submitted also in the elseif()
                                { 
                                    $sel = $tag; 
                                }	
                                echo '<option value="'.$r.'" '.$sel.'>'.$r.'</option>';
                            }   
                            //Echo the closing select tag
                            echo '</select>';
                        ?>
                        <?php if ($error) {echo "<p class='text-danger'>$errorMovieID</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <?php 
                        }
                    ?>
                        
                    <br /><br />
                    <hr />
                    <div class="form-group">
                        <p>**NOTE: For security reason purpose, All data will be revel back once you click submit</p>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <button type='cancel' name='cancel' class='btn btn-primary'>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php
        // put your code here
        ?>
    </body>
</html>