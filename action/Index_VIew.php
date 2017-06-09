<?php
	$conn = new PDO($dsn,$db_user,$db_password);

	$sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '1' ORDER BY `SCORE` DESC LIMIT 5";

	$result = $conn -> query($sql);
	$conn = null;
	$result = $result -> fetchAll();
	$rank = "<table id='top_list'>";
	$rank .= "<tr><td width=10%>排名</td><td width=10%></td><td width=70%>影片名稱</td><td width=10%>分數</td></tr>";
	$index = 1;
	foreach($result as $row){
		$rank .= "<tr>";
		$rank .= "<td width=10%>".$index."</td>";
		$rank .= "<td width=10%><div class='video_photo'><img src='".$row['PHOTO']."'></div></td>";
		$rank .= "<td width=70%><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</td>";
		$rank .= "<td id='ratings' width=10%>".$row['SCORE']."</td>";
		$rank .= "</tr>";
		$index ++;
	}
	$rank .= "</table>"

?>

<!DOCTYPE html>
<html>
	<head>
		<title>IMDB</title>
		<link type="text/css" rel="stylesheet" href="../css/common.css">
		<link type="text/css" rel="stylesheet" href="../css/index.css">
		<link href="../css/js-image-slider.css" rel="stylesheet" />
    	<script src="../lib/js-image-slider.js" type="text/javascript"></script>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
		<script type="text/javascript">
			function ajax(action,rate){
				$.ajax({
				url:"./Ajax_Action.php",
					data:{
						action:action,
						rate:rate
					},
					type:"post",
					success:function(output){
						var result = JSON.parse(output);
						var rank = "<tr><td width=10%>排名</td><td width=10%></td><td width=70%>影片名稱</td><td>分數</td></tr>";
							for(var i =0;i<Object.keys(result).length;i++){
							rank += "<tr>";
							rank += "<td width=10%>"+(i+1)+"</td>";
							rank += "<td width=10%><div class='video_photo'><img src='"+result[i]['PHOTO']+"'></div></td>";
							rank += "<td width=70%><a href='./Page_Video.php?VIDEO_ID="+result[i]['VIDEO_ID']+"'>"+result[i]['VIDEO_NAME']+"</a></td>";
							rank += "<td id='ratings' width=10%>"+result[i]['SCORE']+"</td>";
							rank += "</tr>";
						}
						$("#top_list").html(rank);
					},
					error: function (request, status, error) {
						$("#error_log").html(request.responseText);
					}
				})
			}
			$(function(){
				/*
				 * 滑鼠在該標題上的事件
				 *
				 */
				$(".video_title").mouseover(function(event){
					$(this).css("background-color","black").css("color","LightSkyBlue");
					if($(this).text() == "TOP5 電影"){
						ajax("rate_movie_top5","DESC")
					}else if($(this).text() == "TOP5 戲劇"){
						ajax("rate_drama_top5","DESC")
					}else if($(this).text() == "TOP5 綜藝節目"){
						ajax("rate_tvshow_top5","DESC")
					}else if($(this).text() == "糞電影"){
						ajax("rate_movie_top5","ASC")
					}else if($(this).text() == "狗血劇"){
						ajax("rate_drama_top5","ASC")
					}else if($(this).text() == "大學生了沒"){
						ajax("rate_tvshow_top5","ASC")
					}				
				})
				/*
				 * 滑鼠離開標題時的事件
				 *
				 */
				$(".video_title").mouseout(function(event){
					$(this).css("background-color","white").css("color","black");
				})
			})
		</script>
	</head>
	<body>
		<div id="main">
			<?php
				echo $div_header;
			?>
			<div id="context">
				<div id="sliderFrame">
					<div id="slider">
						<?php
							$dir = '../PIC/tmp_photo';
								if($dh = opendir($dir)){
									while(($file=readdir($dh))!==false){
										if($file!='..' && $file!='.'){
									       $file=iconv("BIG5", "UTF-8",$file); //必要,否則中文會亂碼
									       echo pathinfo($file, PATHINFO_DIRNAME).pathinfo($file, PATHINFO_FILENAME );
									   }
										
									}
								}
						?>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/01.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/02.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/03.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/04.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/05.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/06.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/07.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/08.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/09.jpg"/></a>
						<a class="ns-img"><img width="700px" height="300px" src="../PIC/banner/10.jpg"/></a>
					</div>
				</div>
				<div id='rank'>
					<table><tr><td class="video_title">TOP5 電影</td><td class="video_title">TOP5 戲劇</td><td class="video_title">TOP5 綜藝節目</td><td class="video_title">糞電影</td><td class="video_title">狗血劇</td><td class="video_title">大學生了沒</td></tr></table>
					<?php
						echo $rank;
					?>
				</div>
				
			</div>
			<?php
				echo $footer;
			?>
		</div>
	</body>
</html>