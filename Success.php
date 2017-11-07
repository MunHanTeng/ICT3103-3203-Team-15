<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />
    </head>
    
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <?php
        include 'header.inc';
        include_once 'dbconnect.php';
        if (isset($_SESSION['payment']) != "") 
        {
            header("Location: index.php");
        }
        //For tfa 
        require_once 'loader.php';
        Loader::register('lib','RobThree\\Auth');
        use \RobThree\Auth\TwoFactorAuth;
        $tfa = new TwoFactorAuth('ICT3203');
        
          $UserId = $_SESSION['user'];
          $check_list = $_SESSION['check_list'];
          $PaymentMode = $_SESSION['PaymentMode'];
          $showInfoID = $_SESSION['show_id'];
          $Name = $_SESSION['name'];
          $Email = $_SESSION['email'];  
        ?>
         <?php
            $result = mysqli_query($MySQLiconn, "SELECT * FROM `showinfo` WHERE showInfo_id ='" . $showInfoID . "'");
            $showinfo = mysqli_fetch_assoc($result);

            $result2 = mysqli_query($MySQLiconn, "SELECT * FROM `movie` WHERE movie_id ='" . $showinfo['movie_id'] . "'");
            $result3 = mysqli_query($MySQLiconn, "SELECT * FROM `cinema` WHERE cinema_id ='" . $showinfo['cinema_id'] . "'");
            $movie = mysqli_fetch_assoc($result2);
            $cinema = mysqli_fetch_assoc($result3);
            
            //$PaymentMode = {"Standard Price - $12.50":12.50, "Visa Checkout- $12.00":12, "DBS/POSB Credit & Debit - $7.50":7.50};
            $PaymentModeValue = array(
                    "Standard Price - $12.50" => 12.50,
                    "Visa Checkout- $12.00" => 12,
                    "DBS/POSB Credit & Debit - $7.50" => 7.50
                );       
        ?>
         <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li><a href="MainMovie.php" class="activeLink">Movies</a> <span class="divider"></span></li>
            <li class="active"><?php echo $movie['movie_name'] ?></li>
        </ul>
        <div class="container">
            
            <div class="col-md-4 text-center">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($movie['movie_poster']); ?>"> 
                    <br /><br />
                    <a class="btn btn-default" href="<?php echo $movie['movie_websiteLink']; ?>">Website</a>
           </div>
           <div class="col-md-8 text-center">
            <div class="container-fluid" style="background-color:#303030 ;padding-bottom: 10px">
               <div class="row"> 
               <?php
                 echo '<h5>You have selected &nbsp<span style="font-size:2.5em;color:yellow;">'. $movie['movie_name'].' </span></h5>';
                 echo '<hr>';
                 $timestamp = strtotime($showinfo['showInfo_date']);
                 $day = date('l', $timestamp);
                 echo '<p>Date:<span style="font-size:1em;color:yellow;">'.$day.','.$showinfo['showInfo_date'].'</span>&nbsp&nbsp&nbsp&nbsp&nbsp';
                 echo 'Time:<span style="font-size:1em;color:yellow;">'.$showinfo['showInfo_time'].'</span>&nbsp&nbsp&nbsp&nbsp&nbsp';
                 echo 'Cinema:<span style="font-size:1em;color:yellow;">'.$cinema['cinema_name'].'</span></p>';   
                 echo '<p>Seats Selected:<span style="font-size:1em;color:yellow;">'.implode(', ', $check_list).'</span></p>';    
               ?>
              </div> 
            </div>
            <div class="row">  
                <?php
                $TotAmnt = $PaymentModeValue[$PaymentMode]*count($check_list);
                 echo '<p style="text-align:left;" >Total Amount: &nbsp<span style="font-size:1.5em;color:yellow;">S$'.$TotAmnt.'</span></p>';
                ?>
            </div>
            <div class="row"> 
                <div class="container-fluid" style="background-color:#303030 ;padding-bottom: 10px;text-align: left;">
                    <p>Name: <span style="font-size:1.5em;color:yellow;"><?php echo $Name?></span></p>
                    <hr>
                    <p>Email: <span style="font-size:1.5em;color:yellow;"><?php echo $Email?></span></p>
                </div>
            </div>
            <?php
                   if(!empty($_SESSION['message2'])) {
                       echo '<h4>'.$_SESSION['message2'].'</h4>';
                   } 
            ?>
            </div> 
        </div>
        
      
    </body>
</html>