<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_GET['qrCode'])) {
    header("Location: index.php");
}
$res = mysqli_query($MySQLiconn, "SELECT * FROM ticketcollection WHERE qrValue=".$_GET['qrCode']);
$res = mysqli_query($MySQLiconn, "UPDATE ticketcollection SET ticket_collected=1 WHERE qrValue=".$_GET['qrCode']);
$row = mysqli_affected_rows($res);
if($rows==1){
    echo "Tickets redeemed successfully";
}
else{
    echo "Error redeeming ticket";
}

echo 'QR Code is '. $_GET['qrCode'];


?>