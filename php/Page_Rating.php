<?php
	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	$id = $_GET['member_id'];
	$vid = $_GET['video_id'];
	if(isset($_GET['rating'])){	
		$sql = "Select `RATE` From rating Where member_id = '".$id."' And VIDEO_ID = '".$vid."'";
    	$result = $conn -> query($sql);
    	$result = $result -> fetchAll();
		$count = 0;
		foreach($result as $row){
			$count++;
		}
		if($count>0){
			date_default_timezone_set('Asia/Taipei');
			$time = date("Y-m-d H:i:s");
			$sql = "UPDATE `rating` SET `RATE`='".$_GET['rating']."',`DATE`='".$time."' WHERE member_id = '".$id."' And VIDEO_ID = '".$vid."'";
			$result = $conn -> exec($sql);
			$conn = null;
			echo $result;
		}else{
			$sql = "INSERT INTO `rating`(`MEMBER_ID`, `VIDEO_ID`, `RATE`) VALUES ('".$id."','".$vid."','".$_GET['rating']."')";
			$result = $conn -> exec($sql);
			$conn = null;
			echo $result;
		}
	}else {
		$sql = "Select `RATE` From rating Where member_id = '".$id."' And VIDEO_ID = '".$vid."'";
    	$result = $conn -> query($sql);
    	$result = $result -> fetchAll();
        $conn = null;
		$count = 0;
		foreach($result as $row){
			$count++;
		}
		if($count > 0){
			echo json_encode($result[0]);
		}else{
			echo json_encode($result);
		}
	}
?>