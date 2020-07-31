<?php
$servername = "localhost";
$username = "dbname";
$password = "quix2012";
$dbname = "input";
date_default_timezone_set('Asia/Seoul');
$now = new DateTime();
parse_str( html_entity_decode( $_SERVER['QUERY_STRING']) , $out);
if ( array_key_exists( 'temp', $out ) ) {
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
// $datenow = $now->format("Y-m-d H:i:s");
$temp = $out['temp'];
$humi = $out['humi'];
  $om = $out['om'];
$sql = "INSERT INTO tempnhumi(temp,humi,om) VALUES ($temp, $humi, $om)";
 if ($conn->query($sql) === TRUE) {
echo "Sensed data saved.";
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
}
?>


