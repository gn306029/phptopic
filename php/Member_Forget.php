<?php
	session_start();
    //下拉式清單用
    include './Page_Search_Set.php';
    $login_form = "<form name='memberlogin' action='./Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
?>
<!DOCTYPE html>
<html>
<head>
	<title>忘記密碼</title>
</head>
<body>
	<form action='./Member_Forget_Set.php' method="POST">
		輸入帳號：<input type='text' name='username' /></br>
		輸入電子郵件：<input type='text' name='email' /></br>
		<input type='submit' value='送出' />
	</form>
</body>
</html>