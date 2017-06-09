<?php

	$db_host = 'db.mis.kuas.edu.tw';
	$db_name = 's1104137130';
	$db_user = 's1104137130';
	$db_password = '1314520';
	$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn = new PDO($dsn,$db_user,$db_password);
	$select = $conn->query("Select * From kind");
	$result = $select -> fetchAll();
	$kind = "<option value='0' selected='selected'>-----</option>";
	foreach($result as $row){
		$kind .= "<option value='".$row['KIND_ID']."'>".$row['KIND_NAME']."</option>";
	}
	$select = $conn->query("Select * From category");
	$Allcategory = $select -> fetchAll();
	$category = "<option value='0' selected='selected'>-----</option>";
	foreach ($Allcategory as $row) {
		$category .= "<option value='".$row['CATEGORY_ID']."'>".$row['CATEGORY_NAME']."</option>";
	}
	$conn = null;
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

    $div_header = "<div id='header'><table><tr><td id=\"logo\"><a href=\"../Index.php\"><img src=\"../PIC/top/logo.png\" width=\"200px\"></a></td><td id=\"search\"><form name=\"search\" action=\"../php/Page_SearchList.php\" method=\"GET\">";
    $div_header .= "<input type=\"text\" name=\"search\" /><select name=\"kind\">".$kind."</select><select name=\"category\">".$category."</select><img src=\"../PIC/top/searchbutton.png\" onclick=\"document.search.submit()\" width=\"42px\"></form>";
    $div_header .= "</td><td id=\"memberlogin\">";
    if(isset($_SESSION["username"])){
        $div_header .= $_SESSION["username"].",您好<br>";
		$div_header .= " <a href=./Member_Manager.php><img src=../PIC/top/manager-1.png name=manager width=150px></a>　　";
		$div_header .= "<a href='./Member_Logout.php'/><img src=\"../PIC/top/logout.png\" width=\"70px\"></a>";
    }else{
        $div_header .= $login_form;   
    }
    $div_header .= "</td><td id=\"memberlogin2\">";
    if(!isset($_SESSION["username"])){
	    $div_header .= "<a href=\"./Member_Register.php\"><img src=\"../PIC/top/register.png\" width=\"70px\"></a><br>";
	    $div_header .= "<img src=\"../PIC/top/login.png\" onclick=\"document.memberlogin.submit()\" width=\"70px\"><br>";   
	}
	$div_header .= "</td></tr><tr><td></td><td align=\"center\">";
	$div_header .= "<a href=\"Page_SearchList.php?search=&kind=1&category=0\" onMouseOut=\"document.movie.src='../PIC/top/movie.png'\" onMouseOver=\"document.movie.src='../PIC/top/movie-1.png'\"><img src=\"../PIC/top/movie.png\" name=\"movie\" width=\"70px\"></a> ".
                        "<a href=\"Page_SearchList.php?search=&kind=3&category=0\" onMouseOut=\"document.drama.src='../PIC/top/drama.png'\" onMouseOver=\"document.drama.src='../PIC/top/drama-1.png'\"><img src=\"../PIC/top/drama.png\" name=\"drama\" width=\"70px\"></a> ".
                        "<a href=\"Page_SearchList.php?search=&kind=2&category=0\" onMouseOut=\"document.tvshow.src='../PIC/top/tvshow.png'\" onMouseOver=\"document.tvshow.src='../PIC/top/tvshow-1.png'\"><img src=\"../PIC/top/tvshow.png\" name=\"tvshow\" width=\"70px\"></a> ".
                        "<a href=\"Page_ActorList.php\" onMouseOut=\"document.actor.src='../PIC/top/actor.png'\" onMouseOver=\"document.actor.src='../PIC/top/actor-1.png'\"><img src=\"../PIC/top/actor.png\" name=\"actor\" width=\"70px\"></a>";
    $div_header .= "</td><td></td><td>";
    if(!isset($_SESSION["username"])){
        $div_header .= "<a href=\"./Member_Forget.php\"><img src=\"../PIC/top/forget.png\" width=\"130px\" /></a>";
    }
    $div_header .= "</td></tr></table></div><br>";

    $footer = "<footer><table><tr><td><a href=\"./About.php?action=Me\"><img height=\"36\" border=\"0\" alter=\"關於\" src=\"../PIC/footer/about.png\"></a></td>".
				"<td><a href=\"./About.php?action=Dev\"><img height=\"36\" border=\"0\" alter=\"開發人員\" src=\"../PIC/footer/dev.png\"></a></td>".
				"<td><div><a href=\"https://line.me/R/ti/p/%40gib2079k\"><img height=\"36\" border=\"0\" alt=\"加入好友\" src=\"https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png\"></a></div></td>".
				"</tr><tr><td colspan=3>© 2017 IMDB,KUASMIS</td></tr></table></footer>";
?>