<?php

	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	//Select
	$select = $conn->query("Select * From kind");
	//讀出所有資料
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
?>