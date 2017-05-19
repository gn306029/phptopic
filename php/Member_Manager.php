<?php
	/*
	 * 登陸成功就設定session 並重新導向首頁
	 *
	 */
    session_start();
	/*
	 * 下拉式清單用
	 *
	 */
    include './Page_Search_Set.php';
    $login_form = "<form name='memberlogin' action='./Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
    $login_form .= "</form>";
    /*
	 * 建立連線
	 *
	 *
	 */
    $db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);
	/*
	 * 取得會員喜歡的類別
	 *
	 *
	 */
    $sql = "Select `CATEGORY` From `member` Where `MEMBER_ID` = '".$_SESSION['userid']."'";
    $result = $conn -> query($sql);
    $result = $result -> fetchAll();
    $favorite_category =  explode(",",$result[0]["CATEGORY"]);
	/*
	 * Hidden category 區域要裝的類型
	 *
	 *
	 */
    $form_category = "";
    /*
	 * 處理換行用的
	 *
	 *
	 */
    $index = 0;
    foreach ($Allcategory as $row) {
        /*
		 * 每 4 個換一次行
		 *
		 *
		 */
        if($index==4){
            $form_category .= "</br>";
            $index = 0;
        }
        /*
		 * 該變數用來判斷是否找到會員喜歡的種類
		 *
		 *
		 */
        $find = false;
        /*
		 * 當找到會員喜歡的種類時 將其設為 checked
		 *
		 *
		 */
        for($i = 0;$i<count($favorite_category);$i++){
            if($row["CATEGORY_ID"]===$favorite_category[$i]){
                $form_category .= "<input type=\"checkbox\" id=\"MEMBER_CATEGORY\" name=\"member_category[]\" value='".$row["CATEGORY_ID"]."' checked>".$row['CATEGORY_NAME'];
                $find = true;
                break;
            }
        }
        if(!$find){
            $form_category .= "<input type=\"checkbox\" id=\"MEMBER_CATEGORY\" name=\"member_category[]\" value='".$row["CATEGORY_ID"]."'>".$row['CATEGORY_NAME'];
        }
        
        
        $index ++;
    }
    if($favorite_category[0] == 0){
        $form_category .= "<input type=\"checkbox\" id=\"MEMBER_CATEGORY\" name=\"member_category[]\" value='0' checked>其他";
    }else{
        $form_category .= "<input type=\"checkbox\" id=\"MEMBER_CATEGORY\" name=\"member_category[]\" value='0'>其他";
    }
	if(isset($_POST['action'])){
        echo $form_category;
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
    <link type="text/css" rel="stylesheet" href="../css/index.css" />
    <link type="text/css" rel="stylesheet" href="../css/manager.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
        /*
		 * 取得會員詳細資料
		 *
		 *
		 */
        $(function(){
            var member_id = $("#member_infor").val();
            $("#member_infor").click(function() {
				 $("#button_area").html("");
				   $.ajax({
					url:"./Member_Information_Set.php",
					data:{
						action:"infor",
						member_id:member_id
					},
					type:"post",
					dataType:"json",
					success: function(output) {
						var infor_html = "<table>";
						infor_html += "<tr><td><div id='member_detail'>";
						infor_html += "<input type='hidden' id='id' value='"+output[0]+"'>";
						infor_html += "會員帳號：<label id='user_account'>"+output[2]+"</label><br>";
						infor_html += "會員名稱：<label id='user_name'>"+output[1]+"</label><br>";
						infor_html += "會員密碼：<label id='user_password'>"+output[3]+"</label><br>";
						infor_html += "生日：<label id='user_birthday'>"+output[4]+"</label><br>";
						infor_html += "信箱：<label id='user_email'>"+output[5]+"</label><br>";
						infor_html += "手機：<label id='user_phone_num'>"+output[6]+"</label><br>";
						if(output[7] == 0){
							infor_html += "性別：<label id='user_gender'>男</label><br>";
						}else if(output[7] == 1){
							infor_html += "性別：<label id='user_gender'>女</label><br>";
						}else{
							infor_html += "性別：<label id='user_gender'>第三性</label><br>";
						}
						infor_html += "興趣：</br><label>"+$("#hidden_category").html()+"</label><br>";
						infor_html += "<input type='hidden' name='gender' value='"+output[7]+"'><br>";
						infor_html += "工作：<label id='user_job'>"+output[8]+"</label><br>";
						infor_html += "等級：<label id='user_level'>"+output[10]+"</label><br>";
						infor_html += "<button id='update'>修改</button>"
						infor_html += "</div></td></tr></table>"
						$("#my_infor").html(infor_html);
					},
					error: function (request, status, error) {
						$("#error_log").html(request.responseText);
					}
                });
            });
			/*
			 * 確定修改
			 *
			 *
			 */
            $("body").on("click","#update",function () {
                var infor_html = "<table>";
                infor_html += "<tr><td><div id='member_detail'>";
                infor_html += "<form id='detail_form'>";
                infor_html += "<input type='hidden' name='id' value='"+$("#id").val()+"'></br>";
                infor_html += "<input type='hidden' name='action' value='Update'/></br>";
                infor_html += "會員帳號："+$("#user_account").text()+"</br>";
                infor_html += "會員名稱：<input type='text' name='member_name' value='"+$("#user_name").text()+"'></br>";
                infor_html += "會員密碼：<input type='text' name='member_password' value='"+$("#user_password").text()+"'></br>";
                infor_html += "生日：<input type='date' name='member_birthday' value='"+$("#user_birthday").text()+"'></br>";
                infor_html += "信箱：<input type='text' name='member_email' value='"+$("#user_email").text()+"'></br>";
                infor_html += "手機：<input type='text' name='member_phone_num' value='"+$("#user_birthday").text()+"'></br>";
                infor_html += "性別："+$("#user_gender").text()+"</br>"
                infor_html += "工作：<input type='text' name='member_job' value='"+$("#user_job").text()+"'></br>"
                infor_html += $("#hidden_category").html();
                infor_html += "</br>等級："+$("#user_level").text()+"</br>";
                infor_html += "</form>";
                infor_html += "<button id='detail_form_send'>修改</button>";
                infor_html += "</div></td></tr>";
                infor_html += "</table>";
                $("#my_infor").html(infor_html);
            })
			/*
			 * 送出會員要修改的資料
			 *
			 *
			 */
            $("body").on("click","#detail_form_send",function () {
				$("#button_area").html("");
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:$("#detail_form").serialize()+"&action=Update",
                    type:"post",
                    success:function(output){
                        if(output == "success"){
                            alert("更新成功");
                        }else{
                            alert("更新失敗");
                        }
						history.go(0);
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                })
            })
			/*
			 * 取得會員的我的最愛
			 *
			 *
			 */
            $("#member_favorite").click(function(){
				$("#button_area").html("");
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:{
                        action:"favorite",
                        member_id:$("#member_favorite").val()
                    },
                    type:"post",
                    dataType:"json",
                    success:function(output){
                        var favorite_infor = "<table><tr><td></td><td>影片名稱</td><td></td></tr>";
                        for(var i =0; i<Object.keys(output).length;i++){
                            favorite_infor += "<tr>";
                            favorite_infor += "<td><a href='./Page_Video.php?VIDEO_ID="+output[i]["VIDEO_ID"]+"' ><img src='"+output[i]["PHOTO"]+"' width=70 /></a></td>";
                            favorite_infor += "<td><a href='./Page_Video.php?VIDEO_ID="+output[i]["VIDEO_ID"]+"' >"+output[i]["VIDEO_NAME"]+"</a></td>";
                            favorite_infor += "</tr>";
                        }
                        favorite_infor += "</table>";
                        $("#my_infor").html(favorite_infor);
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                })
            })
			/*
             * 建立管理者介面
             *
+             */
            $("#member_manager").click(function(){
                $("#my_infor").html("");
                $("#button_area").html("<button id='add_new_video'>新增影片</button>　"
                                     +"<button id='get_video_detail'>修改與刪除</button>");
             })
            /*
             * 建立新增影片的欄位
             *
             */
            $("body").on("click","#add_new_video",function(){
                var video_infor = "<form id='add_new_video_form'>";
                video_infor += "影片名稱：<input type='text' name='video_name' /></br>";
                video_infor += "上映日期：<input type='date' name='release_date' /></br>";
                video_infor += "影片種類：<select name='add_kind'>"+$("[name=kind]").html()+"</select></br>";
                video_infor += "影片類別：<select name='add_category'>"+$("[name=category]").html()+"</select></br>";
                video_infor += "影片語言：<input type='text' name='language' /></br>";
                video_infor += "影片地區：<input type='text' name='region' /></br>";
                video_infor += "影片分數：<input type='text' name='score' /></br>";
                video_infor += "影片預算：<input type='text' name='budget' /></br>";
                video_infor += "影片票房：<input type='text' name='boxoffice' /></br>";
                video_infor += "影片長度：<input type='text' name='playtime' /></br>";
                video_infor += "圖片網址：<input type='url' name='photo' /></br>";
                video_infor += "影片簡介：<textarea name='story'></textarea></br>";
                video_infor += "Youtube影片編號：<input type='text' name='trail' /></br>";
                video_infor += "</form>";
                video_infor += "<button id='do_add_new_video'>新增</button>";
                $("#my_infor").html(video_infor);
            })
            /*
             * 確定新增
             *
             */
            $("body").on("click","#do_add_new_video",function(){
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:$("#add_new_video_form").serialize()+"&action=add_new_video",
                    type:"post",
                    success:function(output){
                        if(output == "success"){
                            alert("新增成功");
                            $("#my_infor").html("");
                        }else{
                            alert("新增失敗");
                        }
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                })
            })
            /*
             * 取出影片資料欄位
             * 用 ajax 抓出所有影片
             * 更新與刪除都使用這個方法
             *
             */
            $("body").on("click","#get_video_detail",function(){
                 $.ajax({
                    url:"./Member_Information_Set.php",
                    data:{
                        action:"get_video"
                    },
                    type:"post",
                    dataType:"json",
                    success:function(output){
                        $("#my_infor").html("");
                        var select_tag = "<select name='video_select'>";
                        select_tag += "<option value='0' selected>---------------請選擇---------------</option>";
                        for(var i =0;i<output.length;i++){
                            select_tag += "<option value='"+output[i]['VIDEO_ID']+"'>"+output[i]['VIDEO_NAME']+"</option>";
                        }
                        select_tag += "</select>";
                        $("#my_infor").html("請選擇電影名稱："+select_tag);
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                 })
            })
            /*
             * 建立刪除影片的欄位
             * 用 ajax 把相關資料丟到後台
             * 再把資料顯示到前台
             * 讓管理者做修改
             *
             */
            $("body").on("change","[name=video_select]",function(){
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:{
                        action:"get_video_detail",
                        video_id:$("[name=video_select]").val()
                    },
                    type:"post",
                    dataType:"json",
                    success:function(output){
                        /*
                         * 把該部影片的類別與種類設為預設
                         *
                         */
                        var output_kind = "";
                        $("[name=kind] option").each(function(){
                            if(output[0]["KIND_ID"] == $(this).val()){
                                output_kind += "<option value='"+$(this).val()+"' selected>"+$(this).text()+"</option>";
                            }else{
                                output_kind += "<option value='"+$(this).val()+"' >"+$(this).text()+"</option>";
                            }
                        })
                        var output_category = "";
                        $("[name=category] option").each(function(){
                            if(output[0]["CATEGORY_ID"] == $(this).val()){
                                output_category += "<option value='"+$(this).val()+"' selected>"+$(this).text()+"</option>";
                            }else{
                                output_category += "<option value='"+$(this).val()+"' >"+$(this).text()+"</option>";
                            }
                        })
                        if(output != "false"){
                            html = "<form id='video_detail'>";
                            html += "<input type='hidden' name='video_id' value='"+output[0]['VIDEO_ID']+"'/>";
                            html += "影片名稱：<input type='text' name='video_name' value='"+output[0]['VIDEO_NAME']+"'></br>";
                            html += "上映日期：<input type='date' name='release_date' value='"+output[0]['RELEASE_DATE']+"'/></br>";
                            html += "影片種類：<select name='add_kind'>"+output_kind+"</select></br>";
                            html += "影片類別：<select name='add_category'>"+output_category+"</select></br>";
                            html += "影片語言：<input type='text' name='language' value='"+output[0]['LANGUAGE']+"'/></br>";
                            html += "影片地區：<input type='text' name='region' value='"+output[0]['REGION']+"'/></br>";
                            html += "影片分數：<input type='text' name='score' value='"+output[0]['SCORE']+"'/></br>";
                            html += "影片預算：<input type='text' name='budget' value='"+output[0]['BUDGET']+"'/></br>";
                            html += "影片票房：<input type='text' name='boxoffice' value='"+output[0]['BOXOFFICE']+"'/></br>";
                            html += "影片長度：<input type='text' name='playtime' value='"+output[0]['PLAYTIME']+"'/></br>";
                            html += "圖片網址：<input type='url' name='photo' value='"+output[0]['PHOTO']+"'/></br>";
                            html += "影片簡介：<textarea name='story'>"+output[0]['STORY']+"</textarea></br>";
                            html += "Youtube影片編號：<input type='text' name='trail' value='"+output[0]['TRAIL']+"'/></br>";
                            html += "</form>";
                            html += "<button id='do_update_video'>更新</button>      ";
							html += "<button id='do_delete_video'>刪除</button>";
                            $("#my_infor").html(html);
                        }else{
                            alert("請選擇電影");
                        }
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                })
            })
            /*
             * 送出更新資料
             *
             */
            $("body").on("click","#do_update_video",function(){
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:$("#video_detail").serialize() + "&action=update_video",
                    type:"post",
                    success:function(output){
                        if(output == "success"){
                            alert("更新成功");
                        }else{
                            alert(output);
                        }
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                })
            })
            /*
             * 建立刪除影片的欄位
             * 用 ajax 把相關資料丟到後台做相關處理
             *
             */
            $("body").on("click","#do_delete_video",function(){
                if(confirm("確定刪除這部影片嗎?")){
                    if(confirm("你真的確定刪除這部影片嗎?")){
                        if(confirm("你真的真的不會後悔嗎?")){
                            if(confirm("再問最後一次，你真的不後悔嗎?")){
                                if(confirm("真的?")){
                                    alert("好吧");
                                    $.ajax({
                                        url:"./Member_Information_Set.php",
                                        data:{
                                            action:"delete_video",
                                            video_id:$("[name=video_id]").val()
                                        },
                                        type:"post",
                                        success:function(output){
                                            if(output == "success"){
                                                alert("刪除成功");
                                                var msg = "<div>被刪除的影片就像變了心的女朋友，回不來了</div>";
                                                msg += "<img src='http://pic.pimg.tw/clean0074/1341514837-1641908809.jpg'></img>";
                                                $("#my_infor").html(msg);

                                            }else{
                                                alert(output);
                                            }
                                        },
                                        error: function (request, status, error) {
                                            $("#error_log").html(request.responseText);
                                        }
                                    })
                                }
                            }
                        }
                    }
                }
            })
            /*
             * 管理首頁
             *
             */
            $("#manager_index").click(function(){
                $("my_infor").html("");
                $("#button_area").html("<button id='manager_ad'>廣告管理</button>");
            })
            /*
             * 廣告管理
             *
             */
            $("body").on("click","#manager_ad",function(){
                var form = "<form id='select_ad' enctype='multipart/form-data' action='./Member_Save_Picture.php' method='post'>";
                form += "<input name='file[]' type='file'></br>";
                form += "<input type='submit' value='確定'/>";
                form += "</form>";
                form += "<button id='add_picture'>新增檔案</button>";
                form += "<button id='send_picture'>確定</button>";
                $("#my_infor").html(form);
            })
            /*
             * 新增可選擇的檔案
             *
             */
            $("body").on("click","#add_picture",function(){
                $("#select_ad").append("<input name='file[]' type='file'></br>");
            })
        });
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
		<div id='context'>
			<table id='table'>
				<tr>
					<td id='left'>
						<div>
								<?php
									echo "<button id='member_infor' value='".$_SESSION['userid']."'>基本資料管理</button>";
									echo "<p><button id='member_favorite' value='".$_SESSION['userid']."'>我的最愛</button></p>";
									if(isset($_SESSION['level'])){
										if($_SESSION['level']=="管理員"){
                                            echo "<button id='manager_index'>管理首頁</button>";
											echo "<p><button id='member_manager'>影片管理</button><br></p>";
										}
									}
								?>
						</div>
					</td>
					<td id='right'>
						<div id="button_area"></div>
						<div id="my_infor"></div>    
						<div id='error_log'></div>
					</td>
				</tr>
			</table>
		</div>
    </div>
    <div id='hidden_category' style="display:none">
        <?php
            echo $form_category;
        ?>
    </div>
</body>

</html>