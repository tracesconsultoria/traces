<?php
session_start();

if(!isset($_COOKIE['session'])){
	setcookie("session",session_id(),time()+31536000);
	$session = session_id();
}else{
	$session = $_COOKIE['session'];
}

$servername = "localhost";
$username = "root";
$password = "Traces2018@@";
$dbname = "carbono";

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
#$file = 'test.log';
#$current = file_get_contents($file);

$sql = "REPLACE INTO user 
(
	cookie,
	".(!empty($_GET['key']) ? '`key`':'').",
	".(!empty($_GET['phone']) ? 'phone':'').",
	".(!empty($_GET['email']) ? 'email':'').", 
	update_time
) VALUES (
	'".$session."', 
	'".(!empty($_GET['key']) ? $_GET['key']:'')."', 
	'".(!empty($_GET['phone']) ? $_GET['phone']:'')."', 
	'".(!empty($_GET['email']) ? $_GET['email']:'')."', 
	now()
)";
$conn->query($sql);
//$current .= $sql;

foreach($_GET as $key => $value){
	if(!in_array($key, array('key','name','phone','email'))){
		$sql = "INSERT INTO traffic 
		(
			cookie,
			`key`,
			field,
			value,
			update_time
		)
		VALUES
		(
			'".$session."',
			'".(!empty($_GET['key']) ? $_GET['key']:'')."',
			'{$key}',
			'{$value}',
			now()
		) ";
		//$current .= $sql;

		$conn->query($sql);
	}	

}

mysqli_close($conn);

/*
$current .= "\n\n\n SESSION > ".$_COOKIE['session']."\n";
foreach($_POST as $key => $value){
	$current .= "POST > $key = $value\n";
}

foreach($_GET as $key => $value){
	$current .= "GET > $key = $value\n";
}
print $current;
file_put_contents($file, $current);
*/
?>