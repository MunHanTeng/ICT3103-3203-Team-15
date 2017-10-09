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
            
            $sql_query=$MySQLiconn->query("update showinfo set showInfo_date='$_POST[showtimeDateHidden]', showInfo_time='$_POST[showTime]', cinema_id='$cinemaName', movie_id='$movieName'");
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
        
        <script type="text/javascript">
            function delete_id(id)
            {
                if(confirm('Are you sure you want to delete this record ?'))
                {
                    window.location.href='cmsManageShowtime.php?delete_id='+id;
                }
            }
            
            function update_id(id)
            {
                document.getElementById("ShowInfoEditForm").style.display="block";
                window.location.href='cmsupdateshowtime.php?update_id='+id;
            }
            
            function onload()
            {
                
                if (window.location.href.indexOf("update_id") > -1)
                {
                   document.getElementById("ShowInfoEditForm").style.display="block";
                }
                else 
                {
                    document.getElementById("ShowInfoEditForm").style.display="none";
                }
            }
            
        </script>
        
    </head>
    
    <body onload="onload()">
        <?php
            session_start();
            if(!isset($_SESSION['user']))
            {
                header("Location: index.php");
            }
            include_once ("../dbconnect.php");
            $reclimit = 50; //Set Record Limit
     
            if(isset($_GET['page'])){
                $page = $_GET['page'];
            } else {
                $page = 1;
            }
 
            //Setting the page limit
            $start = (($page-1) * $reclimit);
            $sql = "SELECT * FROM showInfo";
            $records = $MySQLiconn->query($sql);
            $total = $records->num_rows; //Display Num of Row
            $tpages = ceil($total / $reclimit);

            
            $rec = "SELECT s.showInfo_id, s.showInfo_date, s.showInfo_time, c.cinema_name, m.movie_name 
                    FROM showinfo s inner join cinema c
                    on s.cinema_id = c.cinema_id
                    inner join movie m
                    on s.movie_id = m.movie_id LIMIT $start, $reclimit";
            $records = $MySQLiconn->query($rec);
            
            //execute the SQL query and return records
            $resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
            $userRow = $resultUser->fetch_array();
            $resultCount = $MySQLiconn->query("select count(*) from showinfo");   
            
            if(isset($_GET['delete_id']))
            {
                $sql_query=$MySQLiconn->query("DELETE FROM showInfo WHERE showInfo_id=".$_GET['delete_id']);
                mysqli_query($MySQLiconn, $sql_query);
                echo ("<SCRIPT LANGUAGE='JavaScript'>
                        window.alert('Succesfully Deleted')
                        window.location.href='cmsManageShowtime.php';
                        </SCRIPT>");
            }
            
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
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <form method="post">
                    <table border="2" class="summary">
                        <thead>
                            <tr>
                                <th class="col-md-1"><h4>Action</h4></th>
                                <!-- <th class="col-md-2"><h4>Cinema ID</h4></th> -->
                                <th class="col-md-3"><h4>Show Date</h4></th>
                                <th class="col-md-2"><h4>Show Time</h4></th>
                                <th class="col-md-2"><h4>Cinema Name</h4></th>
                                <th class="col-md-2"><h4>Movie Name</h4></th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php
                                $row2 = mysqli_fetch_row($resultCount);
                                $num = $row2[0];
                            
                                if ($num == 0)
                                {
                                    echo 
                                    "<tr><td colspan='8'><p class='centerText'>No Result to display</p></td></tr>";
                                }
                                else 
                                {
                                    while($row = $records->fetch_array())
                                    {
                            ?>                 
                                        <tr class='success'>
                                            <td class='col-md-1'><a href='javascript:delete_id(<?php echo $row[0]; ?>)'>Delete</a><br /><br /><a href='javascript:update_id(<?php echo $row[0]; ?>)'>Update</a></td>
                                            <!-- <td class='col-md-2'><p><?php echo $row[0]; ?></p></td> Show Time ID -->
                                            <td class='col-md-3'><p><?php echo $row[1]; ?></p></td> <!-- Show Time Date -->
                                            <td class='col-md-2'><p><?php echo $row[2]; ?></p></td> <!-- TIme of show TIme -->
                                            <td class='col-md-2'><p><?php echo $row[3]; ?></p></td> <!-- CInema Name -->
                                            <td class='col-md-2'><p><?php echo $row[4]; ?></p></td> <!-- Movie Name -->
                                        </tr>
                                    <?php
                                    }
                                }
                            ?>
                                
                            <tr>
                                <td colspan='10'>
                                    <!-- <button type="submit" name="delete" class="btn btn-primary" <?php if ($num <= 1) echo 'disabled="disabled"' ?>>delete</button> -->
                                    <a href="cmsAddShowTime.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign yellow">Add</span></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            
            <ul class="pagination">
            <?php
                for($i=1;$i<=$tpages;$i++) 
                {
                    echo "<li><a href=cmsManageShowtime.php?page=".$i.">".$i."</a></li>";
                }
            ?>
            </ul>

            <div><?php include 'cmsfooter.inc';?></div>
            
            <div class="col-md-12 col-sm-12">
                <form name="ShowInfoEditForm" id="ShowInfoEditForm" class="form-inline" enctype="multipart/form-data" method="POST">
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
                        <br />
                        <p for="showtimeDate">Your Select Date is : </p><input type="text" name="showtimeDateHidden" class="showtimeDateHidden" id="showtimeDateHidden" value="<?php echo $row2[0]; ?>">
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