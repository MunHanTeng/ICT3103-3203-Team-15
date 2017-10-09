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

<?php
$sql = "SELECT * FROM movie WHERE movie_id = " . $_GET['q'];
if ($result = $MySQLiconn->query($sql)) {
	//fetch a record from result set into an associate array
	while ($row = $result->fetch_array()) {
		$movie_name = $row['movie_name'];
		$movie_type = $row['movie_type'];
		$movie_cast = $row['movie_cast'];
		$movie_director = $row['movie_director'];
		$movie_genre = $row['movie_genre'];
		$movie_release = $row['movie_release'];
		$movie_runningTime = $row['movie_runningTime'];
		$movie_distributor = $row['movie_distributor'];
		$movie_language = $row['movie_language'];
		$movie_synopsis = $row['movie_synopsis'];
		$movie_TNC = $row['movie_TNC'];
		$movie_trailerLink = $row['movie_trailerLink'];
		$movie_websiteLink = $row['movie_websiteLink'];
		$movie_poster = $row['movie_poster'];
		$movie_carousel = $row['movie_carousel'];
	}
}
mysqli_free_result($result);
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
            <form name= "movie" class="form-horizontal" action="<?php echo "cmsmovieEdit_exec.php?q=" . $_GET['q']; ?>" enctype="multipart/form-data" method='post'>
                <div class="form-group">
                    <label class='control-label col-sm-3'>Name: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_name' class='form-control' value="<?php echo $movie_name; ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Type: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_type' class='form-control' value="<?php echo $movie_type ?>" required></input>
                    </div>
		</div>
			
                <div class="form-group">
                    <label class='control-label col-sm-3'>Cast: </label>
			<div class='col-sm-5'>
                        <textarea type='text' name='movie_cast' class='form-control' required><?php echo $movie_cast; ?></textarea>
                    </div>
		</div>
			
                <div class="form-group">
                    <label class='control-label col-sm-3'>Director: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_director' class='form-control' value="<?php echo $movie_director ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Genre: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_genre' class='form-control' value="<?php echo $movie_genre ?>" required></input>
                    </div>
		</div>
			
                <div class="form-group">
                    <label class='control-label col-sm-3'>Release Date: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_release' class='form-control' value="<?php echo $movie_release ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Running Time: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_runningTime' class='form-control' value="<?php echo $movie_runningTime ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Distributor: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_distributor' class='form-control' value="<?php echo $movie_distributor ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Language: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_language' class='form-control' value="<?php echo $movie_language ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Synopsis: </label>
                    <div class='col-sm-5'>
                        <textarea type='text' name='movie_synopsis' class='form-control' required><?php echo $movie_synopsis ?></textarea>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Terms and Conditions: </label>
                    <div class='col-sm-5'>
                        <textarea type='text' name='movie_TNC' class='form-control' required><?php echo $movie_TNC ?></textarea>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Trailer: </label>
                    <div class='col-sm-5'>
                        <textarea type='text' name='movie_trailerLink' class='form-control' required><?php echo $movie_trailerLink ?></textarea>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Website Link: </label>
                    <div class='col-sm-5'>
                        <input type='text' name='movie_websiteLink' class='form-control' value="<?php echo $movie_websiteLink ?>" required></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Upload New Poster: </label>
                    <div class='col-sm-5'>
                        <input name="movie_poster" type="file"></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                        <?php
                            echo "<img src='data:image/jpeg;base64," . base64_encode($movie_poster) . "' />"
                        ?>
                    </div>
		</div>
		
                <div class="form-group">
                    <label class='control-label col-sm-3'>Upload New Carousel: </label>
                    <div class='col-sm-5'>
                        <input name="movie_carousel" type="file"></input>
                    </div>
		</div>
		
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                        <?php
                            echo "<img src='data:image/jpeg;base64," . base64_encode($movie_carousel) . "' />"
			?>
                    </div>
		</div>
		
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="confirm">Confirm</button>
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

</body>
</html>