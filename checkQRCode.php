<?php

session_start();
include_once 'dbconnect.php';

if (!isset($_GET['qrCode'])) {
    header("Location: index.php");
}

$qrValue = mysqli_real_escape_string($MySQLiconn, $_GET['qrCode']);

$res = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection WHERE qrValue=" . $qrValue);
if (mysqli_num_rows($res) == 1) {
    $res = mysqli_query($MySQLiconn, "UPDATE ticketcollection SET ticket_collected=1 WHERE qrValue=" . $qrValue);
    $row = mysqli_affected_rows($res);
    if ($rows == 1) {
        echo "Tickets redeemed successfully";
    } else {
        echo "Error redeeming ticket";
    }
}


echo 'QR Code is ' . $qrValue;
?>