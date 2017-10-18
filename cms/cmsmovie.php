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
<script src="../http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<?php 
session_start();
include_once ("../dbconnect.php");
$resultUser = $MySQLiconn->query("SELECT * FROM user_list WHERE user_id=".$_SESSION['user']);
$userRow = $resultUser->fetch_array();
?>

<script type="text/javascript">
function delete_movie(id) {
	if(confirm('Are you sure you want to delete this movie?')) {
		window.location.href='cmsmovieDelete.php?q='+id;
	}
}
</script>
<?php include 'cmsheader.inc';?>
        <h1>Movie Information Page</h1>
	<div class="row">
		<div class="col-md-12">

				<!-- Table -->
				<div class="table-responsive">
					<table border="2" class="summary">
						<thead>
							<th class='col-md-2'><h4>Actions</h4></th>
							<th class='col-md-2'><h4>Movie Name</h4></th>
							<th class='col-md-2'><h4>Type</h4></th>
							<th class='col-md-2'><h4>Cast</h4></th>
							<th class='col-md-1'><h4>Director</h4></th>
							<th class='col-md-5'><h4>Sypnosis</h4></th>
							<th class='col-md-1'><h4>Distributor</h4></th>
							<th class='col-md-1'><h4>Running Time</h4></th>
							<th class='col-md-1'><h4>Release Date</h4></th>
							<th class='col-md-1'><h4>Genre</h4></th>
						</thead>
						<tbody>
						<?php
						$sql = "SELECT * FROM movie";
						//if ($result = mysqli_query($connection, $sql)) {
						if ($result = $MySQLiconn->query($sql)) {
							//fetch a record from result set into an associate array
							$i=1;
							while ($row = $result->fetch_array()) {
								echo "<tr>";
								echo "<td class='col-md-2'>";								
								echo "<a href='cmsmovieEdit.php?q=" . $row['movie_id'] . "'>";
								// echo "<button class='btn btn-primary'>Edit</button>";
								echo "Edit";
								echo "</a><br />";								
								echo "<a onClick=delete_movie(". $row['movie_id'] . ")>";
								// echo "<button class='btn btn-delete'>Delete</button>";
								echo "Delete";
								echo "</a>";
								echo "</td>";
								//echo "<td class='col-md-1'>" . $i . "</td>";
								echo "<td class='col-md-2'><p>" . $row['movie_name'] . "</td></p>";					
								echo "<td class='col-md-2'><p>" . $row['movie_type'] . "</p></td>";
								echo "<td class='col-md-2'><p>" . $row['movie_cast'] . "</p></td>";
								echo "<td class='col-md-1'><p>" . $row['movie_director'] . "</p></td>";
								echo "<td class='col-md-5'><p>" . $row['movie_synopsis'] . "</p></td>";
								echo "<td class='col-md-1'><p>" . $row['movie_distributor'] . "</p></td>";
								echo "<td class='col-md-1'><p>" . $row['movie_runningTime'] . "</p></td>";
								echo "<td class='col-md-1'><p>" . $row['movie_release'] . "</p></td>";
								echo "<td class='col-md-1'><p>" . $row['movie_genre'] . "</p></td>";
								echo "</tr>";
								$i++;
							}
						}
						mysqli_free_result($result);
						?>
							<tr>
								<td colspan='10'>
									<a href="cmsmovieAdd.php"><button class="btn btn-primary">Add</button></a>
								</td>
							</tr>
						</tbody>
					</table>
					<br /> <br />
				</div>
			

		
		<?php include 'cmsfooter.inc';?>	
		</div>
	</div>



<?php
// mysqli_close($connection);
?>
</body>
</html>