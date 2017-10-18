<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CMS Movie</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
	<link href="../images/gv32x32.ico" rel="shortcut icon" />
</head>
<body>
<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/scripts.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<?php 
session_start();
include_once ("../dbconnect.php");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <?php include 'cmsheader.inc';?>
            <div class="jumbotron">
                <div class="container">
                    <?php
                        $sql = "DELETE FROM movie WHERE movie_id=" . $_GET['q'];
			if ($MySQLiconn->query($sql)) {
                            echo "<h1>Deleted successfully</h1>";
			}
			
                        else {
                            echo "<h1>Delete failed</h1>";
			}
                    ?>
		
                    <br />
                    <a href="cmsmovie.php"><button class="btn btn-default">Back</button></a>
		</div>
            </div>		
            <?php include 'cmsfooter.inc';?>	
	</div>
    </div>
</div>
</body>
</html>