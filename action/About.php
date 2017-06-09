<?php
   
?>
<?php
	session_start();
	/*
	 * 下拉清單用
	 *
	 */
    include './Page_View_Set.php';
	if(isset($_GET['action'])){
		if($_GET['action'] == "Me"){
			echo "<title>關於我們</title>";
		}else if($_GET['action'] == "Dev"){
			echo "<title>開發人員</title>";
		}else{
			echo "<title>Not Found</title>";
		}
	}
?>

<!DOCTYPE html>
<html>

	<head>
		<title>關於我們</title>
		<link type="text/css" rel="stylesheet" href="../css/common.css">
		<link type="text/css" rel="stylesheet" href="../css/about.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>

	<body>
		<div id="main">
			<?php
				echo $div_header;
			?>
			<div id="context">
				<?php
				if(isset($_GET['action'])){
					if($_GET['action'] == "Me"){
						echo "<table>";
						echo "<tr><td>為了許多需要影視娛樂增添生活色彩</td></tr>";
						echo "<tr><td>卻又不知如何選擇節目而花費大量時間的你們</td></tr>";
						echo "<tr><td>本團隊為您量身打造了IMDB這個線上影視評分網站</td></tr>";
						echo "<tr><td>歡迎大家來到這裡尋找精神存糧</td></tr>";
						echo "<tr><td>尋獲屬於自己的知音</td></tr>";
						echo "<tr><td>用您的文字讓大家熱血沸騰</td></tr>";
						echo "</table>";
					}else if($_GET['action'] == "Dev"){
						echo "<table>";
						echo "<tr><td><div><a href='https://www.facebook.com/ruyutiffany'><img src='../PIC/dev/29.jpg' height='100%'></a></div></td>";
						echo "<td><div><a href='https://www.facebook.com/profile.php?id=100001256308475'><img src='../PIC/dev/36.jpg' height='100%'></a></div></td>";
						echo "<td><div><a href='https://www.facebook.com/profile.php?id=100001723677181'><img src='../PIC/dev/10.jpg' height='100%'></a></div></td>";
						echo "<td><div><a href='https://www.facebook.com/a9331109'><img src='../PIC/dev/26.jpg' height='100%'></a></div></td>";
						echo "<td><div><a href='https://www.facebook.com/linzheguang'><img src='../PIC/dev/30.jpg' height='100%'></a></div></tr>";
						echo "<tr><td><a href='https://www.facebook.com/ruyutiffany'>黃汝淯</a></td>";
						echo "<td><a href='https://www.facebook.com/profile.php?id=100001256308475'>劉志營</a></td>";
						echo "<td><a href='https://www.facebook.com/profile.php?id=100001723677181'>林奕儒</a></td>";
						echo "<td><a href='https://www.facebook.com/a9331109'>范廷維</a></td>";
						echo "<td><a href='https://www.facebook.com/linzheguang'>林哲廣</a></td></td></tr>";
						echo "</table>";
					}
				}
				?>
			</div>
			<?php
				echo $footer;
			?>
		</div>
	</body>
</html>