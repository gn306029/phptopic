<?php
	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	$sql = "INSERT INTO `favorite` (`MEMBER_ID`, `VIDEO_ID`, `FAVORITE_ID`) VALUES ('".$_POST['memberid']."','".$_POST['videoid']."',null)";
	$result = $conn -> exec($sql);
	$conn = null;
	echo $result;
?>