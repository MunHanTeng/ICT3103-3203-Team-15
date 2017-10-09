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
                    <?php $resultUser = $MySQLiconn->query("SELECT  ( SELECT COUNT(*) FROM   user_list ) AS numUser,
                        (SELECT COUNT(*) FROM   user_list where user_role = 'admin') AS numUserAdmin, 
                        (SELECT COUNT(*) FROM   user_list where user_role = 'user') AS numUserReg FROM dual");
                        $resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
                        $userRow = $resultUser->fetch_array();
                    ?>
                    <?php include 'cmsheader.inc';?>
            <form name= "movie" class="form-horizontal" action="cmsmovieAdd_exec.php" enctype="multipart/form-data" method='post' id="movieform">
            <div class="form-group">
                <label class='control-label col-sm-3'>Name: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_name' class='form-control' placeholder='Enter movie name' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Type: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_type' class='form-control' placeholder='Enter movie type' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Cast: </label>
                <div class='col-sm-5'>
                    <textarea type='text' name='movie_cast' class='form-control' placeholder='Enter movie cast' required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Director: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_director' class='form-control' placeholder='Enter movie director' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Genre: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_genre' class='form-control' placeholder='Enter movie genre' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Release Date: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_release' class='form-control' placeholder='Enter movie release date' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Running Time: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_runningTime' class='form-control' placeholder='Enter movie running time' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Distributor: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_distributor' class='form-control' placeholder='Enter movie distributor' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Language: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_language' class='form-control' placeholder='Enter movie language' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Synopsis: </label>
                <div class='col-sm-5'>
                    <textarea type='text' name='movie_synopsis' class='form-control' placeholder='Enter movie synopsis' required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Terms and Conditions: </label>
                <div class='col-sm-5'>
                    <textarea type='text' name='movie_TNC' class='form-control' placeholder='Enter movie terms and conditions' required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Trailer: </label>
                <div class='col-sm-5'>
                    <textarea type='text' name='movie_trailerLink' class='form-control' placeholder='Enter movie trailer link' required></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Website Link: </label>
                <div class='col-sm-5'>
                    <input type='text' name='movie_websiteLink' class='form-control' placeholder='Enter movie website link' required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Poster: </label>
                <div class='col-sm-5'>
                    <input name="movie_poster" type="file" required></input>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-3'>Carousel: </label>
                <div class='col-sm-5'>
                    <input name="movie_carousel" type="file" required></input>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <a href="#" onclick="history.go(-1)"><button class="btn btn-default">Back</button></a>
                </div>
            </div>
            </form>
            <br /><br /><br /><br />
        
        <?php include 'cmsfooter.inc';?>
        </div>
    </div>
</div>

		</div>
	</div>
</div>

</body>
</html>