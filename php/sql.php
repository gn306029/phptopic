<?php

	if(isset($_POST['action']) && !empty($_POST['action'])) {
	    $action = $_POST['action'];
	    switch($action) {
	        case 'checkaccount' : 
	        	checkaccount($_POST['account']);
	        	break;
	    }
	}

	function checkaccount($member_account){
	//建立資料庫連線資料
		$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
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

	
?>
