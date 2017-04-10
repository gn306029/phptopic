<?php

	$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
	$MEMber_account = $_POST['MEMBER_ACCOUNT'];
	$MEMber_password = $_POST['MEMBER_PASSWORD'];

	$sql = "SELECT MEMBER_ACCOUNT,MEMBER_PASSWORD FROM `MEMBER` WHERE MEMBER_ACCOUNT LIKE '".$MEMber_account."' AND MEMBER_PASSWORD LIKE '".$MEMber_password."'";
	$result = mysqli_query($connection,$sql);
	if(mysqli_num_rows($result)==1){
		echo "OK";
	}else{
		echo "No";
	}

	mysqli_close($connection);

?>