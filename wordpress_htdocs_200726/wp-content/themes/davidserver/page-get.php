<?php
/*
Template Name: GET
Template Post Type: page
*/
?>

<?php get_header(); ?>

<div class="container">
	<div class="container-box">
		<div class="main-content">
			<?php
			if(have_posts()) {
				while(have_posts()) :the_post();
					the_content();
				endwhile;
			}
			?>
		</div>

	</div>
</div>
	


<?PHP
/* david mbed 에서 소켓통신(cpp ; mbed api )으로 보내온 데이터를 받는다(php 소켓 서버) 


$addr = gethostbyname('127.0.0.1');
$port = 5555;
$buf = "";

$sock = socket_create(AF_INET, SOCK_DGRAM, 0);
if($sock < 0)	die(socket_strerror($sock));

if(($ret = socket_bind($sock, $addr, $port)) < 0)	die(socket_strerror($ret));

do
{
	$read = socket_recvfrom($sock, $buf, 2048, 0, $addr, $port);
	echo "Receive data : $buf <br>";

	$temp = preg_split("/\s+/", $buf);
	sort($temp);
	
	for($i = count($temp) - 1; $i >= 0; $i--)
	{
		$resp .= $temp[$i] ." ";
	}
	
	$send = socket_sendto($sock, $resp, strlen($resp), 0, $addr, $port);
	echo "Send data : $resp <br>";
} while($read < 0);

socket_close ($sock);

david end *************************************************/

?>
















<!-- node mcu에서 측정 data를 웹서버로 보내면, 아래코드로  data를 받아서 data base에 저장을 하게됩니다.  -->

<?php

$servername = "localhost";

/* mysql database 의 username , 비밀번호를 입력한다 */
$username = "dbadmin";
$password = "quix2012";
/*
if( isset($_GET['dust1_0']) && isset($_GET['dust2_5']) && isset($_GET['dust10_0']))
{
$d010=$_GET['dust1_0'];
$d025=$_GET['dust2_5'];
$d100=$_GET['dust10_0'];
*/

$db_name = "get";
if( isset($_GET['temperature']))
 {
	$d010=$_GET['temperature'];

// Create connection
// $mysqli = mysqli_connect(localhost, user name, 비밀번호, db name ,db port); => 본인의 내용을 입력한다.
	$mysqli = mysqli_connect("localhost","dbadmin","quix2012","get");

// Check connection
	if ($mysqli->connect_errno) {
//		die("Connection failed: " . $mysqli->connect_error);
	}

	$query = "insert into db_temp (dt_create, temperature)
                      values(now(),'$d010')";
	if (!$mysqli->query($query))
	{
//		printf("Error: %s\n", $mysqli->error);
	}
	$mysqli->close();
//	echo "Success ".time();

}

?>

<!-- 조회구간 선택     -->

<div class="container">
	<div class="container-box">
		<div class="main-content">
			<form action="" method="post">
			<input type="text" name="datepicker1" id="datepicker1"> ~
			<input type="text" name="datepicker2" id="datepicker2">
			<input type="submit" value="조회하기">
			</form>
		</div>
	</div>
</div>


<!-- 그래프로 보기 -->

<?php
$id=$_POST['datepicker1'];
$id2=$_POST['datepicker2'];

/**************************************************/
/* mysql 연결테스트 ******************************/
/**************************************************/

$link = mysqli_connect("localhost", "dbadmin", "quix2012", "get");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
mysqli_close($link);

echo "<br>";

/**************************************************/

$conn=mysqli_connect("localhost","dbadmin","quix2012","get");
$sql="select * from (SELECT dt_create, temperature
FROM `db_temp`
order by dt_create desc
limit 100000
)t_a
order by t_a.dt_create";

$result = mysqli_query($conn,$sql) ;
$rows=array();
$flag=true;
$table=array();
$table['cols']=array(
//array('lable'=>'id' , 'type'=>'number'),
array('lable'=>'dt_create','type'=>'string'),
array('temperature'=>'temperature','type'=>'number')
);

$rows=array();
$date0 = new DateTime($id);
$date2 = new DateTime($id2);

while($r=mysqli_fetch_assoc($result)){

	$date1 = new DateTime($r['dt_create']);
	if ($date1>$date0 && $date1<$date2) {
	$temp=array();
//	$temp[]=array('v'=>(int) $r['id']);
	$temp[]=array('v'=>(string) $r['dt_create']);
	$temp[]=array('v'=>(int) $r['temperature']);
	$rows[]=array('c'=>$temp);

	echo($r['dt_create']."__[temperature: ".$r['temperature']."]<br>");

}
}

$table['rows']=$rows;
$jsonTable=json_encode($table);


?>


<!-- 여기부터는 자바 스크립트 입니다. jsonTable의 data를 구글 차트에 전달해서 그래프를 그리도록 합니다. -->
<div class="container">
	<div class="container-box">
		<div class="main-content">
			<script src="//www.google.com/jsapi"></script>
			<script src="//www.gstatic.com/charts/loader.js"></script>
			<script>
				google.charts.load('43', {'packages':['corechart']});
				google.charts.setOnLoadCallback(drawChart);
				function drawChart() {
				var data = new google.visualization.DataTable(<?=$jsonTable?>);
				var options = {
				title: ' 현재시간까지의 측정결과 입니다.',
				curveType: 'function',
				legend: { position: 'bottom' }
				};
				var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
				chart.draw(data, options);
				}
			</script>

			<div id="curve_chart" style="width:1500px; height: 600px"></div>
		</div>
	</div>
</div>


<?php get_footer(); ?>


