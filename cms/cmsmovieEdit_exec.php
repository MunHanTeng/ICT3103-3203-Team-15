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
            <?php 
                $resultUser = $MySQLiconn->query("SELECT  ( SELECT COUNT(*) FROM   user_list ) AS numUser,
                (SELECT COUNT(*) FROM   user_list where user_role = 'admin') AS numUserAdmin, 
                (SELECT COUNT(*) FROM   user_list where user_role = 'user') AS numUserReg FROM dual");
                $resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
                $userRow = $resultUser->fetch_array();
            ?>
            <?php include 'cmsheader.inc';?>
            <div class="jumbotron">
                <div class="container">
                    <?php
                        $sql1 = "UPDATE movie SET ";
                        $sql2 = "movie_name='". $_POST['movie_name'] . "', movie_type='" . $_POST['movie_type'] . 
                        "', movie_cast='" . $_POST['movie_cast'] . "', movie_director='" . $_POST['movie_director'] . 
                        "', movie_genre='" . $_POST['movie_genre'] . "', movie_release='" . $_POST['movie_release'] . 
                        "', movie_runningTime='" . $_POST['movie_runningTime'] . "', movie_distributor='" . $_POST['movie_distributor'] . 
                        "', movie_language='" . $_POST['movie_language'] . "', movie_synopsis='" . $_POST['movie_synopsis'] . 
                        "', movie_TNC='" . $_POST['movie_TNC'] . "', movie_trailerLink='" . $_POST['movie_trailerLink'] . 
                        "', movie_websiteLink='" . $_POST['movie_websiteLink'] . "' ";
            
                        if ($_FILES['movie_poster']['size'] == 0) {
                            $sql3 = "";
                        }
                            
                        else {
                            $poster = file_get_contents($_FILES['movie_poster']['tmp_name']);
                            $poster = mysqli_real_escape_string($MySQLiconn, $poster);
                            $sql3 = ", movie_poster='" . $poster . "' ";
                        }

                        if ($_FILES['movie_carousel']['size'] == 0) {
                            $sql4 = "";
                        }
                            
                        else {
                            $carousel = file_get_contents($_FILES['movie_carousel']['tmp_name']);
                            $carousel = mysql_real_escape_string($carousel);
                            $sql4 = ", movie_carousel='" . $carousel . "' ";
                        }                


                        $sql5 = "WHERE movie_id = " . $_GET['q'] . ";" ;   
                        $query = $sql1.$sql2.$sql3.$sql4.$sql5;

                        if ($MySQLiconn->query($query)) {
                            echo "<h1>Updated successfully</h1>";   
                        }
                    
                        else {
                            echo "<h1>Update was not successful</h1>";
                        }
                    ?>
                    <br>
                    <a href="cmsmovie.php"><button class="btn btn-primary">Back to Movie</button></a>
                    <a href="#" onclick="history.go(-1)"><button class="btn btn-default">Back to Edit</button></a>
                </div>
            </div>
            <?php include 'cmsfooter.inc';?>
        </div>
    </div>
</div>


</body>
</html>