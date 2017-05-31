<?php
	session_start();

	function input($val)
	{
		$val = str_replace("<",'&lt;',$val);
		$val = str_replace(">",'&gt;',$val);
		$val = str_replace('"','&quot;',$val);
		$val = str_replace('"','&ldquo;',$val);
		$val = str_replace('"','&rdquo;',$val);
		$val = str_replace("'",'&lsquo;',$val);
		$val = str_replace("'",'&rsquo;',$val);
		return $val;
	}

	function checkaccount($member_account){
	//建立資料庫連線資料
		//$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
		global $connection;
		if (mysqli_connect_errno())
		{
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$MEMBER_ACCOUNT = $member_account;

		$sql = "SELECT MEMBER_ACCOUNT FROM MEMBER WHERE MEMBER_ACCOUNT LIKE '".$MEMBER_ACCOUNT."'";
		$result = mysqli_query($connection,$sql);
		if(mysqli_num_rows($result) == 0){
			//echo 是 Ajax 要抓的資料，我前台的 js 要用
			echo "0";
			return false;
		}else{
			//echo 是 Ajax 要抓的資料，我前台的 js 要用
			echo "1";
			return true;
		}
	}
	//建立資料庫連線資料
	$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
	mysqli_query($connection,"SET NAMES utf8");
	$MEMBER_ACCOUNT = $_POST['REGISTER_ACCOUNT'];

	if(!checkAccount($MEMBER_ACCOUNT)){
		$MEMBER_PASSWORD = $_POST['REGISTER_PASSWORD'];
		$MEMBER_NAME = addslashes($_POST['REGISTER_NAME']);
		$MEMBER_BIRTHDAY = addslashes($_POST['REGISTER_BIRTHDAY']);
		$MEMBER_EMAIL = addslashes($_POST['REGISTER_EMAIL']);
		$MEMBER_PHONE_NUM = addslashes($_POST['REGISTER_PHONE_NUM']);
		$MEMBER_GENDER = addslashes($_POST['REGISTER_GENDER']);
		$MEMBER_JOB = addslashes($_POST['REGISTER_JOB']);
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
		$MEMBER_ACCOUNT = input($MEMBER_ACCOUNT);
		$MEMBER_PASSWORD = input($MEMBER_PASSWORD);
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
