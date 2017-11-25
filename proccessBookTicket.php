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
    ?>
    <script>
        alert('Error Displaying Ticket Information!');
        window.location.href = 'errorPage.php';
    </script>
    <?php

}
$showInfoResult = $showInfoQuery->get_result();

$showinfo = mysqli_fetch_assoc($showInfoResult);


$movieQuery = $MySQLiconn->prepare("SELECT movie_name, movie_poster, movie_websiteLink FROM movie WHERE movie_id = ?");
$movieQuery->bind_param('i', $showinfo['movie_id']);
if (!$movieQuery->execute()) {
    ?>
    <script>
        alert('Error Displaying Ticket Information!');
        window.location.href = 'errorPage.php';
    </script>
    <?php

}
$movieResult = $movieQuery->get_result();

$movie = mysqli_fetch_assoc($movieResult);

$check_list = $_POST['check_list'];
foreach ($check_list as $seat) {

    $resExists = $MySQLiconn->prepare("SELECT movie_name FROM locked_seat WHERE movie_name = ? and showinfo_id = ? and seat_no = ?");
    $resExists->bind_param('sss', $movie['movie_name'], $_COOKIE['showinfoID'], $seat);
    if (!$resExists->execute()) {
        ?>
        <script>
            alert('We encounter some errors!');
            window.location.href = 'errorPage.php'
        </script>
        <?php

    }
    $resExists->store_result();

    if ($resExists->num_rows == 0) {
        //For Insert
        $stmt3 = $MySQLiconn->prepare("INSERT INTO locked_seat(movie_name, showinfo_id, seat_no, user_id, timestamp) VALUES(?, ?, ?, ?, now())");
        $stmt3->bind_param('ssss', $movie['movie_name'], $_COOKIE['showinfoID'], $seat, $_SESSION['user']);
        if (!$stmt3->execute()) {
            ?>
            <script>
                alert('We encounter some error!');
                window.location.href = 'errorPage.php'
            </script>
            <?php

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
    $PaymentMode = explode(("- $"), trim_input($_POST['BuyTicket']));
    if ($PaymentMode[1] == 12.00 || $PaymentMode[1] == 12.50 || $PaymentMode[1] == 7.50) {
        $_SESSION['PaymentMode'] = $PaymentMode[1];
        $checkseats = true;
        for ($i = 0; $i < sizeof($check_list); $i++) {
            if (!preg_match('/^[A-E0-9]{2,3}$/', $check_list[$i])) {
                $checkseats = false;
                break;
            }
        }
        if ($checkseats == true) {

            $_SESSION['check_list'] = $check_list;
            $showInfoID = trim_input($_POST['show_id']);
            $_SESSION['show_id'] = $showInfoID;
            header("Location: Payment.php");
        } else {
            echo "<script>
            alert('An error has occurred. Please try again1!');
            window.location.href = 'MainMovie.php';
            </script>";
        }
    } else {
        echo "<script>
           alert('An error has occurred. Please try again!');
           window.location.href = 'MainMovie.php';
        </script>";
    }
}
?>
