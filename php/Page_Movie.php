<?php
    session_start();
	/*
     * include 為產生下拉清單的 Php
     *
     */
    include './Page_Search_Set.php';
	/*
     * 帳號與密碼的輸入框
     *
     */
    $login_form = "<form name='memberlogin' action='./Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
	$login_form .= "</form>";
?>
<?php
	/*
     * 建立資料庫連線
     *
     */
	$db_host='fs.mis.kuas.edu.tw';
	$db_name='s1104137130';
	$db_user='s1104137130';
	$db_pswd='1314520';
	$dsn="mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn=new PDO($dsn,$db_user,$db_pswd);
	/*
     * 搜尋所有種類
     *
     */
	$stmt=$conn->query("SELECT DISTINCT A.CATEGORY_ID, B.CATEGORY_NAME FROM VIDEO A JOIN CATEGORY B ON A.CATEGORY_ID=B.CATEGORY_ID where a.KIND_ID=1");
	
	/*
     * 搜尋所有種類為戲劇的影片
     * limit 不等於 null , 為分頁用的資料
     * limit 等於 null , 為計算資料總數用的 Sql
     */
	function search_function($search,$category,$kind,$limit){
        global $conn;
        if($limit != null){
            $sql = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`SCORE`,`RELEASE_DATE`,`PHOTO` From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_NAME` Like '%".$search."%' And ".$category." And ".$kind." Order by `VIDEO_NAME` ".$limit;
        }else{
            $sql = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`SCORE`,`RELEASE_DATE`,`PHOTO` From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_NAME` Like '%".$search."%' And ".$category." And ".$kind;
        }
		$result = $conn -> query($sql);
		return $result;
    }
	
    $search = $_GET['search'];
    $kind_ = $_GET['kind'];
    $category_ = $_GET['category'];
	/*
     * 如果沒有指定類別或種類 就搜尋全部
     *
     */
    if($kind_ == "0"){
        $kind_ = "`video`.`KIND_ID` LIKE '%%'";
    }else{
        $kind_ = "`video`.`KIND_ID` = '".$_GET['kind']."'";
    }
    if($category_ == "0"){
        $category_ = "`video`.`CATEGORY_ID` LIKE '%%'";
    }else{
        $category_ = "`video`.`CATEGORY_ID` = '".$_GET['category']."'";
    }

    /*
     * 預設頁數
     *
     */
    $now_pages = 1;
    /*
     * 如果有頁數 就使用該頁數
     *
     */
    if (isset($_GET['page'])) {
      $now_pages = $_GET['page'];
    }

    /*
     * result 為要顯示的資料
     * count_movie 為要計算總數的查詢
     *
     */
    $result = search_function($search,$category_,$kind_,"LIMIT ".($now_pages*10-10).",10");
    $count_movie = search_function($search,$category_,$kind_,null) -> fetchAll();
    /*
     * 計算有幾筆資料
     *
     */
    $all_num = count($count_movie);
    $page_list = "<select name='page' id='select_page'>";
    /*
     * 如果總數不被整除 , 頁碼要加一
     *
     */
    if($all_num % 10 != 0){
        $all_num = floor( $all_num / 10 ) + 1 ;
    }else{
        $all_num = floor( $all_num / 10 );
    }
    /*
     * 新增頁碼到 Select
     *
     */
    for($i = 1;$i<=$all_num;$i++){
        if($i == $now_pages){
            $page_list .= "<option value='".$i."' selected>".$i."</option>";
        }else{
            $page_list .= "<option value='".$i."' >".$i."</option>";
        }
    }
    /*
     * $table 為要輸出的影片 table
     *
     */
	$table = "";
	$page_list .= "</select>";
    foreach ($result as $row ) {
        $table .= "<tr>";
		$table .= "<td><div><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'><img src='".$row['PHOTO']."' height='100%'></a></div></td>";
        $table .= "<th><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</th>";
        $table .= "<td>".$row['CATEGORY_NAME']."</td>";
        $table .= "<td>".$row['KIND_NAME']."</td>";
        $table .= "<td>".$row['LANGUAGE']."</td>";
        $table .= "<td>".$row['RELEASE_DATE']."</td>";
        $table .= "<td><label class='score'>".$row['SCORE']."</label></td>";
        $table .= "</tr>";
    }
	$i=0;
	/*
     * 按下 戲劇 後的那一排選擇種類
     *
     */
	$movietable='';
	$movietable .= "<tr>";
	foreach($stmt as $row){	
        $movietable .= '<td><a href=./Page_Movie.php?search=&kind=1&category='. $row['CATEGORY_ID']. '>'. $row['CATEGORY_NAME'].'</a></td>';
		$i++;
		if($i%10==0){
			$movietable .='</tr><tr>';
		}
	}
	$movietable .= "</tr>";
	/*
     * 頁碼設定
     *
     */
    if($now_pages == 1){
        $table .= "<tr><td colspan='8' align='center'>".$page_list."<a href='./Page_Movie.php?page=".($now_pages+1)."&search=&kind=1&category=0'>下一頁</a></td></tr>";
    }else if($now_pages == $all_num){
        $table .= "<tr><td colspan='8' align='center'><a href='./Page_Movie.php?page=".($now_pages-1)."&search=&kind=1&category=0'>前一頁</a>".$page_list."</td></tr>";
    }else{
        $table .= "<tr><td colspan='8' align='center'><a href='./Page_Movie.php?page=".($now_pages-1)."&search=&kind=1&category=0'>前一頁</a>".$page_list." <a href='./Page_Movie.php?page=".($now_pages+1)."&search=&kind=1&category=0'>下一頁</a></td></tr>";
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/index.css">
    <link type="text/css" rel="stylesheet" href="../css/search.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
        /*
         * 用下拉選單選擇 Page 時觸發的事件
         *
         */
        $(function(){
            $("#select_page").change(function(){
                $.get("./Page_Drama.php?page="+$("#select_page").val()+"&search=&kind=3&category=0",function(data){
                    $("body").html(data);
                })
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
			<table align='center'>
				<?php
					echo $movietable;
				?>
			</table>
			<table>
				<tr>
					<td colspan='2'>名稱</td><td>類別</td><td>類型</td><td>語言</td><td>上映日期</td><td>分數</td>
				</tr>
				<?php
					echo $table;
				?>
			</table>
		</div>
    </div>
</body>

</html>