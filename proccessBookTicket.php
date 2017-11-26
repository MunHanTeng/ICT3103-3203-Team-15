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

include_once 'dbconnect.php';

// RETRIEVE SHOW INFO
$showInfoQuery = $MySQLiconn->prepare("SELECT showInfo_date, showInfo_time, movie_id FROM showinfo WHERE showInfo_id = ?");
$showInfoQuery->bind_param('i', $_COOKIE['showinfoID']);
if (!$showInfoQuery->execute()) {
    unset($_SESSION['PaymentMode']);
    unset($_SESSION['check_list']);
    unset($_SESSION['show_id']);
    unset($_SESSION['CCN']);
    unset($_SESSION['CCE']);
    unset($_SESSION['CVV2']);
    unset($_SESSION['CCName']);
    unset($_SESSION['payment']);
    unset($_SESSION["session_check_list"]);
    unset($_SESSION["buy_ticket"]);
    header("Location:errorPage.php");
}
$showInfoResult = $showInfoQuery->get_result();

$showinfo = mysqli_fetch_assoc($showInfoResult);

//Retrieve the Movie ID    
$movieQuery = $MySQLiconn->prepare("SELECT movie_name, movie_id, movie_poster, movie_websiteLink FROM movie WHERE movie_id = ?");
$movieQuery->bind_param('i', $showinfo['movie_id']);
if (!$movieQuery->execute()) {
    unset($_SESSION['PaymentMode']);
    unset($_SESSION['check_list']);
    unset($_SESSION['show_id']);
    unset($_SESSION['CCN']);
    unset($_SESSION['CCE']);
    unset($_SESSION['CVV2']);
    unset($_SESSION['CCName']);
    unset($_SESSION['payment']);
    unset($_SESSION["session_check_list"]);
    unset($_SESSION["buy_ticket"]);
    header("Location:errorPage.php");
}
$movieResult = $movieQuery->get_result();
$movie = mysqli_fetch_assoc($movieResult);

$check_list = $_SESSION["session_check_list"];
foreach ($check_list as $seat) {
    $resExists = $MySQLiconn->prepare("SELECT movie_name FROM locked_seat WHERE movie_name = ? and showinfo_id = ? and seat_no = ?");
    $resExists->bind_param('sss', $movie['movie_name'], $_COOKIE['showinfoID'], $seat);
    if (!$resExists->execute()) {
        unset($_SESSION['PaymentMode']);
        unset($_SESSION['check_list']);
        unset($_SESSION['show_id']);
        unset($_SESSION['CCN']);
        unset($_SESSION['CCE']);
        unset($_SESSION['CVV2']);
        unset($_SESSION['CCName']);
        unset($_SESSION['payment']);
        unset($_SESSION["session_check_list"]);
        unset($_SESSION["buy_ticket"]);
        header("Location:errorPage.php");
    }
    $resExists->store_result();

    if ($resExists->num_rows == 0) {
        //For Insert
        $stmt3 = $MySQLiconn->prepare("INSERT INTO locked_seat(movie_name, showinfo_id, seat_no, user_id, timestamp) VALUES(?, ?, ?, ?, now())");
        $stmt3->bind_param('ssss', $movie['movie_name'], $_COOKIE['showinfoID'], $seat, $_SESSION['user']);
        if (!$stmt3->execute()) {
            unset($_SESSION['PaymentMode']);
            unset($_SESSION['check_list']);
            unset($_SESSION['show_id']);
            unset($_SESSION['CCN']);
            unset($_SESSION['CCE']);
            unset($_SESSION['CVV2']);
            unset($_SESSION['CCName']);
            unset($_SESSION['payment']);
            unset($_SESSION["session_check_list"]);
            unset($_SESSION["buy_ticket"]);
            header("Location:errorPage.php");
            $result = false;
        }
        $result = true;
    } else {
        ?>
        <script>
            alert('The seat has already been locked please try again later');
            window.location.href = 'bookTicket.php'
        </script>
        <?php

        $result = false;
    }
}

if ($result == true) { // check payment first then seats
    $PaymentMode = explode(("- $"), trim_input($_SESSION["buy_ticket"]));
    if ($PaymentMode[1] == 12.00 || $PaymentMode[1] == 12.50 || $PaymentMode[1] == 7.50) {
        $_SESSION['PaymentMode'] = $PaymentMode[1];
        $_SESSION['check_list'] = $check_list;
        $showInfoID = trim_input($_COOKIE['showinfoID']);
        $_SESSION['show_id'] = $showInfoID;
        header("Location: Payment.php");
    } else {
        unset($_SESSION['PaymentMode']);
        unset($_SESSION['check_list']);
        unset($_SESSION['show_id']);
        unset($_SESSION['CCN']);
        unset($_SESSION['CCE']);
        unset($_SESSION['CVV2']);
        unset($_SESSION['CCName']);
        unset($_SESSION['payment']);
        unset($_SESSION["session_check_list"]);
        unset($_SESSION["buy_ticket"]);
        header("Location:errorPage.php");
    }
}
?>
