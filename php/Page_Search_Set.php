<?php

	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	$select = $conn->query("Select * From kind");
	$result = $select -> fetchAll();
	$kind = "<option value='0' selected='selected'>-----</option>";
	foreach($result as $row){
		$kind .= "<option value='".$row['KIND_ID']."'>".$row['KIND_NAME']."</option>";
	}
	$select = $conn->query("Select * From category");
	$Allcategory = $select -> fetchAll();
	$category = "<option value='0' selected='selected'>-----</option>";
	foreach ($Allcategory as $row) {
		$category .= "<option value='".$row['CATEGORY_ID']."'>".$row['CATEGORY_NAME']."</option>";
	}
	$conn = null;
	/*
	 * 帳號與密碼的輸入框
	 *
	 */
    $login_form = "<form name='memberlogin' action='./Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
    $login_form .= "</form>";
?>