<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>忘記密碼</title>
</head>
<body>
	<form action='./forget_set.php' method="POST">
		輸入帳號：<input type='text' name='username' /></br>
		輸入電子郵件：<input type='text' name='email' /></br>
		<input type='submit' value='送出' />
	</form>
</body>
</html>