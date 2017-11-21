<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function trim_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$PaymentMode = explode(("- $"), trim_input($_POST['BuyTicket']));
if ($PaymentMode[1] == 12.00 || $PaymentMode[1] == 12.50 || $PaymentMode[1] == 7.50) {
    $_SESSION['PaymentMode'] = $PaymentMode[1];

    $check_list = $_POST['check_list'];
    $_SESSION['check_list'] = $check_list;

    $showInfoID = trim_input($_POST['show_id']);
    $_SESSION['show_id'] = $showInfoID;
    header("Location: Payment.php");
} else {
    echo "<script>
           alert('An error has occurred. Please try again!');
           window.location.href = 'MainMovie.php';
        </script>";
}
?>
