<?php
include_once ("../dbconnect.php");
$id = $_GET['id']; // $id is now defined

// or assuming your column is indeed an int
// $id = (int)$_GET['id'];

mysqli_query($con,"DELETE FROM cinema WHERE id='".$id."'");
mysqli_close($con);
header("Location: index.php");
?> 