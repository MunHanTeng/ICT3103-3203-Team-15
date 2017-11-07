<?php

include "qrcodelib/qrlib.php";


require('mod10.php');

function validate($CCN, $CCED, $CVV2) {
    $CARDTYPE = $ErrArr = NULL;
    $expm = substr($CCED, 0, 2);
    $expy = substr($CCED, 3, 4);
    $cc = new CCVal($CCN, $expm, $expy, $CVV2);

    $cstatus = $cc->IsValid();
    //echo $expy;

    if ($cstatus[0] == "valid") {
        $CARDTYPE = $cstatus[1];
        $_POST["card_number"] = $cstatus[2];
        $_POST["card_cvv2"] = $cstatus[3];
        return true;
    } else {
        $ErrArr = $cstatus;
        $_SESSION['message1'] = $cstatus[0];
        return false;
    }
}

include_once 'dbconnect.php';
session_start();
//echo $_POST['CreditCardNo'];
if (isset($_POST['submit'])) {

    $okay = TRUE;
    if (empty($_POST['CreditCardNo'])) {
        $_SESSION['CCN'] = 'Credit Card No is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (!is_numeric($_POST['CreditCardNo'])) {
        $_SESSION['CCN'] = 'Credit Card No must be numeric!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (strlen($_POST['CreditCardNo']) != 16) {
        $_SESSION['CCN'] = 'Credit Card No must be 16 digit!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    if (empty($_POST['CreditCardExpiry'])) {
        $_SESSION['CCE'] = 'Credit Card Expiry date is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    if (empty($_POST['CVV2'])) {
        $_SESSION['CVV2'] = 'CVV2 is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (!is_numeric($_POST['CVV2'])) {
        $_SESSION['CVV2'] = 'CVV2/CVC2 number must be numeric!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (strlen($_POST['CVV2']) != 3) {
        $_SESSION['CVV2'] = 'CVV2/CVC2 must be 3 digit!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    if (empty($_POST['CreditCardName'])) {
        $_SESSION['CreditCardName'] = 'Credit Card Name is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }

    if ($okay) {
        if (validate($_POST['CreditCardNo'], $_POST['CreditCardExpiry'], $_POST['CVV2'])) {
            $dic = array('A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4);
            //echo '<br>';
            //echo 'PaymentMode: '. $_SESSION['PaymentMode'];
            //echo '<br>';
            //echo 'Seats selected: '.implode(', ', $_SESSION['check_list']);
            //echo '<br>';
            //echo 'show id: '. $_SESSION['show_id'];
            //echo '<br>';
            $userid = $_SESSION['user'];
            $CCNum = $_POST['CreditCardNo'];
            $CCName = $_POST['CreditCardName'];
            $date = $_SESSION['BookTime'];
            $timeType = explode(" ", $date);
            $timeItems = explode(":", $timeType[0]);

            $Formattime = implode(":", $timeItems);
            $Formattime .= ":00";
            $Formaydate = $_SESSION['BookDate'];

            // TICKET COLLECT
            $sql_queryticketCollect = $MySQLiconn->query("INSERT INTO ticketcollection( ticket_collected, booking_time, CreditCardNum, CreditCardName, user_id, booking_date) VALUES (0, '$Formattime', '$CCNum', '$CCName', '$userid', '$Formaydate')");
            mysqli_query($MySQLiconn, $sql_queryticketCollect);
            $collectionresult = mysqli_affected_rows($MySQLiconn);
            $id = mysqli_insert_id($MySQLiconn);
            foreach ($_SESSION['check_list'] as $seat) {
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

                // BOOKING
                $sql_querybooking = $MySQLiconn->query("INSERT INTO booking( showInfo_id, seat_no, showInfo_row, showInfo_column, movie_id, collection_id) VALUES ('$showInfoID','$seat','$row','$col', '$movieID', '$id')");
                mysqli_query($MySQLiconn, $sql_querybooking);

                // QR CODE
                $randmd = md5(uniqid(rand(), true));
                $fixedvalue = 123456;
                $qrcode = (string) ($fixedvalue . $randmd . $_SESSION['user'] . $_SESSION['show_id'] . $_SESSION['name']);                //qrcode making unencrypted
                $hashedfile = hash("sha256", $qrcode);
                $fileurl = '128.199.217.166/checkQRCode.php?qrCode=' . $hashedfile . '';
                $sql_qradd = $MySQLiconn->query("UPDATE ticketcollection SET qrValue='$hashedfile' WHERE collection_id='$id'");
                /* $collectid = mysqli_query($MySQLiconn, "SELECT collection_id FROM ticketcollection WHERE user_id ='". $userid. "'" );
                  echo $showtheid; //the problem is here
                 */
                //$sql_qradd = $MySQLiconn->query("UPDATE ticketcollection SET qrValue='$hashedfile' WHERE collection_id=22");
                //$sql_qradd = $MySQLiconn->query("INSERT INTO ticketcollection(qrValue) VALUES ('$hashedfile') WHERE (collection_id='".$collectid. "'");
                mysqli_query($MySQLiconn, $sql_qradd);
            }
            
            // EMAIL
            if ($sql_querybooking && $sql_queryticketCollect) {
                $id = mysqli_insert_id($MySQLiconn);
                $result = mysqli_query($MySQLiconn, "SELECT * FROM `showinfo` WHERE showInfo_id ='" . $_SESSION['show_id'] . "'");
                $showinfo = mysqli_fetch_assoc($result);
                $result2 = mysqli_query($MySQLiconn, "SELECT * FROM `movie` WHERE movie_id ='" . $showinfo['movie_id'] . "'");
                $movie = mysqli_fetch_assoc($result2);
               
                require 'email/PHPMailerAutoload.php';

                $mail = new PHPMailer;
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = '3103.3203.team15@gmail.com';       // SMTP username
                $mail->Password = 'te@m15ssd';                        // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                $mail->setFrom('from@example.com', 'Golden Village');
                $mail->addAddress('' . $_SESSION['email'] . '');      // Add a recipient

                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = '' . $movie['movie_name'] . ' seats successfully booked!';
                $mail->Body = '<p>Dear ' . $_SESSION['name'] . ',</p>
                                    <p>Please be informed that your transaction is confirmed and payment has been debited from your account.</p>
                                    <p><b><u>Movie Ticket Details</u></b>
                                    <p>Booked Date: ' . date("d-m-y", strtotime($showinfo['showInfo_date'])) . '</p> 
                                    <p>Booked Time: ' . $showinfo['showInfo_time'] . '</p>
                                    <p>Your seat(s) is/are ' . implode(', ', $_SESSION['check_list']) . ' with a total price of $' . $_SESSION['price'] . '</p>
                                    <p>Your QR:
                                    <p><img src= https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . $fileurl . '></p>';
                if (!$mail->send()) {
                    echo 'Movie tickets details could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("Success! We will be sending an email to you shortly");';
                    echo 'window.location.href = "Success.php";';
                    echo '</script>';
                    $_SESSION['message2'] = 'Success! We will be sending an email to you shortly!';
                }
            }
            //header("Location: Success.php");
        } else {
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
