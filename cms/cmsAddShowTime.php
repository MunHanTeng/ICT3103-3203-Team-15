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
    if (isset($_POST["submit"]))
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
		$phpDate = strstr($showtimeDateHidden, ' ', true);
		$mysqldate = date( 'Y-m-d', strtotime($phpDate) );
	   
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
            
            $sql_query=$MySQLiconn->query("INSERT INTO showinfo(showInfo_date, showInfo_time, cinema_id, movie_id) VALUES
                ('$mysqldate','$_POST[showTime]','$cinemaName','$movieName')");
            mysqli_query($MySQLiconn, $sql_query);
			echo $phpDate;
            echo '<script language="javascript">';
            echo 'alert("Successfully Added new Show Info"); location.href="cmsManageShowtime.php"';
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
        <!-- If we include this in the date picker will not work with this jquery version -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
        <script src="../bootstrap-3.3.5-dist/js/bootstrap.min.js"></script> 
        <script src="../js/clockpicker.js"></script> 
        <div class="container">
            <div class="col-md-6 col-sm-6">
                <h1>Add Showtime</h1>
                <form enctype="multipart/form-data" method="POST" action="cmsAddShowTime.php">
                    <br />
                    <div class="form-group">
                        <p for="showtimeDate">Show Date: </p>
                        <input type="text" name="showtimeDate" class="form-control" id="datepicker" placeholder="Choose Showtime Date">
                        <br />
                        <p for="showtimeDate">Your Select Date is : </p><input type="text" name="showtimeDateHidden" class="showtimeDateHidden" id="showtimeDateHidden" value="<?php if ($error) echo $showtimeDateHidden; ?>">
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
                        <?php if ($error) {echo "<p class='text-danger'>$errorSDate</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <div class="form-group">
                        <p for="showtimeDate">Show Time: </p>
                        <p><input id="showTime" type="text" placeholder="Select Show Time" style="color: black;" class="showTime form-control" name="showTime"/></p>

                        <script>
                            $(function() {
                                $('#showTime').timepicker();
                            });
                        </script>
                        <?php if ($error) {echo "<p class='text-danger'>$errorSTime</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
                    <!-- Cinema Dropdown -->
                    <div class="form-group">
                        <p for="showtimeDate">Choose Cinema: </p>
                        <?php
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
                        <?php if ($error) {echo "<p class='text-danger'>$errorCinemaID</p>";} else echo "<p class='text-danger'></p>"?>
                    </div>
                    
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
                    
                    
                    <br />
                    <hr />
                    
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
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