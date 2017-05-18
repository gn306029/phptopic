<?php
	/*
	 * 登陸成功就設定session 並重新導向首頁
	 *
	 */
	session_start();
	unset($_SESSION['username']);
	unset($_SESSION['userid']);
	header("Location: ../index.php");
	echo "<script>alert('已登出');</script>";
?>