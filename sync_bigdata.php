<?php

$servername = "54.233.199.157";
$username = "root";
$password = "Traces2018@@";
$dbname = "carbono";
$userBigData = 'danny@carbonno.com.br';
$passBigData = 'ybeclqfr';


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$select_list_user_to_update = $conn->query("SELECT * FROM user where `key` is not null and (phone is null or email is null or name is null or date_birth is null) and (date_sync_bigdata <= DATE_FORMAT( CURRENT_DATE - INTERVAL 1 MONTH, '%Y/%m/%d' ) or date_sync_bigdata is null)");

//print_r($select_list_user_to_update);
if($select_list_user_to_update->num_rows > 0){
	while ($row = mysqli_fetch_array($select_list_user_to_update, MYSQLI_ASSOC)){
		//print $row['key'];
		$url = "http://bigboost.bigdatacorp.com.br/API/Query?USERNAME=".$userBigData."&PASSWORD=".$passBigData."&SOURCE=BOOKPF&SEARCHKEY=DOC=".$row['key'];
		//$url = "http://bigboost.bigdatacorp.com.br/API/Query?USERNAME={$userBigData}&PASSWORD={$passBigData}&SOURCE=BOOKPF&SEARCHKEY=DOC=31766756824";
		//print_r($url);
		//print_r(json_decode(file_get_contents($url), true));
		$result = json_decode(file_get_contents($url), true);
		$res = json_decode($result['OperationResult'], true);
		$array_phone = $res['Entities'][1]['People'][0]['Contacts'][0]['Phone'];
		$phone = !empty($array_phone['Number']) ? ", phone='".$array_phone['CountryCode'].' '.$array_phone['AreaCode'].' '.$array_phone['Number'].' '.$array_phone['Complement']."'" : '';
		
		$email = !empty($res['Entities'][1]['People'][0]['Emails'][0]) ? ", email='".$res['Entities'][1]['People'][0]['Emails'][0]."'" : '';

		$conn->query("UPDATE user set date_sync_bigdata=now() $phone $email where `key`='".$row['key']."'");


			
	}
}
mysqli_close($conn);

?>