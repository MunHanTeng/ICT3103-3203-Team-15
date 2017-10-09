<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $check_list = $_POST['check_list'];
        $_SESSION['check_list']=$check_list;

        $PaymentMode = $_POST['BuyTicket'];
        $_SESSION['PaymentMode']=$PaymentMode;

        $showInfoID = $_POST['show_id'];
        $_SESSION['show_id']=$showInfoID;
         header("Location: Payment.php");
?>
