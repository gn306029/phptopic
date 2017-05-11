<?php
	session_start();
	include './php/Page_Search_Set.php';
	$login_form = "<form name='memberlogin' action='php/Member_Login.php' method='POST'>";
	$login_form .= "<img src=\"PIC/top/account.png\" width=\"70px\" />";
	$login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
	$login_form .= "<img src=\"PIC/top/password.png\" width=\"70px\" />";
	$login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
?>

<!DOCTYPE html>
<html>
	<head>
		<title>IMDB</title>
		<link type="text/css" rel="stylesheet" href="./css/index.css">
	</head>
	<body>
		<div id="main">
			<div id="header">
				<table>
					<tr>
						<td id="logo">
							<a href="index.php"><img src="PIC/top/logo.png" width="200px"></a>
						</td>
						<td id="search">
							<form name="search" action="php/Page_SearchList.php" method="GET">
								<input type="text" name="search" />
								<select name="kind">
								<?php
									echo $kind;
								?>
								</select>
								<select name="category">
								<?php
									echo $category;
								?>
								</select>
								<img src="PIC/top/searchbutton.png" onclick="document.search.submit()" width="42px">
							</form>
						</td>
						<!--帳號密碼-->
						<td id="memberlogin">
							<?php
								//判斷登入狀態
								if(isset($_SESSION["username"])){
									echo $_SESSION["username"]."</br>您好";
									echo "<a href='./php/Member_Manager.php'>會員中心</a>";
								}else{
									echo $login_form;   
								}
							?>
						</td>
						<!--註冊-->
						<td id="memberlogin2">
							<?php
								//判斷登入狀態
								if(isset($_SESSION["username"])){
									echo "<a href='./php/Member_Logout.php'/>登出";
								}else{
									echo "<a href=\"./php/Member_Register.php\"><img src=\"./PIC/top/register.png\" width=\"70px\"></a><br>";
									echo "<img src=\"./PIC/top/login.png\" onclick=\"document.memberlogin.submit()\" width=\"70px\"><br>";    
								}
							?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="center">
							<a href="php/Page_Movie.php" onMouseOut="document.movie.src='PIC/top/movie.png'" onMouseOver="document.movie.src='PIC/top/movie-1.png'"><img src="PIC/top/movie.png" name="movie" width="70px"></a>　
							<a href="php/Page_Drama.php" onMouseOut="document.drama.src='PIC/top/drama.png'" onMouseOver="document.drama.src='PIC/top/drama-1.png'"><img src="PIC/top/drama.png" name="drama" width="70px"></a>　
							<a href="php/Page_Tvshow.php" onMouseOut="document.tvshow.src='PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='PIC/top/tvshow-1.png'"><img src="PIC/top/tvshow.png" name="tvshow" width="70px"></a>　
							<a href="php/Page_Actor.php" onMouseOut="document.actor.src='PIC/top/actor.png'" onMouseOver="document.actor.src='PIC/top/actor-1.png'"><img src="PIC/top/actor.png" name="actor" width="70px"></a>
						</td>
						<td></td>
						<td>
						<?php
							//判斷登入狀態
							if(!isset($_SESSION["username"])){
								echo "<a href=\"php/Member_Forget.php\"><img src=\"PIC/top/forget.png\" width=\"130px\" /></a>";
							}
						?>	
						</td>
					</tr>
				</table>
			</div>
			<div id="context">
				
			</div>
		</div>
	</body>
</html>