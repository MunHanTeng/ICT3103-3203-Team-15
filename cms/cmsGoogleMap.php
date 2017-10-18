<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Google Map</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../images/gv32x32.ico" rel="shortcut icon" />

</head>
<body>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>

    
    <?php 
        session_start();
        include_once ("../dbconnect.php");
        
        $rec2 = "SELECT cinema_googleMap FROM cinema where cinema_id =".$_GET['id'];
        $records2 = $MySQLiconn->query($rec2);
    ?>
    
    <div class="container google">
        <div class="col-xs-9 google">
            <?php
                while($row = $records2->fetch_array())
                {
            ?>
            <?php 
                $image = $row[0];
                $imageData = base64_encode(file_get_contents($image));
                echo '<img src="data:image/jpeg;base64,'.$imageData.'">';
            ?>
            <?php
            }
            ?>
	</div>
    </div>
</body>
</html>