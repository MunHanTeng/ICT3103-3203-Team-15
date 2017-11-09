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
    <?php
        include 'header.inc';
        include_once 'dbconnect.php';
        //if(isset($_POST['submit'])){
        $check_list = $_SESSION['check_list'];
       // $_SESSION['check_list']=$check_list;

        $PaymentMode = $_SESSION['PaymentMode'];
        //$_SESSION['PaymentMode']=$PaymentMode;

        $showInfoID = $_SESSION['show_id'];
        
        ?>
        <?php
            $result = mysqli_query($MySQLiconn, "SELECT * FROM `showinfo` WHERE showInfo_id ='" . $showInfoID . "'");
            $showinfo = mysqli_fetch_assoc($result);
            //echo count($showinfo);
            //echo '<br>';
            //$movieID = $showinfo['movie_id'];
            $result2 = mysqli_query($MySQLiconn, "SELECT * FROM `movie` WHERE movie_id ='" . $showinfo['movie_id'] . "'");
            $result3 = mysqli_query($MySQLiconn, "SELECT * FROM `cinema` WHERE cinema_id ='" . $showinfo['cinema_id'] . "'");
            $movie = mysqli_fetch_assoc($result2);
            $cinema = mysqli_fetch_assoc($result3);
            
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
                   echo '<p>You have selected movie: <span style="font-size:2.5em;color:yellow;">'.$movie['movie_name'].'</span>&nbsp&nbsp&nbsp&nbsp&nbsp</p>';
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
            <script>
                function validate() {
                    var isValid = true;
                    var form1 = document.getElementById('STARTLOGIN');
                    document.getElementById('STARTLOGIN').style.display='none';

                    if (isValid) {
                        document.getElementById('form1').style.display='block';
                        return false;
                    }
                }
            </script>
              <div class="row">  
                <?php
                $TotAmnt = $PaymentModeValue[$PaymentMode]*count($check_list);
                 echo '<p style="text-align:left;" >Total Amount: &nbsp<span style="font-size:1.5em;color:yellow;">S$'.$TotAmnt.'</span></p>';
                ?>
                 </div>
                  <?php
                 if(!isset($_SESSION['user'])){
                    echo '<button type="button" name="STARTLOGIN" id="STARTLOGIN" class="btn btn-danger btn-md" onClick ="validate()">Login</button>';
                    echo '<div class="row">'; 
                    echo '<form id="form1" action="login.php" method="POST" style="display:none;">';   
                       echo 'Username: <input type="text" name="email">';
                       echo 'Password: <input type="password" name="pwd">';
                       echo '<button type="submit" name="submit" class="btn btn-danger btn-md" style="padding-left:2em;padding-right:2em;float: right;">submit</button>'; 
                    echo '</form>';
                    echo '<div>';    
                 }else{
                    echo '<form action="Payment2.php" method="POST">';
                       echo '<button type="submit" name="Next" id="Next" class="btn btn-danger btn-md" >Next</button>';
                    echo '</form>';
                 }
                  
                ?>
           </div>
        </div> 

        <?php
       // }
        ?>
         <?php
              
         ?> 
    </body>
</html>