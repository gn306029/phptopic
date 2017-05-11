<?php
	session_start();
	$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
	$MEMber_account = $_POST['MEMBER_ACCOUNT'];
	$MEMber_password = $_POST['MEMBER_PASSWORD'];

	$sql = "SELECT MEMBER_ID,MEMBER_ACCOUNT,MEMBER_PASSWORD,MEMBER_NAME FROM `MEMBER` WHERE MEMBER_ACCOUNT LIKE '".$MEMber_account."' AND MEMBER_PASSWORD LIKE '".$MEMber_password."'";
	mysqli_query ($connection,"SET NAMES UTF8"); 
	$result = mysqli_query($connection,$sql);
	$data = mysqli_fetch_array($result);

	if(mysqli_num_rows($result)==1){
		$_SESSION["username"] = $data[3];
		$_SESSION["userid"] = $data[0];
		setcookie("username",$data[3],time()+3600);
		header("Location: ../index.php");
	}else{
		echo "No";
	}

	mysqli_close($connection);

?>