<?php
/*
Template Name: list_data
Template Post Type: post, page, event
*/

?>


<!DOCTYPE html><html>
<head>
<title>Sensed Data</title>
</head><body>
<center>
<H1>My Sensor Data</H1>
</center>

<?php
include_once("./wp-config.php");
$current_user = wp_get_current_user();

if( 0 == $current_user->ID ) {
echo '<center>';
//??????echo 'Login name is: ' . $current_user->display_name . '<br />';
echo 'Login required... <br />';
echo '</center>';
exit();
}

echo "<center>";
echo "[" . $current_user->display_name . "(으)로?로그인]<br />";
echo "</center>";

$con = mysqli_connect('localhost','dbadmin','quix2012','input');

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
//Selecting the data from table but with limit
$query = "SELECT * FROM tempnhumi order by dt_create desc
limit 100000";
$result = mysqli_query ($con, $query);
?>

<table align="center" border="0" cellpadding="3">
<tr><th>Id</th><th>Send Date</th><th>Temperature(&deg;C)</th><th>Humidity(&#37;)</th><th>om</th></tr>
<?php
while ($row = mysqli_fetch_array($result)) {
?>
<tr align="center">
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['dt_create']; ?></td>
<td><?php echo $row['temp']; ?></td>
<td><?php echo $row['humi']; ?></td>
<td><?php echo $row['om']; ?></td>
</tr>
<?php
}
?>
</table>
</body>
</html>

