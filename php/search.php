<?php
    include './searchDetail.php';
?>
<?php
    function search_function($search,$category,$kind){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $select = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`REGION`,`SCORE`,`RELEASE_DATE`,`PLAYTIME` From `movie` Join `kind` On `movie`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `movie`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_NAME` Like '%".$search."%' And `movie`.`CATEGORY_ID` Like '%".$category."%' And `movie`.`KIND_ID` Like '%".$kind."%'";
        $result = $conn -> query($select);
        return $result;
    }
    //啟用 session
    session_start();
    $search = $_GET['search'];
    $kind_ = $_GET['kind'];
    $category_ = $_GET['category'];

    if($kind_ == "0"){
        $kind_ = "";
    }
    if($category_ == "0"){
        $category_ = "";
    }
    $result = search_function($search,$category_,$kind_);
    $table = "";
    foreach ($result as $row) {
        $table .= "<tr>";
        $table .= "<td><a href='./video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</td>";
        $table .= "<td>".$row['CATEGORY_NAME']."</td>";
        $table .= "<td>".$row['KIND_NAME']."</td>";
        $table .= "<td>".$row['LANGUAGE']."</td>";
        $table .= "<td>".$row['REGION']."</td>";
        $table .= "<td>".$row['SCORE']."</td>";
        $table .= "<td>".$row['RELEASE_DATE']."</td>";
        $table .= "<td>".$row['PLAYTIME']."</td>";
        $table .= "</tr>";
    }

?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
    <link type="text/css" rel="stylesheet" href="../css/movie.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
    <div id="main">
        <div id="header">
            <table>
                <tr>
                    <td id="logo">
                        <a href="../index.html"><img src="../PIC/top/logo.png" width="200px"></a>
                    </td>
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
                    <td id="memberlogin">
                        <form name="memberlogin" action="../php/login.php" method="POST">
                            <img src="../PIC/top/account.png" width="70px" />
                            <input type="text" name="MEMBER_ACCOUNT" />
                            <br>
                            <img src="../PIC/top/password.png" width="70px" />
                            <input type="password" name="MEMBER_PASSWORD">
                            <br>
                        </form>
                    </td>
                    <td id="memberlogin2">
                        <a href="../page/register.php"><img src="../PIC/top/register.png" width="70px"></a><br>
                        <img src="../PIC/top/login.png" onclick="document.memberlogin.submit()" width="70px"><br>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center">
                        <a href="movie.php" onMouseOut="document.movie.src='../PIC/top/movie.png'" onMouseOver="document.movie.src='../PIC/top/movie-1.png'"><img src="../PIC/top/movie.png" name="movie" width="70px"></a>
                        <a href="drama.php" onMouseOut="document.drama.src='../PIC/top/drama.png'" onMouseOver="document.drama.src='../PIC/top/drama-1.png'"><img src="../PIC/top/drama.png" name="drama" width="70px"></a>
                        <a href="tvshow.php" onMouseOut="document.tvshow.src='../PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='../PIC/top/tvshow-1.png'"><img src="../PIC/top/tvshow.png" name="tvshow" width="70px"></a>
                        <a href="actor.php" onMouseOut="document.actor.src='../PIC/top/actor.png'" onMouseOver="document.actor.src='../PIC/top/actor-1.png'"><img src="../PIC/top/actor.png" name="actor" width="70px"></a>
                    </td>
                    <td></td>
                    <td>
                        <a href="forget.html"><img src="../PIC/top/forget.png" width="130px" /></a>
                    </td>
                </tr>
            </table>
        </div>
		<br>
        <div id="context">
			<table>
            <tr>
            <td>
            影片名稱呦
            </td>
            <td>
            類別呦
            </td>
            <td>
            類型呦
            </td>
            <td>
            語言呦
            </td>
            <td>
            地區呦
            </td>
            <td>
            分數呦
            </td>
            <td>
            上映日期呦
            </td>
            <td>
            母體比例呦
            </td>
            </tr>
            <?php
                echo $table;
            ?>
				<!--<tr><th colspan=2>金剛：骷髏島</th></tr>
				<tr><td width=50%><img src="http://pic.pimg.tw/dinosaurs/1489050945-446408582.jpg"></td>
					<td width=50%>
						<p id="ratings">專業評分：8.7/10</p>
						<p id="score">會員評分：8.7/10</p>
						<p>類　　型：科幻</p>
						<p>上映日期：2017年03月10日</p>
						<p>語　　言：英語</p>
						<p>地　　區：美國</p>
						<p>預　　算：1.85億美元</p>
						<p>票　　房：5.54億美元</p>
						<p>片　　長：118分鐘</p>
					</td>
				</tr>
				<tr>
					<td colspan=2>
						<p>劇情</p>
						<p>故事設定於1973年，美國發射了人造衛星後，意外在太平洋發現了「骷髏島」。... <a href="https://zh.wikipedia.org/wiki/金剛：骷髏島#.E5.8A.87.E6.83.85">詳全文</a></p>
					</td>
				</tr>
				<tr><td colspan=2><p>演員</p></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=湯姆·希德斯頓">湯姆·希德斯頓</a></td><td><a href="actor.php?actor=山繆·傑克森">山繆·傑克森</a></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=約翰·古德曼">約翰·古德曼</a></td><td><a href="actor.php?actor=布麗·拉森">布麗·拉森</a></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=景甜">景甜</a></td><td><a href="actor.php?actor=約翰·奧提茲">約翰·奧提茲</a></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=托比·凱貝爾">托比·凱貝爾</a></td><td> <a href="actor.php?actor=柯瑞·霍金斯">柯瑞·霍金斯</a></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=湯瑪斯·曼恩">湯瑪斯·曼恩</a></td><td><a href="actor.php?actor=傑森·米切爾">傑森·米切爾</a></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=謝伊·惠格姆">謝伊·惠格姆</a></td><td><a href="actor.php?actor=泰瑞·諾塔利">泰瑞·諾塔利</a></td></tr>
					<tr class="actor"><td><a href="actor.php?actor=約翰·C·萊利">約翰·C·萊利</a></td>
				</tr>
				<tr><td colspan=2><p>預告</td></p>
				<tr>
					<td colspan=2 align="center">	
						<iframe width="863" height="485" src="https://www.youtube.com/embed/vH2iuDnuKjE" frameborder="0" allowfullscreen></iframe>
					</td>
				</tr>-->
			</table>
		</div>
    </div>
</body>

</html>
