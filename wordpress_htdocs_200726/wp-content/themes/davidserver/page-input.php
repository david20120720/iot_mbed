<?php
/*
Template Name: INPUT
Template Post Type: page
*/

/*
<!DOCTYPE html> 
<html> 
	<head> 
		<meta charset="utf-8"/> 
	</head> 
	<body> 
	<form action="" method="get">
		<p>temperature : <input type="text" name="temp"></p> 
		<p>humidity : <input type="text" name="humi"></p> 
		<p><input type="submit" /></p> 
	</form> 
	</body> 
</html>
*/

?>


<?php
header("Content-Type: text/html;charset=UTF-8"); 

$host = 'localhost'; 
$user = 'dbadmin'; 
$pw = 'quix2012'; 
$dbName = 'input'; 
$mysqli = new mysqli($host, $user, $pw, $dbName);

    if($mysqli){
		echo "MySQL successfully connected!<br/>"; 
		$temp = $_GET['temp']; 
		$humi = $_GET['humi']; 
		echo "<br/>Temperature = $temp"; 
		echo ", "; 
		echo "Humidity = $humi<br/>"; 
		$query = "INSERT INTO tempnhumi (temp, humi) VALUES ('$temp','$humi')"; 
		mysqli_query($mysqli,$query); 
		echo "</br>success!!"; 
	}
	else{ 
		echo "MySQL could not be connected";
   	} 
mysqli_close($mysqli);
?>
