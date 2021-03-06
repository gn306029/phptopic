﻿<?php
    session_start();
	/*
     * include 為產生下拉清單的 Php
     *
     */
    include './Page_View_Set.php';
?>
<?php
    
    function sql_select($sql,$array){
    	$db_host = 'db.mis.kuas.edu.tw';
	    $db_name = 's1104137130';
	    $db_user = 's1104137130';
	    $db_password = '1314520';
	    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
	    $conn = new PDO($dsn,$db_user,$db_password);
        $stmt = $conn -> prepare($sql);
        if(isset($array)){
            $stmt -> execute($array);
        }else{
            $stmt -> execute();
        }
        return $stmt->fetchAll();
    }
    /*
     * 尋找電影詳細資訊
     *
     */
	 if(isset($_GET['VIDEO_ID'])){
		$array = array($_GET['VIDEO_ID']);		 
		$result = sql_select("Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`REGION`,`SCORE`,`RELEASE_DATE`,`PLAYTIME`,`BUDGET`,`BOXOFFICE`,`PLAYTIME`,`PHOTO`,`STORY`,`TRAIL`From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_ID` = ?",$array);
		/*
		 * 尋找演員
		 *
		 */
		$actor = sql_select("Select `actor`.`ACTOR_ID`,`ACTOR_NAME`,`ACTOR_PHOTO` From `actor` Join `actor_list` On `actor`.`ACTOR_ID` = `actor_list`.`ACTOR_ID` Where `VIDEO_ID` = ?",$array);
		/*
		 * 尋找該部電影評論
		 *
		 */
		$commentary = sql_select("Select `MEMBER_NAME` , `COMMENT_TIME` , `COMMENTARY` , `COMMENTARY_ID` From `commentary` Join `member` On `commentary`.`MEMBER_ID` = `member`.`MEMBER_ID` Where `VIDEO_ID` = ? ",$array);
		/*
		 * data 為電影資料
		 *
		 */
		$data = $result;
		$actor_table = "<tr class='actor'>";
		$commentary_div = "";
		$index = 0;
		/*
		 * 演員的Table表格
		 *
		 */
		foreach($actor as $row){
			$index++;
			$actor_table .= "<td><div><a href='Page_Actor.php?actor_id=$row[0]'/><img src='".$row[2]."' height='100%'></a></div><br><a href='Page_Actor.php?actor_id=$row[0]'/>$row[1]</a></td>";
			if($index%5==0){
				$actor_table .="<tr class='actor'>";
			}
		}
		$actor_table.="</tr>";
		/*
		 * 評論 table
		 *
		 */
		$comment_data = $commentary;
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
		if(isset($_SESSION['username'])){
			/*
			 * 搜尋我的最愛資料
			 *
			 */
			$f_array = array($_SESSION['userid'],$data[0]["VIDEO_ID"]);
			$favorite = sql_select("Select count(`video`.`VIDEO_ID`) From `video` Join `favorite` On `video`.`VIDEO_ID` = `favorite`.`VIDEO_ID` Where `MEMBER_ID` = ? and `video`.`VIDEO_ID` = ?",$f_array);
			if($favorite[0][0]=='1'){
				$msg="<input type='button' value='取消我的最愛' id='favorite' style='background-color: black;color: white;' onclick=\"favorite()\"/>";
			}else{
				$msg="<input type='button' value='加入我的最愛' id='favorite' style='background-color:yellow;' onclick=\"favorite()\"/>";
			}	
		}
	}
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/common.css">
    <link type="text/css" rel="stylesheet" href="../css/video.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script>
	$(function(){
		$("#send").click(function(){
			$.ajax({
				url:"./Page_Comment.php",
				type:"post",
				data:$("#comment_form").serialize(),
				success:function(output){
					var result = JSON.parse(output);
					var html = "<div class='member_message'>#"+(parseInt($(".member_message").last().text().substring(1,2))+1)+"   </br>";
					html += "Dear "+result[0]['MEMBER_NAME']+" says：</br>";
					html += "留言時間："+result[0]['COMMENT_TIME']+"</br>";
            		html += result[0]['COMMENTARY']+"</div><br>";
					$("#show_comment").append(html);
				},
                error: function (request, status, error) {
                    $("#error_log").html(request.responseText);
                }
			})
		})
	})
	//加入我的最愛
	function favorite(){
		$.ajax({
			url:"./Member_Information_Set.php",
			data:{
				action:"video_favorite",
				videoid:$("#vid").val()
			},
			type:"post",
			dataType:"json",
			success:function(output){
				if(output=="1"){
					alert("新增成功");
					$("#button_name").html("<input type='button' value='取消我的最愛' id='favorite' style='background-color:black;color: white; 'onclick=\"favorite()\"/>");
				}else if(output!="1"){
					alert("取消成功");
					$("#button_name").html("<input type='button' value='加入我的最愛' id='favorite' style='background-color:yellow;' onclick=\"favorite()\"/>");
				}
				
			},
			error: function (request, status, error) {
				alert(request.responseText);
				$("#error_log").html(request.responseText);
			}
		})
	}
	var img_num = null;
	var check = null;
	//================== 
	// 圖片地址設置 
	//================== 
	var imgSrc = '../PIC/top/star.png'; //沒有填色的星星
	var imgSrc_2 = '../PIC/top/star-1.png'; //打分後有顏色的星星
	//--------------------------------------------------------------------------- 
	var checkvid = (window.location.search).substring(10);
	var checklogin = "<?php if(isset($_SESSION['userid'])){echo $_SESSION['userid'];}else{echo "fuck";}  ?>";
	if(checklogin=="fuck"){
		check = 1;
		}
	else{
		$.ajax({
			url:"./Page_Rating.php",
			data:{
				member_id:checklogin,
				video_id:checkvid
			},
			dataType:"json",
			type:"get",	
			success: function(output) {
				if(typeof output[0] == "undefined"){
					check = 0;
					var rate_html ="　";
					for(i=0;i<10;i++){
						check = 0;
						rate_html += "<img src='../PIC/top/star.png' style=\"width:20px\"/>";
					}
				}
				else{
					check = 1;
					var rate_html = "　";
					for(i=0;i<output[0];i++){
						rate_html += "<img src='../PIC/top/star-1.png' style=\"width:20px\"/>";
					}
					for(i=0;i<(10-output[0]);i++){
						rate_html += "<img src='../PIC/top/star.png' style=\"width:20px\"/>";
					}
				}
				$(".starWrapper").html(rate_html);
			},
			error: function (request, status, error) {
				console.log("fuck");
				$("#error_log").html(request.responseText);
			}
		})
		
	}
	function phprating(){
		if(check==1) {
			check=0;
			return;
		}
		$.ajax({
			url:"./Page_Rating.php",
			data:{
				member_id:$("#uid").val(),
				video_id:$("#vid").val(),
				rating:img_num
			},
			type:"get",	
			dataType:"json",
			success: function(output) {
				alert("評分成功");
				check = 1;
				$("#ratings").html("評分："+output[0][0]);
			},
			error: function (request, status, error) {
				$("#error_log").html(request.responseText);
			}
		})
	}
	function rate(obj,oEvent){ 
		if(check==1) return; 
		var e = oEvent || window.event; 
		var target = e.target || e.srcElement;  
		var imgArray = obj.getElementsByTagName("img"); 
		for(var i=0;i<imgArray.length;i++){ 
		   imgArray[i]._num = i;
		} 
		if(target.tagName=="IMG"){ 
		   for(var j=0;j<imgArray.length;j++){ 
			if(j<=target._num){ 
			 imgArray[j].src=imgSrc_2;
			 img_num = imgArray[j]._num+1;
			 } else { 
			 imgArray[j].src=imgSrc; 
			} 
		   } 
		} else { 
		   for(var k=0;k<imgArray.length;k++){ 
			imgArray[k].src=imgSrc; 
		   } 
		} 
	}
	</script>
