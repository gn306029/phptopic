<?php

	header('Content-type:text/html;charset=utf-8');
	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137110';
	$db_user = 's1104137110';
	$db_password = 'gn306029';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	//Select
	$select = $conn->query("Select * From phptest");
	//Update Insert Delete
	//$other = $conn->exec("Update `phptest` set `password`='123'");
	$insert = $conn->exec("INSERT INTO `phptest`(`username`, `password`) VALUES ('hello','world')");
	//讀出所有資料
	$result = $select -> fetchAll();
	foreach($result as $row){
		echo $row['username']." ".$row['password']."</br>";
	}
	/*$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
	mysqli_query($connection,"SET NAMES utf8");
	$sql = "SELECT * FROM `MEMBER` ";
	$result = mysqli_query($connection,$sql);
	while($row = mysqli_fetch_assoc($result))
        {
            echo $row['MEMBER_ID']." ".$row['MEMBER_NAME']." ".$row['MEMBER_ACCOUNT']." ".$row['MEMBER_PASSWORD']." ".$row['MEMBER_BIRTHDAY']."</br>";
        }
    */
?>