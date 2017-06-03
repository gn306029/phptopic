<?php
    session_start();
	/*
	 * 下拉清單用
	 *
	 */
    include './Page_Search_Set.php';
?>


<!DOCTYPE html>
<html>

	<head>
		<title>忘記密碼</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="../css/common.css">
		<link type="text/css" rel="stylesheet" href="../css/video.css">
		<link href="../js/jquery.loading.css" rel="stylesheet">	
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="../js/checkspecial.js"></script>
		<script src="../js/jquery.loading.js"></script>
		<script type="text/javascript">
			$(function(){
				var ischeck = [true,true];
				$("#username").change(function(){
					if(checkspecial($(this).val())){
						ischeck[0] = false;
					}else{
						ischeck[0] = true;
					}
				})
				$("#email").change(function(){
					var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
			        if (pattern.test($(this).val())) {
			            ischeck[1] = true;
			        } else {
			            ischeck[1] = false;
			        }
				})
				$("#send").click(function(){
					if(ischeck[0] && ischeck[1] && $("#username").val().length != 0 && $("#email").val().length != 0){
						$("body").loading({
						  stoppable: true
						});
						$.ajax({
							url:"./Member_Forget_Set.php",
							data:$("#data").serialize(),
							type:"post",
							success:function(output){
								setTimeout(function(){
									$("body").loading('stop');
									if(output=="err"){
										alert("寄信時出現錯誤，請洽管理員");
									}else if(output == "no data"){
										alert("資料有誤");
									}else if(output == "success"){
										alert("寄信成功");
									}
								},500)
							},
							error: function (request, status, error) {
			                   $("#error_log").html(request.responseText);
			            	}
						})
					}else{
						alert("請檢察表單輸入是否確實");
					}
				})
			})
 		</script>
	</head>

	<body>
		<div id="main">
			<div id="header">
				<table>
					<tr>
						<td id="logo">
							<a href="../index.php"><img src="../PIC/top/logo.png" width="200px"></a>
						</td>
						<!--搜尋列-->
						<td id="search">
							<form name="search" action="../php/Page_SearchList.php" method="GET">
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
								<img src="../PIC/top/searchbutton.png" onclick="document.search.submit()" width="42px"></form>
						</td>
						<!--帳號密碼-->
						<td id="memberlogin">
							<?php
								//判斷登入狀態
								if(isset($_SESSION["username"])){
									echo $_SESSION["username"].",您好<br>";
									echo " <a href=./Member_Manager.php><img src=../PIC/top/manager-1.png name=manager width=150px></a>　　";
									echo "<a href='./Member_Logout.php'/><img src=\"../PIC/top/logout.png\" width=\"70px\"></a>";
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
								   
								}else{
									echo "<a href=\"./Member_Register.php\"><img src=\"../PIC/top/register.png\" width=\"70px\"></a><br>";
									echo "<img src=\"../PIC/top/login.png\" onclick=\"document.memberlogin.submit()\" width=\"70px\"><br>";    
								}
							?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td align="center">
							<a href="Page_SearchList.php?search=&kind=1&category=0" onMouseOut="document.movie.src='../PIC/top/movie.png'" onMouseOver="document.movie.src='../PIC/top/movie-1.png'"><img src="../PIC/top/movie.png" name="movie" width="70px"></a> 
							<a href="Page_SearchList.php?search=&kind=3&category=0" onMouseOut="document.drama.src='../PIC/top/drama.png'" onMouseOver="document.drama.src='../PIC/top/drama-1.png'"><img src="../PIC/top/drama.png" name="drama" width="70px"></a> 
							<a href="Page_SearchList.php?search=&kind=2&category=0" onMouseOut="document.tvshow.src='../PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='../PIC/top/tvshow-1.png'"><img src="../PIC/top/tvshow.png" name="tvshow" width="70px"></a> 
							<a href="Page_ActorList.php" onMouseOut="document.actor.src='../PIC/top/actor.png'" onMouseOver="document.actor.src='../PIC/top/actor-1.png'"><img src="../PIC/top/actor.png" name="actor" width="70px"></a>
						</td>
						<td></td>
						<td>
							<?php
								//判斷登入狀態
								if(!isset($_SESSION["username"])){
									echo "<a href=\"./Member_Forget.php\"><img src=\"../PIC/top/forget.png\" width=\"130px\" /></a>";
								}
							?>
						</td>
					</tr>
				</table>
			</div>
			<br>
			<div id="context">
				<form id='data' method="POST">
					<p>帳　　號：<input type='text' name='username' id="username"/></p>
					<p>電子郵件：<input type='email' name='email' id="email"/></br></p>
					<input type='button' id='send' value='送出' />
				</form>
			</div>
			<footer><table><tr>
				<td><a href="./About.php?action=Me"><img height="36" border="0" alter="關於" src="../PIC/footer/about.png"></a></td>
				<td><a href="./About.php?action=Dev"><img height="36" border="0" alter="開發人員" src="../PIC/footer/dev.png"></a></td>
				<td><div><a href="https://line.me/R/ti/p/%40gib2079k"><img height="36" border="0" alt="加入好友" src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png"></a></div></td>
				
			</tr>
			<tr>
				<td colspan=3>© 2017 IMDB,KUASMIS</td>
			</tr></table></footer>
		</div>
	</body>
</html>