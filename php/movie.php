<?php
    session_start();
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
	$table='';
	$table .= "<table>";
	foreach($stmt as $row){	
        $table .= '</br><tr><a href=./search.php?search=&kind=1&category='. $row['CATEGORY_ID']. '>'. $row['CATEGORY_NAME'].'</a></tr>';
	}
	$table .= "</table>";
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/index.css">
    <link type="text/css" rel="stylesheet" href="../css/search.css">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
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
					echo $table;
				?>
			</table>
		</div>
    </div>
</body>

</html>