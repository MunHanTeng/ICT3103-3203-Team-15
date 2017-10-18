 <!doctype html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>CMS Main</title>
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../css/style.css" rel="stylesheet">
      <link href="../images/gv32x32.ico" rel="shortcut icon" />
    
    </head>
    <body>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/scripts.js"></script>
        
        <?php
            session_start();
            include_once ("../dbconnect.php");
            if(!isset($_SESSION['user']))
            {
                header("Location: index.php");
            }
            //execute the SQL query and return records
            $resultUser = $MySQLiconn->query("SELECT  ( SELECT COUNT(*) FROM   user_list ) AS numUser,
            (SELECT COUNT(*) FROM   user_list where user_role = 'admin') AS numUserAdmin, 
            (SELECT COUNT(*) FROM   user_list where user_role = 'user') AS numUserReg FROM dual");
            $resultMovie = $MySQLiconn->query("select count(*) as numMovie from movie");
            $resultPromotion = $MySQLiconn->query("select count(*) as numPromotion from promotion");
            $resultCinema = $MySQLiconn->query("select count(*) as numCinema from cinema");
            $resultBooking = $MySQLiconn->query("select count(*) as numBooking from Booking");
			$resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
            $userRow = $resultUser->fetch_array();
            $resultCount = $MySQLiconn->query("select count(*) from cinema");   
        ?>
      <?php include 'cmsheader.inc';?>

      <h1>Summary</h1>
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <table border="2" class="summary">
        <thead>
            <tr>
                <th><h4>Item</h4></th>
                <th><h4>Summary Total</h4></th>
            </tr>
        </thead>
        <tbody>
            <?php
                while( $row = $resultUser->fetch_array()){
                    echo
                    "<tr class='success'>
                        <td><p>User</p></td>
                        <td><p>Total : {$row['numUser']} User</p>
                        <p>Total Admin User : {$row['numUserAdmin']} User</p>  
                        <p>Total Registered User : {$row['numUserReg']} User</p>   
                        </td>
                    </tr>\n";
                }
                
                while( $row = $resultMovie->fetch_array()){
                    echo
                    "<tr class='success'>
                        <td><p>Movie</p></td>
                        <td><p>{$row['numMovie']} Movie</p></td>
                    </tr>\n";
                }
                
                while( $row = $resultPromotion->fetch_array()){
                    echo
                    "<tr class='success'>
                        <td><p>Promotion</p></td>
                        <td><p>{$row['numPromotion']} Promotion</p></td>
                    </tr>\n";
                }
                
                while( $row = $resultCinema->fetch_array()){
                    echo
                    "<tr class='success'>
                        <td><p>Cinema</p></td>
                        <td><p>{$row['numCinema']} Cinema</p></td>
                    </tr>\n";
                }
                
                while( $row = $resultBooking->fetch_array()){
                    echo
                    "<tr class='success'>
                        <td><p>Booking</p></td>
                        <td><p>{$row['numBooking']} Booking</p></td>
                    </tr>\n";
                }
            ?>
        </tbody>
      </table>
          <?php include 'cmsfooter.inc';?>
      </div>
      
    </body>
    </html>