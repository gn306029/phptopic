<?php
	//引入sql.php檔案
	include './sql.php';
	//啟用 session
	session_start();

	//建立資料庫連線資料
	$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
	mysqli_query($connection,"SET NAMES utf8");
	$MEMBER_ACCOUNT = $_POST['REGISTER_ACCOUNT'];

	if(!checkAccount($MEMBER_ACCOUNT)){
		$MEMBER_PASSWORD = $_POST['REGISTER_PASSWORD'];
		$MEMBER_NAME = $_POST['REGISTER_NAME'];
		$MEMBER_BIRTHDAY = $_POST['REGISTER_BIRTHDAY'];
		$MEMBER_EMAIL = $_POST['REGISTER_EMAIL'];
		$MEMBER_PHONE_NUM = $_POST['REGISTER_PHONE_NUM'];
		$MEMBER_GENDER = $_POST['REGISTER_GENDER'];
		$MEMBER_JOB = $_POST['REGISTER_JOB'];
		$CATEGORY = implode(",",$_POST['REGISTER_CATEGORY']);
		$sql = 
			"
			INSERT INTO `MEMBER`(
			`MEMBER_ID`,`MEMBER_NAME`,`MEMBER_ACCOUNT`,`MEMBER_PASSWORD`,`MEMBER_BIRTHDAY`,
			`MEMBER_EMAIL`,`MEMBER_PHONE_NUM`,`MEMBER_GENDER`,`MEMBER_JOB`,`CATEGORY`,`MEMBER_LEVEL`
			) 
			VALUES (
			null,'".$MEMBER_NAME."','".$MEMBER_ACCOUNT."','".$MEMBER_PASSWORD."','".$MEMBER_BIRTHDAY."','"
			.$MEMBER_EMAIL."','".$MEMBER_PHONE_NUM."','".$MEMBER_GENDER."','".$MEMBER_JOB."','".$CATEGORY."','普通')
			";
		$mq = mysqli_query($connection,$sql);
		mysqli_close($connection);
		//判斷 Insert 結果是成功還失敗
		if($mq){
			echo "Insert成功";
		}else{
			echo "Insert失敗";
		}
	}else{
		echo "Insert失敗";
	}


?>
