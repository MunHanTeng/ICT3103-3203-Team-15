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
                        $poster = file_get_contents($_FILES['movie_poster']['tmp_name']);
                        $poster = mysqli_real_escape_string($MySQLiconn, $poster);
                        
                        $carousel = file_get_contents($_FILES['movie_carousel']['tmp_name']);
                        $carousel = mysqli_real_escape_string($MySQLiconn, $carousel);
                    
                        $movieName = trim($_POST['movie_name']);
                        $movieType = trim($_POST['movie_type']);
                        $movieCast = trim($_POST['movie_cast']);
                        $movieDirector = trim($_POST['movie_director']);
                        $movieGenre = trim($_POST['movie_genre']);
                        $movieRelease = trim($_POST['movie_release']);
                        $movieRunningTime = trim($_POST['movie_runningTime']);
                        $movieDistributor = trim($_POST['movie_distributor']);
                        $movieLanguage = trim($_POST['movie_language']);
                        $movieSynopsis = trim($_POST['movie_synopsis']);
                        $movieTNC = trim($_POST['movie_TNC']);
                        $movieTrailerLink = trim($_POST['movie_trailerLink']);
                        $movieWebsiteLink = trim($_POST['movie_websiteLink']);
                        
                        $sql1 = "INSERT INTO movie (movie_name, movie_type, movie_cast, movie_director, movie_genre, movie_release, movie_runningTime, 
                        movie_distributor, movie_language, movie_synopsis, movie_TNC, movie_trailerLink, movie_websiteLink, movie_poster, movie_carousel) VALUES
                        ('$movieName', '$movieType', '$movieCast', '$movieDirector', '$movieGenre', '$movieRelease', '$movieRunningTime', "
                        . "'$movieDistributor', '$movieLanguage', '$movieSynopsis', '$movieTNC', '$movieTrailerLink', '$movieWebsiteLink', '$poster', '$carousel')";
                        
                        /* $sql2 = "('" . $_POST['movie_name'] . ', ' . $_POST['movie_type'] . ', ' 
                        . $_POST['movie_cast'] . ', ' . $_POST['movie_director'] . ', ' 
                        . $_POST['movie_genre'] . ', ' . $_POST['movie_release'] . ', ' 
                        . $_POST['movie_runningTime'] . ', ' . $_POST['movie_distributor'] . ', ' 
                        . $_POST['movie_language'] . ', ' . $_POST['movie_synopsis'] . ', ' 
                        . $_POST['movie_TNC'] . ', ' . $_POST['movie_trailerLink'] . ', ' 
                        . $_POST['movie_websiteLink'] . ");'"; */
                        
                        $query = $sql1;
                        if ($MySQLiconn->query($query)) {
                            echo "<h1>Movie Added Sucessfully</h1>";   
                        }
                        
                        else {
                            echo $MySQLiconn->error;
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