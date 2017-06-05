<?php
	session_start();
	include './php/Page_Search_Set.php';
	$login_form = "<form name='memberlogin' action='./php/Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"./PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"./PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
    $login_form .= "</form>";
?>

<?php
	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);

	$sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '1' ORDER BY `SCORE` DESC LIMIT 5";

	$result = $conn -> query($sql);
	$conn = null;
	$result = $result -> fetchAll();
	$rank = "<table id='top_list'>";
	$rank .= "<tr><td width=10%>排名</td><td width=10%></td><td width=70%>影片名稱</td><td width=10%>分數</td></tr>";
	$index = 1;
	foreach($result as $row){
		$rank .= "<tr>";
		$rank .= "<td width=10%>".$index."</td>";
		$rank .= "<td width=10%><div class='video_photo'><img src='".$row['PHOTO']."'></div></td>";
		$rank .= "<td width=70%><a href='./php/Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</td>";
		$rank .= "<td id='ratings' width=10%>".$row['SCORE']."</td>";
		$rank .= "</tr>";
		$index ++;
	}
	$rank .= "</table>"

?>

<!DOCTYPE html>
<html>
	<head>
		<title>IMDB</title>
		<link type="text/css" rel="stylesheet" href="./css/common.css">
		<link type="text/css" rel="stylesheet" href="./css/index.css">
		<link href="./js/js-image-slider.css" rel="stylesheet" />
    		<script src="./js/js-image-slider.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script type="text/javascript">
			function ajax(action,rate){
				$.ajax({
				url:"./php/Member_Information_Set.php",
					data:{
						action:action,
						rate:rate
					},
					type:"post",
					success:function(output){
						var result = JSON.parse(output);
						var rank = "<tr><td width=10%>排名</td><td width=10%></td><td width=70%>影片名稱</td><td>分數</td></tr>";
							for(var i =0;i<Object.keys(result).length;i++){
							rank += "<tr>";
							rank += "<td width=10%>"+(i+1)+"</td>";
							rank += "<td width=10%><div class='video_photo'><img src='"+result[i]['PHOTO']+"'></div></td>";
							rank += "<td width=70%><a href='./php/Page_Video.php?VIDEO_ID="+result[i]['VIDEO_ID']+"'>"+result[i]['VIDEO_NAME']+"</a></td>";
							rank += "<td id='ratings' width=10%>"+result[i]['SCORE']+"</td>";
							rank += "</tr>";
						}
						$("#top_list").html(rank);
					},
					error: function (request, status, error) {
						$("#error_log").html(request.responseText);
					}
				})
			}
			$(function(){
				/*
				 * 滑鼠在該標題上的事件
				 *
				 */
				$(".video_title").mouseover(function(event){
					$(this).css("background-color","black").css("color","LightSkyBlue");
					if($(this).text() == "TOP5 電影"){
						ajax("rate_movie_top5","DESC")
					}else if($(this).text() == "TOP5 戲劇"){
						ajax("rate_drama_top5","DESC")
					}else if($(this).text() == "TOP5 綜藝節目"){
						ajax("rate_tvshow_top5","DESC")
					}else if($(this).text() == "糞電影"){
						ajax("rate_movie_top5","ASC")
					}else if($(this).text() == "狗血劇"){
						ajax("rate_drama_top5","ASC")
					}else if($(this).text() == "大學生了沒"){
						ajax("rate_tvshow_top5","ASC")
					}				
				})
				/*
				 * 滑鼠離開標題時的事件
				 *
				 */
				$(".video_title").mouseout(function(event){
					$(this).css("background-color","white").css("color","black");
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
                                echo $_SESSION["username"].",您好<br>";
								echo " <a href=./php/Member_Manager.php><img src=./PIC/top/manager-1.png name=manager width=150px></a>　　";
								echo "<a href='./php/Member_Logout.php'/><img src=\"./PIC/top/logout.png\" width=\"70px\"></a>";
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
                                echo "<a href=\"./php/Member_Register.php\"><img src=\"./PIC/top/register.png\" width=\"70px\"></a><br>";
                                echo "<img src=\"./PIC/top/login.png\" onclick=\"document.memberlogin.submit()\" width=\"70px\"><br>";    
                            }
                        ?>
                    </td>
					</tr>
					<tr>
						<td></td>
						<td align="center">
							<a href="php/Page_SearchList.php?search=&kind=1&category=0" onMouseOut="document.movie.src='PIC/top/movie.png'" onMouseOver="document.movie.src='PIC/top/movie-1.png'"><img src="PIC/top/movie.png" name="movie" width="70px"></a> 
							<a href="php/Page_SearchList.php?search=&kind=3&category=0" onMouseOut="document.drama.src='PIC/top/drama.png'" onMouseOver="document.drama.src='PIC/top/drama-1.png'"><img src="PIC/top/drama.png" name="drama" width="70px"></a> 
							<a href="php/Page_SearchList.php?search=&kind=2&category=0" onMouseOut="document.tvshow.src='PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='PIC/top/tvshow-1.png'"><img src="PIC/top/tvshow.png" name="tvshow" width="70px"></a> 
							<a href="php/Page_ActorList.php" onMouseOut="document.actor.src='PIC/top/actor.png'" onMouseOver="document.actor.src='PIC/top/actor-1.png'"><img src="PIC/top/actor.png" name="actor" width="70px"></a>
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
				<div id="sliderFrame">
					<div id="slider">
						<?php
							$dir = './PIC/tmp_photo';
								if($dh = opendir($dir)){
									while(($file=readdir($dh))!==false){
										if($file!='..' && $file!='.'){
									       $file=iconv("BIG5", "UTF-8",$file); //必要,否則中文會亂碼
									       echo pathinfo($file, PATHINFO_DIRNAME).pathinfo($file, PATHINFO_FILENAME );
									   }
										
									}
								}
						?>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/01.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/02.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/03.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/04.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/05.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/06.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/07.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/08.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/09.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="./PIC/banner/10.jpg"/></a>
					</div>
				</div>
				<div id='rank'>
					<table><tr><td class="video_title">TOP5 電影</td><td class="video_title">TOP5 戲劇</td><td class="video_title">TOP5 綜藝節目</td><td class="video_title">糞電影</td><td class="video_title">狗血劇</td><td class="video_title">大學生了沒</td></tr></table>
					<?php
						echo $rank;
					?>
				</div>
				
			</div>
			<footer><table><tr>
				<td><a href="./php/About.php?action=Me"><img height="36" border="0" alter="關於" src="./PIC/footer/about.png"></a></td>
				<td><a href="./php/About.php?action=Dev"><img height="36" border="0" alter="開發人員" src="./PIC/footer/dev.png"></a></td>
				<td><div><a href="https://line.me/R/ti/p/%40gib2079k"><img height="36" border="0" alt="加入好友" src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png"></a></div></td>
				
			</tr>
			<tr>
				<td colspan=3>© 2017 IMDB,KUASMIS</td>
			</tr></table></footer>
		</div>
	</body>
</html>