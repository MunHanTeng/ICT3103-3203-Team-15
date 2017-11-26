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
        <?php
        include 'header.inc';
        include_once 'dbconnect.php';
		include_once __DIR__ .'/libs/csrf/csrfprotector.php';
		csrfProtector::init();
        $resultPromotion = mysqli_query($MySQLiconn, "SELECT promotionInfo_title, promotionInfo_image, promotionInfo_description  FROM promotion");
        ?>
        <ul class="breadcrumb">
            <li><a href="index.php" class="activeLink">Home</a> <span class="divider"></span></li>
            <li class="active">Promotions</li>
        </ul>


        <div class="container">
            <?php
            $position = 0;
            $promotionNo = 0;
            while ($row = mysqli_fetch_assoc($resultPromotion)) {

                if ($position == 0) {
                    echo '<div class="row row-eq-height">';
                }
                echo '<div class="col-md-4 col-sm-4 col-height">';
                    echo '<div class="promotion">';
                        echo '<h2>'.$row['promotionInfo_title'].'</h2>';
                        echo '<p><img src="data:image/jpeg;base64,' . base64_encode($row['promotionInfo_image']) . '">'.$row['promotionInfo_description'].'</p>';
                        echo '<div class="col-bottom">';
                            echo '<a class="btn btn-primary" data-toggle="modal" data-target="#myModal'.$promotionNo.'">View Detail</a>';
                            echo '<!--Modal-->';
                            echo '<div class="modal fade" id="myModal'.$promotionNo.'" role="dialog">';
                                echo '<div class="modal-dialog">';
                                echo '<!-- Modal content-->';
                                    echo '<div class="modal-content">';
                                        echo '<div class="modal-header">';
                                        echo '<button style="color: white" type="button" class="close" data-dismiss="modal">&times;</button><!-- Makes the X -->';
                                        echo '<h4 class="modal-title">'.$row['promotionInfo_title'].'</h4>';
                                    echo '</div>'; //modal-header
                                    echo '<div class="modal-body">';
                                    if (!empty($row['promotionInfo_image'])) {
                                        echo '<img src="data:image/jpeg;base64,'. base64_encode($row['promotionInfo_image']).'">';
                                    }
                                    if (!empty($row['promotionInfo_description'])) {
                                        echo '<h4>PROMOTION DETAILS</h4>';
                                        echo '<p>'.$row['promotionInfo_description'].'</p>';
                                    }
                                    echo '</div>'; //modal-body
                                    echo '<div class="modal-footer">';
                                        echo '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
                                    echo '</div>'; //modal-footer;
                                echo '</div>'; //modal-content
                            echo '</div>'; //modal-dialog
                        echo '</div>'; //model fade
                    echo '</div>'; //container
                //promotion, column
                echo '</div></div>';
                if ($position == 2) {
                    echo '</div>';
                    $position = 0;
                } else {
                    $position++;
                }
                $promotionNo++;
            }
            if ($position != 2) {
                echo '</div>';
            }
            ?>
        </div>
    </div>
    <?php include 'footer.inc'; ?>
</body>
</html>