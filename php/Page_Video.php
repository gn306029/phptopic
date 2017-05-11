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
<?php
    //尋找電影詳細資訊
    function search_function(){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $sql = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`REGION`,`SCORE`,`RELEASE_DATE`,`PLAYTIME`,`BUDGET`,`BOXOFFICE`,`PLAYTIME`,`PHOTO`,`STORY`,`TRAIL`From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_ID` = '".$_GET['VIDEO_ID']."'";
        $result = $conn -> query($sql);
        $conn = null;
        return $result;
    }
    //尋找演員
    function search_actor(){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $sql = "Select `actor`.`ACTOR_ID`,`ACTOR_NAME` From `actor` Join `actor_list` On `actor`.`ACTOR_ID` = `actor_list`.`ACTOR_ID` Where `VIDEO_ID` = '".$_GET['VIDEO_ID']."'";
        $result = $conn -> query($sql);
        $conn = null;
        return $result;
    }
    //尋找該部電影評論
    function search_commentary(){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $sql = "Select `MEMBER_NAME` , `COMMENT_TIME` , `COMMENTARY` , `COMMENTARY_ID` From `commentary` Join `member` On `commentary`.`MEMBER_ID` = `member`.`MEMBER_ID` Where `VIDEO_ID` = '".$_GET['VIDEO_ID']."'";
        $result = $conn -> query($sql);
        $conn = null;
        return $result;
    }
    $result = search_function();
    $actor = search_actor();
    $commentary = search_commentary();
    $data = array();//電影資料
    $actor_table = "";//演員的Table表格
    $commentary_div = "";
    foreach ($result as $row) {
    	array_push($data,$row);
    }
    $index = 0;
    //演員table
    foreach($actor as $row){
        //一行塞兩個
        if($index == 0){        
            $actor_table .= "<tr class='actor'><td><a href='Page_Actor.php?actor_id=$row[0]'/>".trim($row[1])."</td>";
            $index = 1;
        }else if($index == 1){
            $actor_table .= "<td><a href='Page_Actor.php?actor_id=$row[0]'/>".trim($row[1])."</td></tr>";
            $index = 0;
        }
    }
    //評論table
    $comment_data = $commentary -> fetchAll();
    //var_dump($comment_data);
    if(empty($comment_data[0])){
        $commentary_div = "<div class='member_message'>目前暫無評論</div>";
    }else{
        $index = 1;
        foreach ($comment_data as $row) {
            $commentary_div .= "<div class='member_message'>#".$index."   </br>";
            $commentary_div .= "Dear ".$row[0]." says：</br>";
            $commentary_div .= "留言時間：".$row[1]."</br>";
            $commentary_div .= $row[2]."</div><br>";
            $index++;
        }
    }
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
                                echo $_SESSION["username"]."</br>您好";
                                echo "<a href='./Member_Manager.php'>會員中心</a>";
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
                                echo "<a href='./Member_Logout.php'/>登出";
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
                        <a href="Page_Actor.php?actor_id=0" onMouseOut="document.actor.src='../PIC/top/actor.png'" onMouseOver="document.actor.src='../PIC/top/actor-1.png'"><img src="../PIC/top/actor.png" name="actor" width="70px"></a>
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
                        echo "<form action='./Page_Comment.php' method='POST'>";
                        echo "<textarea name='comment' rows='5' cols='100' style='width:100%;'></textarea>";
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
