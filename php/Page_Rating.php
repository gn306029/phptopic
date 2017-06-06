<?php
	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	if(isset($_GET['rating'],$_GET['member_id'],$_GET['video_id'])){	
		$sql = "Select `RATE` From rating Where member_id = '".$_GET['member_id']."' And VIDEO_ID = '".$_GET['video_id']."'";
    	$result = $conn -> query($sql);
    	$result = $result -> fetchAll();
		$count = 0;
		foreach($result as $row){
			$count++;
		}
		if($count>0){
			date_default_timezone_set('Asia/Taipei');
			$time = date("Y-m-d H:i:s");
			$sql = "UPDATE `rating` SET `RATE`='".$_GET['rating']."',`DATE`='".$time."' WHERE member_id = '".$_GET['member_id']."' And VIDEO_ID = '".$_GET['video_id']."'";
			$result = $conn -> exec($sql);
			$sql = "UPDATE `video` SET `SCORE`=(select avg(rate) from rating where VIDEO_ID = '".$_GET['video_id']."') WHERE VIDEO_ID = '".$_GET['video_id']."'";
			$result = $conn -> exec($sql);
			$sql = "SELECT `SCORE` FROM `video` WHERE VIDEO_ID = '".$_GET['video_id']."'";
			$result = $conn -> query($sql);
			$conn = null;
			$result = $result -> fetchAll();
			echo json_encode($result);
		}else{
			$sql = "INSERT INTO `rating`(`MEMBER_ID`, `VIDEO_ID`, `RATE`) VALUES ('".$_GET['member_id']."','".$_GET['video_id']."','".$_GET['rating']."')"; 
			$result = $conn -> exec($sql);
			$sql = "UPDATE `video` SET `SCORE`=(select avg(rate) from rating where VIDEO_ID = '".$_GET['video_id']."') WHERE VIDEO_ID = '".$_GET['video_id']."'";
			$result = $conn -> exec($sql);
			$sql = "SELECT `SCORE` FROM `video` WHERE VIDEO_ID = '".$_GET['video_id']."'";
			$result = $conn -> query($sql);
			$conn = null;
			$result = $result -> fetchAll();
			echo json_encode($result);
		}
	}else if(isset($_GET['member_id'],$_GET['video_id'])){
		$sql = "Select `RATE` From rating Where member_id = '".$_GET['member_id']."' And VIDEO_ID = '".$_GET['video_id']."'";
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