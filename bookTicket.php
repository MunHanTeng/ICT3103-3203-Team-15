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
        if (!isset($_SESSION['user'])) {
            echo '<script language="javascript">';
            echo 'alert("Please Login in order to be able to buy ticket sucessfully"); location.href="index.php"';
            echo '</script>';
        }
        include_once 'dbconnect.php';
        $showInfoID = $_COOKIE['showinfoID'];
        echo $showInfoID;
        $result = mysqli_query($MySQLiconn, "SELECT * FROM `showinfo` WHERE showInfo_id ='" . $showInfoID . "'");
        $showinfo = mysqli_fetch_assoc($result);
        
        $result2 = mysqli_query($MySQLiconn, "SELECT * FROM `movie` WHERE movie_id ='" . $showinfo['movie_id'] . "'");
        $result3 = mysqli_query($MySQLiconn, "SELECT * FROM `cinema` WHERE cinema_id ='" . $showinfo['cinema_id'] . "'");
        $movie = mysqli_fetch_assoc($result2);
        $cinema = mysqli_fetch_assoc($result3);
        
//        $sqlTime = "Select * from showinfo where movie_id='".$showinfo['movie_id']."' and cinema_id='".$showinfo['cinema_id']."' and showInfo_date='".$date['showInfo_date']."'";
//        $resultTime = mysql_query($sqlTime);
//        while ($time = mysql_fetch_assoc($resultTime)) {
//            echo $time['showInfo_id'];
//        }
        ?>
        <script>
            window.onload=function(){
              document.getElementById("StartBooking").style.display='none';
              document.getElementById("SeatSelection").style.display='none';
            }
        </script>
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
            <div class="row">
              <div class="container-fluid" style="background-color:#303030;">
                <?php
                $timestamp = strtotime($showinfo['showInfo_date']);
                $day = date('l', $timestamp);
                echo "<center><h5>You have selected movie : </h5><h4>". $movie['movie_name'] ."</h4>";
                echo "<h5> on </h5><h4>".$day."  ".$showinfo['showInfo_date']."  ".$showinfo['showInfo_time']."</h4></center>";
                
                //echo "<hr>";
                ?>
                <hr>
              </div>
            </div>
            </div>
               <script>
                 function updateAmount(list){
                    var count;
                    var countValue;
                    if(list.checked)
                    {
                       countValue = document.getElementById("demo").innerHTML;
                       if (countValue == '')
                       {
                           count=0;
                       }
                       else 
                       {
                           count = document.getElementById("demo").innerHTML;
                       }
                       count++
                       count = document.getElementById("demo").innerHTML = count;
                    }
                    else{
                       count = document.getElementById("demo").innerHTML;
                       
                       count--
                       document.getElementById("demo").innerHTML = count;
                    }
                    if(count != 0){
                         document.getElementById("SeatSelection").style.display='block';
                    }
                }
               </script>
               <script>
                  function TicketType(){
                    var PaymentMode = {"Standard Price - $12.50":12.50, "Visa Checkout- $12.00":12, "DBS/POSB Credit & Debit - $7.50":7.50};
                    
                    
                    var x = document.getElementById("BuyTicket").value;
                    var count = document.getElementById("demo").innerHTML.valueOf();
                    var TicketPrice = PaymentMode[x]* count;
                    //alert("Total Price: " + TicketPrice);
                    document.getElementById("TicketType").innerHTML = x;
                    document.getElementById("TicketType").innerHTML.style='color:White';
                    
                    document.getElementById("TicketPrice").innerHTML = "$"+PaymentMode[x];
                    document.getElementById("TicketPrice").innerHTML.style='White';
                    
                    document.getElementById("Qty").innerHTML = count;
                    document.getElementById("Qty").innerHTML.style='White';
                    
                    document.getElementById("TotalAmount").innerHTML = "$"+TicketPrice;
                    document.getElementById("TotalAmount").innerHTML.style='White';
                    
                    if(x != "" && count != 0){
                       document.getElementById("StartBooking").style.display='block';
                       
                    }
                  }
               </script>
            <div class="row">
                <div class="col-md-8 text-center">
                <div class="container-fluid" style="width:475px;height:235px">
                  <!--Create Button-->
                <form action="proccessBookTicket.php" method="POST">
                  <div class="btn-toolbar" name="SeatSelection" data-toggle="buttons" name="SelectedSeats" style="padding:10px">   
                  <?PHP
                  $dic = array( 0=>'A', 1=>'B', 2=>'C', 3=>'D', 4=>'E');
                  $var = 0;
                  $q = 1;
                  $sql = 'Select * from booking where showInfo_id = "'. $showInfoID.'"' ;
                  $bookedS = mysqli_query($MySQLiconn, $sql);
                  //echo 'count: '. count($bookedS);
                  //echo '<br>';
                  $bookedseats = array();
                  if( count($bookedS) > 0){
                     while ($seatNo = mysqli_fetch_assoc($bookedS)) {
                      //echo $seatNo['seat_no'];
                      //echo '<br>';
                      array_push($bookedseats,$seatNo['seat_no']);
                  } 
                  }
                  
                  echo '<center><h5>screen</h5></center>';
                  $checked = 0;
                      for($row =0; $row < 5; $row++){
                          echo '<h5>'.$dic[$row].'</h5>';
                         for($col = 0; $col < 10; $col++){
                            if (in_array(''.$dic[$row].($col+1).'', $bookedseats)){
                              echo '<label class="btn btn-primary btn-lg" disabled="disabled">';
                              echo '<input type="checkbox"  name="check_list[]" id="check" value="'.$dic[$row].($col+1).'">';
                              echo '</label>';   
                            }else{
                              echo '<label class="btn btn-primary btn-lg">';
                              echo '<input type="checkbox"  name="check_list[]" onchange = "updateAmount(this);" value="'.$dic[$row].($col+1).'">';
                              echo '</label>';  
                            }
                          }
                          echo '<br>';
                          
                      }
                      //echo 'You have selected '. $var.' number of buttons!';
                  ?>
                  </div>
                </div>
                 
                 <div id="SeatSelection">
                  <div class="container-fluid" style="background-color:#303030;padding-bottom: 10px">
                      <center> <h3 style="display:inline;"> You have selected </h3> <h3 id="demo" name="demo"></h3> <h3> seats!</h3></center>
                  
                     <hr>
                     
                  <Label style="color:white;">Select Ticket Price: </Label>
                  
                    <select name="BuyTicket" id="BuyTicket" onchange="TicketType()" class="form-control">
                        <option value="">Please Select your Payment Mode</option>
                        <option value="Standard Price - $12.50">Standard Price - $12.50</option>
                        <option value="Visa Checkout- $12.00">Visa Checkout- $12.00</option>
                        <option value="DBS/POSB Credit & Debit - $7.50">DBS/POSB Credit & Debit - $7.50</option>
                    </select>
                  
                    <hr>
                  
                  <div id="StartBooking" style="overflow-x:auto;">
                        <table style="width: 100%">
                            <tr>
                                <td><h5 style="margin-left: 5px" id="Pay">Ticket Type</h5></td>
                                <td><h5 id="Pay">Ticket Price</h5></td>
                                <td><h5 id="Pay">Qty</h5></td>
                                <td><h5 id="Pay">Total Amount</h5></td>
                            </tr>
                            <tr>
                                <td><h5 style="margin-left: 5px" id="TicketType" class="popup"></h5></td>
                                <td><h5 id="TicketPrice" class="popup">Ticket Price</h5></td>
                                <td><h5 id="Qty" class="popup">Qty</h5></td>
                                <td><h5 id="TotalAmount" class="popup">Total Amount</h5></td>
                            </tr>
                        </table>    
                      <input type="hidden" name="show_id" value="<?php echo $showinfo['showInfo_id']?>">
                      <br>
                      <button type="submit" name="submit" class="btn btn-primary">Start Payment</button>
                    </div>
                   </div>
                     
                 
            </form>
            </div>
           </div> 
          </div>
           
    </body>
</html>