</head>

<body>
    <div id="main">
        <?php
        	echo $div_header;
        ?>
        <div id="context">
			<table>
				<?php
					try{
						if(isset($data[0]["VIDEO_ID"])){
							echo "<tr>";
							echo "<td width=50%><img src='".addslashes($data[0]['PHOTO'])."'></td>";
							echo "<td width=50%>";
							echo "<input type='hidden' id='V_id' value='".$data[0]["VIDEO_ID"]."'>";
							echo "<label id='video_name' style='color:hotpink;'>".addslashes($data[0]['VIDEO_NAME'])."</label>";
							echo "<label id='button_name' style='color:hotpink;'>";
							if(isset($_SESSION['userid'])){ 
								echo $msg;
							}
							echo "</label>";
							if(isset($_SESSION['userid'])){
								echo "<input type='hidden' value='".$_SESSION['userid']."' id='uid'>";
							}
							echo "<p id='ratings'>評分：".$data[0]['SCORE']."</p>";
							echo "<p class='starWrapper' onmouseover='rate(this,event)' onclick='phprating(this)'></p>";
							echo "<p id='rerate'></p>";
							echo "<p id = 'error_log'></p>";
							echo "<p>類　　型：".addslashes($data[0]['CATEGORY_NAME'])."</p>";
							echo "<p>上映日期：".addslashes($data[0]['RELEASE_DATE'])."</p>";
							echo "<p>語　　言：".addslashes($data[0]['LANGUAGE'])."</p>";
							echo "<p>地　　區：".addslashes($data[0]['REGION'])."</p>";
							echo "<p>預　　算：".addslashes($data[0]['BUDGET'])."</p>";
							echo "<p>票　　房：".addslashes($data[0]['BOXOFFICE'])."</p>";
							echo "<p>片　　長：".addslashes($data[0]['PLAYTIME'])."</p>";
							echo "</td>";
							echo "</tr>";
						}else{
							echo "無此影片";
						}	
					}catch(Exception $e){
						echo $e -> getMessage();
						}
				?> 
			</table>
			<table>
				<?php
					if(isset($data[0]["VIDEO_ID"])){
						echo "<tr><td colspan=5><p>".$actor_table."</p></td></tr>";
						echo "<tr><td colspan=5><p style='color:hotpink;'>介紹</p><p>".addslashes($data[0]['STORY'])."<a href='https://zh.wikipedia.org/wiki/".addslashes($data[0]['VIDEO_NAME'])."'/>詳全文</a></p></td></tr>";
						echo "<tr><td colspan=5><p style='color:hotpink;'>影片</p></td></tr>";
						echo "<tr><td colspan=5 align='center'><iframe width='863' height='485' src='https://www.youtube.com/embed/".addslashes($data[0]['TRAIL'])."' frameborder='0' allowfullscreen></iframe></td></tr>";
						echo "<tr><td colspan=5><p style='color:hotpink;'>評論</td></p></td></tr>";
					}
				?>
			</table>
		</div>
        <div id="commentary">
            <div id='show_comment'>
			<?php
				if(isset($data[0]["VIDEO_ID"])){
					echo $commentary_div;
				}
			?>
			</div>
			<div id='message'>
			<?php
				if(isset($_SESSION['username']) && isset($data[0]["VIDEO_ID"])){
					echo "<form id='comment_form'>";
					echo "<textarea name='comment' rows='5' cols='100' style='width:100%;'></textarea>";
					echo "<input type='hidden' name='userid' value='".$_SESSION['userid']."'/>";
					echo "<input type='hidden' id='vid' name='videoid' value='".$_GET['VIDEO_ID']."'/>";
					echo "<input type='button' id='send' value='送出' />";
					echo "</form>";
				}
			?>
			</div>
        </div>
		<?php
			echo $footer;
		?>
    </div>
</body>

</html>