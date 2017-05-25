<?php
	session_start();
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    function sql_exec($sql){
        global $dsn,$db_user,$db_password;
        $conn = new PDO($dsn,$db_user,$db_password);
        $result = $conn -> exec($sql);
        $conn = null;
        return $result;
    }
    function sql_select($sql){
        global $conn;
        $result = $conn -> query($sql);
        $result = $result -> fetchAll();
        $conn = null;
        return $result;
    }
    
	/*
	 * 取得使用者的最愛
	 *
	 */
	$sql = "Select `video`.`VIDEO_NAME` From `video` Join `favorite` On `video`.`VIDEO_ID` = `favorite`.`VIDEO_ID` Where `MEMBER_ID` = '".$_SESSION['userid']."' AND `video`.`VIDEO_ID`='".$_POST['videoid']."'";
	$result = sql_select($sql);
	$rs = json_encode($result);
	if($result==null){
		$sql = "INSERT INTO `favorite` (`MEMBER_ID`, `VIDEO_ID`, `FAVORITE_ID`) VALUES ('".$_SESSION['userid']."','".$_POST['videoid']."',null)";
		$result =sql_exec($sql);
		echo "1";
	}else{
		$sql = "DELETE FROM `favorite` WHERE `MEMBER_ID`='".$_SESSION['userid']."' AND `VIDEO_ID`='".$_POST['videoid']."'";
		$result =sql_exec($sql);
		echo "0";
	}
	
?>