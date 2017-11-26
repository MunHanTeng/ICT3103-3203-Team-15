<?php

include "qrcodelib/qrlib.php";


require('mod10.php');

function trim_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function validate($CCN, $CCED, $CVV2) {
    $CARDTYPE = $ErrArr = NULL;
    $expm = substr($CCED, 0, 2);
    $expy = substr($CCED, 3, 4);
    $cc = new CCVal($CCN, $expm, $expy, $CVV2);

    $cstatus = $cc->IsValid();

    if ($cstatus[0] == "valid") {
        $CARDTYPE = $cstatus[1];
        $_POST["card_number"] = trim_input($cstatus[2]);
        $_POST["card_cvv2"] = trim_input($cstatus[3]);
        return true;
    } else {
        $ErrArr = $cstatus;
        $_SESSION['message1'] = $cstatus[0];
        return false;
    }
}

include_once 'dbconnect.php';
session_start();
if (isset($_POST['submit'])) {

    $okay = TRUE;
    if (empty(trim_input($_POST['CreditCardNo']))) {
        $_SESSION['CCN'] = 'Credit Card No is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (!is_numeric(trim_input($_POST['CreditCardNo']))) {
        $_SESSION['CCN'] = 'Credit Card No must be numeric!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (strlen(trim_input($_POST['CreditCardNo'])) != 16) {
        $_SESSION['CCN'] = 'Credit Card No must be 16 digit!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    if (empty(trim_input($_POST['CreditCardExpiry']))) {
        $_SESSION['CCE'] = 'Credit Card Expiry date is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    if (empty(trim_input($_POST['CVV2']))) {
        $_SESSION['CVV2'] = 'CVV2 is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (!is_numeric(trim_input($_POST['CVV2']))) {
        $_SESSION['CVV2'] = 'CVV2/CVC2 number must be numeric!';
        $okay = FALSE;
        header("Location: Payment2.php");
    } elseif (strlen(trim_input($_POST['CVV2'])) != 3) {
        $_SESSION['CVV2'] = 'CVV2/CVC2 must be 3 digit!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    if (empty(trim_input($_POST['CreditCardName']))) {
        $_SESSION['CCName'] = 'Credit Card Name is Empty!';
        $okay = FALSE;
        header("Location: Payment2.php");
    }
    else if(!preg_match('/^[a-zA-Z]+$/', trim_input($_POST["CreditCardName"])))
    {
        $_SESSION['CCName'] = "Only accept alphabetic Credit Card Name!";
        $okay = False;
        header("Location: Payment2.php");
    }

    if ($okay) {
        if (validate(trim_input($_POST['CreditCardNo']), trim_input($_POST['CreditCardExpiry']), trim_input($_POST['CVV2']))) {
            $dic = array('A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4);

            $userid = $_SESSION['user'];
            $CCNum = trim_input($_POST['CreditCardNo']);
            $CCName = trim_input($_POST['CreditCardName']);
            $date = $_SESSION['BookTime'];
            $timeType = explode(" ", $date);
            $timeItems = explode(":", $timeType[0]);

            $Formattime = implode(":", $timeItems);
            $Formattime .= ":00";
            $Formaydate = $_SESSION['BookDate'];

            // TICKET COLLECT
            $ticketCollectQuery = $MySQLiconn->prepare("INSERT INTO ticketcollection( ticket_collected, booking_time, CreditCardNum, CreditCardName, user_id, booking_date) VALUES (0, ?, ?, ?, ?, ?)");
            $ticketCollectQuery->bind_param('sssss', $Formattime, $CCNum, $CCName, $userid, $Formaydate);
            if (!$ticketCollectQuery->execute()) {
                header( "Location:errorPage.php" );

            }

            $id = mysqli_insert_id($MySQLiconn);
            foreach ($_SESSION['check_list'] as $seat) {
                $row = $dic[$seat[0]];
                $col = $seat[1];
                $showInfoID = $_SESSION['show_id'];
                $movieID = $_SESSION['movie_id'];

                // BOOKING                
                $bookingQuery = $MySQLiconn->prepare("INSERT INTO booking( showInfo_id, seat_no, showInfo_row, showInfo_column, movie_id, collection_id) VALUES (?, ?, ?, ?, ?, ?)");
                $bookingQuery->bind_param('ssssss', $showInfoID, $seat, $row, $col, $movieID, $id);
                if (!$bookingQuery->execute()) {
                   header( "Location:errorPage.php" );

                }

                // QR CODE
                $randmd = md5(uniqid(rand(), true));
                $fixedvalue = 123456;
                $qrcode = (string) ($fixedvalue . $randmd . $_SESSION['user'] . $_SESSION['show_id'] . $_SESSION['name']);                //qrcode making unencrypted
                $hashedfile = hash("sha256", $qrcode);
                $fileurl = 'https://128.199.217.166/checkQRCode.php?qrCode=' . $hashedfile . '';

                $QRQuery = $MySQLiconn->prepare("UPDATE ticketcollection SET qrValue=? WHERE collection_id=?");
                $QRQuery->bind_param('si', $hashedfile, $id);
                if (!$QRQuery->execute()) {
                   header( "Location:errorPage.php" );

                }
            }

            // EMAIL
            if ($ticketCollectQuery->affected_rows > 0 && $bookingQuery->affected_rows > 0 && $QRQuery->affected_rows > 0) {
                $id = mysqli_insert_id($MySQLiconn);

                // RETRIEVE SHOW INFO
                $showInfoQuery = $MySQLiconn->prepare("SELECT showInfo_date, showInfo_time, movie_id FROM showinfo WHERE showInfo_id = ?");
                $showInfoQuery->bind_param('i', $_SESSION['show_id']);
                if (!$showInfoQuery->execute()) {
                    unset($_SESSION['show_id']);
                    header( "Location:errorPage.php" );

                }
                $showInfoResult = $showInfoQuery->get_result();
                $showinfo = mysqli_fetch_assoc($showInfoResult);

                // RETRIEVE MOVIE 
                $movieQuery = $MySQLiconn->prepare("SELECT movie_name FROM movie WHERE movie_id = ?");
                $movieQuery->bind_param('i', $showinfo['movie_id']);
                if (!$movieQuery->execute()) {
                   header( "Location:errorPage.php" );

                }
                $movieResult = $movieQuery->get_result();
                $movie = mysqli_fetch_assoc($movieResult);

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
                                    <p>Your seat(s) is/are ' . trim_input(implode(', ', $_SESSION['check_list'])) . ' with a total price of $' . $_SESSION['price'] . '</p>
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
        } else {
            echo '<script language="javascript">';
            echo 'alert("Please ensure that all details are correct");';
            echo 'window.location.href = "Payment2.php";';
            echo '</script>';
            echo $_SESSION['message1'];
        }
    }
}
?>
