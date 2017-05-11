<?php
    include './searchDetail.php';
?>
<?php
	$db_host='fs.mis.kuas.edu.tw';
	$db_name='s1104137130';
	$db_user='s1104137130';
	$db_pswd='1314520';
	$dsn="mysql:host=$db_host;dbname=$db_name;charset=utf8";
	$conn=new PDO($dsn,$db_user,$db_pswd);
	
	$stmt=$conn->query("SELECT * FROM CATEGORY");
	
	 function search_function($search,$category,$kind){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $sql = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`REGION`,`SCORE`,`RELEASE_DATE`,`PLAYTIME` From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_NAME` Like '%".$search."%' And ".$category." And ".$kind."";
        $result = $conn -> query($sql);
        return $result;
    }
	
	session_start();
    $search = $_GET['search'];
    $kind_ = $_GET['kind'];
    $category_ = $_GET['category'];

    if($kind_ == "0"){
        $kind_ = "`video`.`KIND_ID` LIKE '%%'";
    }else{
        $kind_ = "`video`.`KIND_ID` = '".$_GET['kind']."'";
    }
    if($category_ == "0"){
        $category_ = "`video`.`CATEGORY_ID` LIKE '%%'";
    }else{
        $category_ = "`video`.`CATEGORY_ID` = '".$_GET['category']."'";
    }$result = search_function($search,$category_,$kind_);
	$table = "";
    foreach ($result as $row1) {
        $table .= "<tr>";
        $table .= "<th><a href='./video.php?VIDEO_ID=".$row1['VIDEO_ID']."'>".$row1['VIDEO_NAME']."</th>";
        $table .= "<td>".$row1['CATEGORY_NAME']."</td>";
        $table .= "<td>".$row1['KIND_NAME']."</td>";
        $table .= "<td>".$row1['LANGUAGE']."</td>";
        $table .= "<td>".$row1['REGION']."</td>";
        $table .= "<td>".$row1['SCORE']."</td>";
        $table .= "<td>".$row1['RELEASE_DATE']."</td>";
        $table .= "<td>".$row1['PLAYTIME']."</td>";
        $table .= "</tr>";
    }

	$dramatable='';
	$dramatable .= "<tr>";
	foreach($stmt as $row){	
        $dramatable .= '<td><a href=./drama.php?search=&kind=3&category='. $row['CATEGORY_ID']. '>'. $row['CATEGORY_NAME'].'</a></td>';
	}
	$dramatable .= "</tr>";
?>

<!DOCTYPE html>
<html>
 
<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/index.css">
    <link type="text/css" rel="stylesheet" href="../css/search.css">
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
                        <a href="./register.php"><img src="../PIC/top/register.png" width="70px"></a><br>
                        <img src="../PIC/top/login.png" onclick="document.memberlogin.submit()" width="70px"><br>
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
                        <a href="forget.html"><img src="../PIC/top/forget.png" width="130px" /></a>
                    </td>
                </tr>
            </table>
        </div>
		<br>
        <div id="context">
			<table align='center'>
				<?php
					echo $dramatable;
				?>
			</table>
			<table align='center'>
				<?php
					echo $table;
				?>
			</table>
		</div>
    </div>
</body>

</html>