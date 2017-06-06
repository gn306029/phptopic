<?php
	session_start();
	$connection = mysqli_connect("db.mis.kuas.edu.tw","s1104137130","1314520","s1104137130");
	if(isset($_POST['MEMBER_ACCOUNT'],$_POST['MEMBER_PASSWORD'])){
		$MEMber_account = addslashes($_POST['MEMBER_ACCOUNT']);
		$MEMber_password = addslashes($_POST['MEMBER_PASSWORD']);

		$sql = "SELECT MEMBER_ID,MEMBER_ACCOUNT,MEMBER_PASSWORD, MEMBER_NAME ,MEMBER_LEVEL FROM `MEMBER` WHERE MEMBER_ACCOUNT LIKE '".$MEMber_account."' AND MEMBER_PASSWORD LIKE '".$MEMber_password."'";
		mysqli_query ($connection,"SET NAMES UTF8"); 
		$result = mysqli_query($connection,$sql);
		$data = mysqli_fetch_array($result);
	
		if(mysqli_num_rows($result)==1){
			$_SESSION["username"] = $data[3];
			$_SESSION["userid"] = $data[0];
			$_SESSION["level"] = $data[4];
			setcookie("username",$data[3],time()+3600);
			header("Location: ./index.php");
		}else{
			echo "<script>alert('登入失敗');history.go(-1);</script>";
		}
	}
	mysqli_close($connection);

?>