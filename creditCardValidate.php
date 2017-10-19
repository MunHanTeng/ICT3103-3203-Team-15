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
    //$year = date('y', strtotime($CCED));
    $year = str_replace('/', '', $CCED);
    $checksum += $year;
    //$month = date('m', strtotime($CCED));
    //$d= ($month*100)+$year;
    //$checksum += $d;
    //echo 'checksum: '. $checksum;
    $check = str_split($checksum,3);
    if($check[0] == $CVV2){
        return TRUE;
    }else{
        return FALSE;
    }
}

require('mod10.php');
function validate($CCN, $CCED, $CVV2)
{
    $CARDTYPE=$ErrArr=NULL;
    $expm = substr($CCED, 0, 2);
    $expy = substr($CCED, 3, 4);
    $cc = new CCVal($CCN, $expm, $expy, $CVV2);

    $cstatus=$cc->IsValid();
    //echo $expy;

    if($cstatus[0]=="valid") {
        $CARDTYPE=$cstatus[1];
	$_POST["card_number"]=$cstatus[2];
	$_POST["card_cvv2"]=$cstatus[3];
        return true;
    }
    else 
    {
        $ErrArr=$cstatus;
        $_SESSION['message1'] =  $cstatus[0];
        return false;
    }
 
}


include_once 'dbconnect.php';
session_start();
    //echo $_POST['CreditCardNo'];
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
         if(empty($_POST['CreditCardName'])){
             $_SESSION['CreditCardName'] = 'Credit Card Name is Empty!';
             $okay = FALSE;
             header("Location: Payment2.php");
         }
         
         if($okay){
            if(validate($_POST['CreditCardNo'], $_POST['CreditCardExpiry'], $_POST['CVV2'])){
                $dic = array( 'A'=> 0, 'B'=>1, 'C'=>2, 'D'=>3, 'E'=>4);
                //echo '<br>';
                //echo 'PaymentMode: '. $_SESSION['PaymentMode'];
                //echo '<br>';
                //echo 'Seats selected: '.implode(', ', $_SESSION['check_list']);
                //echo '<br>';
                //echo 'show id: '. $_SESSION['show_id'];
                //echo '<br>';
                foreach ($_SESSION['check_list'] as $seat){
                    //echo '<br>';
                    $row = $dic[$seat[0]];
                    //echo 'Row: '. $dic[$seat[0]];
                    //echo '<br>';
                    //echo 'col: '. $seat[1];
                    $col = $seat[1];
                    //echo '<br>';
                    //echo 'seat: '. $seat;
                    
                    //echo '<br>';
		    $showInfoID = $_SESSION['show_id'];
                    $movieID = $_SESSION['movie_id'];
                    $userid = $_SESSION['user'];
                    //echo 'User id '.$_SESSION['user'];
                    //echo 'show info id: '. $showInfoID;
                    //echo 'show movie id: '. $movieID;
                    $CCNum = $_POST['CreditCardNo'];
                    $CCName = $_POST['CreditCardName'];
                    $sql_query=$MySQLiconn->query("INSERT INTO booking( showInfo_id, seat_no, showInfo_row, showInfo_column, user_id, movie_id, CreditCardNum, CreditCardName) VALUES ('$showInfoID','$seat','$row','$col', '$userid', '$movieID', '$CCNum', '$CCName')");
                    mysqli_query($MySQLiconn, $sql_query);
                }
                echo '<script language="javascript">';
                echo 'alert("Success! We will be sendig an email to you shortly");';
                echo 'window.location.href = "Success.php";';
                echo '</script>';
                $_SESSION['message2'] = 'Success! We will be sendig an email to you shortly!';
                //header("Location: Success.php");
            }else{
                echo '<script language="javascript">';
                echo 'alert("Please ensure that all details are correct");';
                echo 'window.location.href = "Payment2.php";';
                echo '</script>';
                echo $_SESSION['message1'];
                //header("Location: Payment2.php");  
            }
         }
         

     }
   
 ?>