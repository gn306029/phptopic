<!DOCTYPE html>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="../css/common.css" />
	<?php
		include './Page_Search_Set.php';
		$login_form = "<form name='memberlogin' action='php/Member_Login.php' method='POST'>";
		$login_form .= "<img src=\"PIC/top/account.png\" width=\"70px\" />";
		$login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
		$login_form .= "<img src=\"PIC/top/password.png\" width=\"70px\" />";
		$login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
		$login_form .= "</form>";
		if($_GET['action'] == "Me"){
			echo "<title>關於我們</title>";
		}else if($_GET['action'] == "Dev"){
			echo "<title>開發人員</title>";
		}else{
			echo "<title>Not Found</title>";
		}
	?>
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
		<div id="context">
			<?php
				if($_GET['action'] == "Me"){
					echo "<table>";
					echo "<tr><td>這是個網站</td></tr>";
					echo "<tr><td>用來查電影</td></tr>";
					echo "<tr><td>跟用來評分</td></tr>";
					echo "<tr><td>大Guy4這樣</td></tr>";
					echo "</table>";
				}else if($_GET['action'] == "Dev"){
					echo "<table>";
					echo "<tr><td><a href='https://www.facebook.com/ruyutiffany'>黃汝淯</a></td><td>組長</td><td>美編</td></tr>";
					echo "<tr><td><a href='https://www.facebook.com/profile.php?id=100001256308475'>劉志營</a></td><td>組員</td><td>資料處理</td></tr>";
					echo "<tr><td><a href='https://www.facebook.com/profile.php?id=100001723677181'>林奕儒</a></td><td>組員</td><td>功能</td></tr>";
					echo "<tr><td><a href='https://www.facebook.com/a9331109'>范廷維</a></td><td>組員</td><td>美編</td></tr>";
					echo "<tr><td><a href='https://www.facebook.com/linzheguang'>林哲廣</a></td><td>組員</td><td>評分</td></tr>";
					echo "</table>";
				}
				?>
		</div>
		<footer><table><tr>
			<td><a href="../php/About.php?action=Me">關於</a></td>
			<td><a href="../php/About.php?action=Dev">開發人員</a></td>
			<td><div><a href="https://line.me/R/ti/p/%40gib2079k"><img height="36" border="0" alt="加入好友" src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png"></a></div></td>
			
		</tr>
		<tr>
			<td colspan=3>© 2017 YouTube, LLC</td>
		</tr></table></footer>
		</div>
</body>
</html>