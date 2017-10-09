<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Golden Village</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="images/gv32x32.ico" rel="shortcut icon" />

    </head>
    <body>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <?php
        include 'header.inc';
        include_once 'dbconnect.php';

        //Print error message not working!
        if (!empty($_SESSION['message1'])) {
            echo '<h4>' . $_SESSION['message1'] . '</h4>';
        }
        $UserId = $_SESSION['user'];
        $check_list = $_SESSION['check_list'];
        $PaymentMode = $_SESSION['PaymentMode'];
        $showInfoID = $_SESSION['show_id'];
        $Name = $_SESSION['name'];
        $Email = $_SESSION['email'];
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $_SESSION['CCN'] =  $_SESSION['CCE'] =  $_SESSION['CVV2'] = "";
        }
        
        ?>
        <?php
        $result = mysqli_query($MySQLiconn, "SELECT * FROM `showinfo` WHERE showInfo_id ='" . $showInfoID . "'");
        $showinfo = mysqli_fetch_assoc($result);

        $result2 = mysqli_query($MySQLiconn, "SELECT * FROM `movie` WHERE movie_id ='" . $showinfo['movie_id'] . "'");
        $result3 = mysqli_query($MySQLiconn, "SELECT * FROM `cinema` WHERE cinema_id ='" . $showinfo['cinema_id'] . "'");
        $movie = mysqli_fetch_assoc($result2);
        $cinema = mysqli_fetch_assoc($result3);

        //$PaymentMode = {"Standard Price - $12.50":12.50, "Visa Checkout- $12.00":12, "DBS/POSB Credit & Debit - $7.50":7.50};
        $PaymentModeValue = array(
            "Standard Price - $12.50" => 12.50,
            "Visa Checkout- $12.00" => 12,
            "DBS/POSB Credit & Debit - $7.50" => 7.50
        );
        ?>
        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li><a href="MainMovie.php" class="activeLink">Movies</a> <span class="divider"></span></li>
            <li class="active"><?php echo $movie['movie_name'] ?></li>
        </ul>

        <div class="container">

            <div class="col-md-4 text-center">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($movie['movie_poster']); ?>"> 
                <br /><br />
                <a class="btn btn-default" href="<?php echo $movie['movie_websiteLink']; ?>">Website</a>
            </div>
            <div class="col-md-8 text-center">
                <div class="container-fluid" style="background-color:#303030 ;padding-bottom: 10px">
                    <div class="row"> 
                        <?php
                        echo '<h5>You have selected &nbsp<span style="font-size:2.5em;color:yellow;">' . $movie['movie_name'] . ' </span></h5>';
                        echo '<hr>';
                        $timestamp = strtotime($showinfo['showInfo_date']);
                        $day = date('l', $timestamp);
                        echo '<p>Date:<span style="font-size:1em;color:yellow;">' . $day . ',' . $showinfo['showInfo_date'] . '</span>&nbsp&nbsp&nbsp&nbsp&nbsp';
                        echo 'Time:<span style="font-size:1em;color:yellow;">' . $showinfo['showInfo_time'] . '</span>&nbsp&nbsp&nbsp&nbsp&nbsp';
                        echo 'Cinema:<span style="font-size:1em;color:yellow;">' . $cinema['cinema_name'] . '</span></p>';
                        echo '<p>Seats Selected:<span style="font-size:1em;color:yellow;">' . implode(', ', $check_list) . '</span></p>';
                        ?>
                    </div> 
                </div>
                <div class="row">  
                    <?php
                    $TotAmnt = $PaymentModeValue[$PaymentMode] * count($check_list);
                    echo '<p style="text-align:left;" >Total Amount: &nbsp<span style="font-size:1.5em;color:yellow;">S$' . $TotAmnt . '</span></p>';
                    ?>
                </div>
                <div class="row"> 
                    <div class="container-fluid" style="background-color:#303030 ;padding-bottom: 10px;text-align: left;">
                        <p>Name: <span style="font-size:1.5em;color:yellow;"><?php echo $Name ?></span></p>
                        <hr>
                        <p>Email: <span style="font-size:1.5em;color:yellow;"><?php echo $Email ?></span></p>
                    </div>
                </div>



                <div class="row"> 
                    <div class="container-fluid" style="background-color:#303030 ;padding-bottom: 10px;text-align: left;">
                        <form action="test.php" method="POST">
                            <input type="hidden" name="email" id="email" value="<?php echo $Email ?>">
                            <div class="form-group" >
                                <label for="CreditCardNo" style="color:white; text-align: right;float:left;">Credit Card No.:</label>
                                <input type="text" class="form-control" id="CreditCardNo" name="CreditCardNo" placeholder="Credit Card No">

                                <span class="text-danger">
                                    <?php
                                    if ($_SESSION['CCN'] == "") {
                                        echo "";
                                    } else {
                                        echo $_SESSION['CCN'];
                                    }
                                    ?></span>
                            </div>
                            <div class="form-group" >
                                <label for="CreditCardExpiry" style="color:white; text-align: right;float:left;">Credit Card Expiry Date</label>
                                <input type="input" class="form-control" id="CreditCardExpiry" name="CreditCardExpiry" placeholder="yyyy-mm">
                                <span class="text-danger"> <?php
                                    if ($_SESSION['CCE'] == "") {
                                        echo "";
                                    } else {
                                        echo $_SESSION['CCE'];
                                    }
                                    ?></span></span>
                            </div>
                            <div class="form-group" >
                                <label for="CVV2" style="color:white; text-align: right;float:left;">CVV2/CVC2</label>
                                <input type="text" class="form-control" id="CVV2" name="CVV2" placeholder="CVV2/CVC2">
                                <span class="text-danger"><?php
                                    if ($_SESSION['CVV2'] == "") {
                                        echo "";
                                    } else {
                                        echo $_SESSION['CVV2'];
                                    }
                                    ?></span>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-md" style="padding-left:2em;padding-right:2em;">Summary</button>
                        </form>

                    </div>
                </div>
            </div> 

    </body>
</html>