<?php
    session_start();
    //下拉式清單用
    include './searchDetail.php';
    $login_form = "<form name='memberlogin' action='./login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/index.css">
    <link type="text/css" rel="stylesheet" href="../css/video.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
                        <form name="search" action="../php/search.php" method="GET">
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
                                echo $_SESSION["username"]."</br>您好";
                                echo "<a href='./manager.php'>會員中心</a>";
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
                                echo "<a href='./logout.php'/>登出";
                            }else{
                                echo "<a href=\"./register.php\"><img src=\"../PIC/top/register.png\" width=\"70px\"></a><br>";
                                echo "<img src=\"../PIC/top/login.png\" onclick=\"document.memberlogin.submit()\" width=\"70px\"><br>";    
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center">
                        <a href="movie.php?search=&kind=1&category=0" onMouseOut="document.movie.src='../PIC/top/movie.png'" onMouseOver="document.movie.src='../PIC/top/movie-1.png'"><img src="../PIC/top/movie.png" name="movie" width="70px"></a>　
                        <a href="drama.php?search=&kind=3&category=0" onMouseOut="document.drama.src='../PIC/top/drama.png'" onMouseOver="document.drama.src='../PIC/top/drama-1.png'"><img src="../PIC/top/drama.png" name="drama" width="70px"></a>　
                        <a href="tvshow.php?search=&kind=2&category=0" onMouseOut="document.tvshow.src='../PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='../PIC/top/tvshow-1.png'"><img src="../PIC/top/tvshow.png" name="tvshow" width="70px"></a>　
                        <a href="actor.php?actor_id=0" onMouseOut="document.actor.src='../PIC/top/actor.png'" onMouseOver="document.actor.src='../PIC/top/actor-1.png'"><img src="../PIC/top/actor.png" name="actor" width="70px"></a>
                    </td>
                    <td></td>
                    <td>
                        <?php
                            //判斷登入狀態
                            if(!isset($_SESSION["username"])){
                                echo "<a href=\"./forget.php\"><img src=\"../PIC/top/forget.png\" width=\"130px\" /></a>";
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </div>
		<br>
        <div id="context">
			<table>
				<tr><td width=50%><?php echo "<img src='".$data[0]['PHOTO']."'>";?></td>
					<td width=50%>
						<p id='video_name'><?php echo $data[0]['VIDEO_NAME'];?><a href='./do_add_myfavorite.php'>加入最愛</a></p>
						<p id="ratings">評分：<?php echo $data[0]['SCORE'];?></p>
						<p>類　　型：<?php echo $data[0]['CATEGORY_NAME'];?></p>
						<p>上映日期：<?php echo $data[0]['RELEASE_DATE'];?></p>
						<p>語　　言：<?php echo $data[0]['LANGUAGE'];?></p>
						<p>地　　區：<?php echo $data[0]['REGION'];?></p>
						<p>預　　算：<?php echo $data[0]['BUDGET'];?> 元</p>
						<p>票　　房：<?php echo $data[0]['BOXOFFICE'];?> 元</p>
						<p>片　　長：<?php echo $data[0]['PLAYTIME'];?></p>
					</td>
				</tr>
				<tr>
					<td colspan=2>
						<p>劇情</p>
						<p><?php echo $data[0]['STORY']."<a href='https://zh.wikipedia.org/wiki/".$data[0]['VIDEO_NAME']."'/>" ?>...詳全文</a></p>
					</td>
				</tr>
				<tr><td colspan=2><p>演員</p></td></tr>
                    <?php  echo $actor_table;  ?>				
				<tr><td colspan=2><p>預告</td></p>
				<tr>
					<td colspan=2 align="center">	
						<?php echo "<iframe width='863' height='485' src='https://www.youtube.com/embed/".$data[0]['TRAIL']."' frameborder='0' allowfullscreen></iframe>"; ?>
					</td>
				</tr>
			</table>
		</div>
        <div id="commentary">
                <div id='show_comment'>
                <?php
                    echo $commentary_div;
                ?>
                </div>
                <div id='message'>
                <?php
                    if(isset($_SESSION['username'])){
                        echo "<form action='./do_insert_comment.php' method='POST'>";
                        echo "<textarea name='comment' rows='5' cols='20'></textarea>";
                        echo "<input type='hidden' name='userid' value='".$_SESSION['userid']."'/>";
                        echo "<input type='hidden' name='videoid' value='".$_GET['VIDEO_ID']."'/>";
                        echo "<input type='submit' value='送出' />";
                        echo "</form>";
                    }
                ?>
                </div>
        </div>
    </div>
</body>

</html>