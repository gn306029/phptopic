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
    
    function search_function($sql){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        
        $result = $conn -> query($sql);
        return $result;
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
     * count_actor 為要計算總數的查詢
     *
     */
    $sql = "SELECT * FROM `actor` Order by `ACTOR_NAME` LIMIT ".($now_pages*10-10).",10";
	$result = search_function($sql) -> fetchAll();
    $count_actor = search_function("SELECT `ACTOR_ID` FROM `actor`") -> fetchAll();
    /*
     * 計算有幾筆資料
     *
     */
    $all_num = count($count_actor);
    /*
     * table 為要輸出的演員資料
     *
     */
	$table = "";
    foreach ($result as $row) {
        $table .= "<tr>";
		$table .= "<td><a href='./Page_Actor.php?actor_id=".$row['ACTOR_ID']."'><img src='".$row['ACTOR_PHOTO']."' width='70px'></a></td>";
        $table .= "<td><a href='./Page_Actor.php?actor_id=".$row['ACTOR_ID']."'>".$row['ACTOR_NAME']."</td>";
        $table .= "<td Width='200'>".mb_substr($row['ACTOR_HISTORY'],0,30)."...</td>";
        $table .= "</tr>";
    }
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
    $page_list = "<select name='page' id='select_page'>";
    for($i = 1;$i<=$all_num;$i++){
        if($i == $now_pages){
            $page_list .= "<option value='".$i."' selected>".$i."</option>";
        }else{
            $page_list .= "<option value='".$i."' >".$i."</option>";
        }
    }
    $page_list .= "</select>";
    /*
     * 頁碼設定
     *
     */
    if($now_pages == 1){
        $table .= "<tr><td colspan='3' align='center'>".$page_list."<a href='./Page_ActorList.php?page=".($now_pages+1)."'>下一頁</a></td></tr>";
    }else if($now_pages == $all_num){
        $table .= "<tr><td colspan='3' align='center'><a href='./Page_ActorList.php?page=".($now_pages-1)."'>前一頁</a>".$page_list."</td></tr>";
    }else{
        $table .= "<tr><td colspan='3' align='center'><a href='./Page_ActorList.php?page=".($now_pages-1)."'>前一頁</a>".$page_list." <a href='./Page_ActorList.php?page=".($now_pages+1)."'>下一頁</a></td></tr>";
    }
    
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/index.css">
    <link type="text/css" rel="stylesheet" href="../css/search.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
        /*
         * 用下拉選單選擇 Page 時觸發的事件
         *
         */
        $(function(){
            $("#select_page").change(function(){
                $.get("./Page_ActorList.php?page="+$("#select_page").val(),function(data){
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
			<table>
				<tr>
					<td></td><td>名稱</td><td Width='750'>簡歷</td>
				</tr>
				<?php
					echo $table;
				?>
			</table>
		</div>
    </div>
</body>

</html>
