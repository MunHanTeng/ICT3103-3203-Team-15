<?php

function validateDate($date, $format = 'Y-m')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function check($CCN, $CCED, $CVV2)
{
    $checksum = 0;
    $array = str_split($CCN,4);
    foreach ($array as $value){
        //echo 'value: '.$value;
        $checksum += $value;
        //echo '<br>';
    }
    $year = date('y', strtotime($CCED));
    
    $checksum += $year;
    $month = date('m', strtotime($CCED));
    $d= ($month*100)+$year;
    $checksum += $d;
    //echo 'checksum: '. $checksum;
    $check = str_split($checksum,3);
    if($check[0] == $CVV2){
        return TRUE;
    }else{
        return FALSE;
    }
    
}

include_once 'dbconnect.php';
session_start();
    echo $_POST['CreditCardNo'];
     if(isset($_POST['submit'])){
        
         $okay = TRUE;
         if(empty($_POST['CreditCardNo'])){
             $_SESSION['CCN'] = 'Credit Card No is Empty!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }elseif(!is_numeric($_POST['CreditCardNo'])){
             $_SESSION['CCN'] = 'Credit Card No must be numeric!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }elseif(strlen($_POST['CreditCardNo']) != 16){
             $_SESSION['CCN'] = 'Credit Card No must be 16 digit!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }
         if(empty($_POST['CreditCardExpiry'])){
             $_SESSION['CCE'] = 'Credit Card Expiry date is Empty!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }elseif(var_dump(validateDate($_POST['CreditCardExpiry']))){
             $_SESSION['CCE'] = 'Please enter a valid Credit Card No Expiry date!'; 
             $okay = FALSE;
             header("Location: Payment2.php");
         }
         if(empty($_POST['CVV2'])){
             $_SESSION['CVV2'] = 'CVV2 is Empty!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }elseif(!is_numeric($_POST['CVV2'])){
             $_SESSION['CVV2'] = 'CVV2/CVC2 number must be numeric!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }elseif(strlen($_POST['CVV2']) != 3){
             $_SESSION['CVV2'] = 'CVV2/CVC2 must be 3 digit!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }
         
         if($okay){
            if(check($_POST['CreditCardNo'], $_POST['CreditCardExpiry'], $_POST['CVV2'])){
                $dic = array( 'A'=> 0, 'B'=>1, 'C'=>2, 'D'=>3, 'E'=>4);
                echo '<br>';
                echo 'PaymentMode: '. $_SESSION['PaymentMode'];
                echo '<br>';
                echo 'Seats selected: '.implode(', ', $_SESSION['check_list']);
                echo '<br>';
                echo 'show id: '. $_SESSION['show_id'];
                echo '<br>';
                foreach ($_SESSION['check_list'] as $seat){
                    echo '<br>';
                    $row = $dic[$seat[0]];
                    echo 'Row: '. $dic[$seat[0]];
                    echo '<br>';
                    echo 'col: '. $seat[1];
                    $col = $seat[1];
                    echo '<br>';
                    echo 'seat: '. $seat;
                    
                    echo '<br>';
					$showInfoID = $_SESSION['show_id'];
                    echo 'show info id: '. $showInfoID;
                    $sql_query=$MySQLiconn->query("INSERT INTO `booking`( `showInfo_id`, `seat_no`, `showInfo_row`, `showInfo_column`) VALUES ('$showInfoID','$seat','$row','$col')");
                    mysqli_query($MySQLiconn, $sql_query);
                }
               
                $_SESSION['message2'] = 'Success! Your movie tickets will emailed to you!';
                header("Location: Success.php");
            }else{
              $_SESSION['message1'] = 'The details you have provided were not valid!';
              header("Location: Payment2.php");  
            }
         }
         

     }
   
 ?>