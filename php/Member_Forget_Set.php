<?php
    session_start();
    //下拉式清單用
    include './Page_Search_Set.php';
    $login_form = "<form name='memberlogin' action='./Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
    $login_form .= "</form>";
?>


<!DOCTYPE html>
<html>

	<head>
		<title>忘記密碼</title>
		<link type="text/css" rel="stylesheet" href="../css/common.css">
		<link type="text/css" rel="stylesheet" href="../css/video.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script type="text/javascript">
			$(window).load(function(){
				var $win = $(window),
					$ad = $('#line').css('opacity', 0).show(),	// 讓廣告區塊變透明且顯示出來
					_width = $ad.width(),
					_height = $ad.height(),
					_diffY = 20, _diffX = 20,	// 距離右及下方邊距
					_moveSpeed = 300;	// 移動的速度
			 
				// 先把 #line 移動到定點
				$ad.css({
					top: $(document).height(),
					left: $win.width() - _width - _diffX,
					opacity: 1
				});
			 
				// 幫網頁加上 scroll 及 resize 事件
				$win.bind('scroll resize', function(){
					var $this = $(this);
			 
					// 控制 #line 的移動
					$ad.stop().animate({
						top: $this.scrollTop() + $this.height() - _height - _diffY,
						left: $this.scrollLeft() + $this.width() - _width - _diffX
					}, _moveSpeed);
				}).scroll();	// 觸發一次 scroll()
			});
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
							<a href="Page_Movie.php?search=&kind=1&category=0" onMouseOut="document.movie.src='../PIC/top/movie.png'" onMouseOver="document.movie.src='../PIC/top/movie-1.png'"><img src="../PIC/top/movie.png" name="movie" width="70px"></a> 
							<a href="Page_Drama.php?search=&kind=3&category=0" onMouseOut="document.drama.src='../PIC/top/drama.png'" onMouseOver="document.drama.src='../PIC/top/drama-1.png'"><img src="../PIC/top/drama.png" name="drama" width="70px"></a> 
							<a href="Page_Tvshow.php?search=&kind=2&category=0" onMouseOut="document.tvshow.src='../PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='../PIC/top/tvshow-1.png'"><img src="../PIC/top/tvshow.png" name="tvshow" width="70px"></a> 
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
				<?php
					/*
					 * 引入寄信的 Php 檔案
					 *
					 */
					include './Member_SendMail.php';
					$result = search_account_result($_POST['username'],$_POST['email']);
					/*
					 * 依使用者輸入的帳號與密碼來做查詢
					 * 若是有撈到資料 代表輸入正確
					 * 否則回傳資料不符
					 *
					 */
					function search_account_result($account,$email){
						$db_host = 'db.mis.kuas.edu.tw';
						$db_name = 's1104137130';
						$db_user = 's1104137130';
						$db_password = '1314520';
						$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
						$conn = new PDO($dsn,$db_user,$db_password);
						$sql = "Select `MEMBER_NAME`,`MEMBER_EMAIL`,`MEMBER_PASSWORD` From `member` Where `MEMBER_ACCOUNT` = '".$account."' And `MEMBER_EMAIL` = '".$email."'";
						$result = $conn -> query($sql);
						return $result;
					}
					
					$data = $result -> fetchAll();
					if(count($data) == 1){
						try{
							$a = send_email($data[0]['MEMBER_NAME'],$data[0]['MEMBER_EMAIL'],"您的密碼","密碼是 ".$data[0]['MEMBER_PASSWORD']);
							echo "<script>alert('$a');history.go(-2);</script>";
						}catch(Exception $e){
							echo "<script>alert('寄信失敗，請尋求管理員');history.go(-1);</script>";
						}
						
					}else{
						echo "<script>alert('資料不符');history.go(-1);</script>";
					}
				?>
			</div>
		</div>
		<div id='line'><a href="https://line.me/R/ti/p/%40gib2079k"><img height="36" border="0" alt="加入好友" src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png"></a></div>
	</body>
</html>
