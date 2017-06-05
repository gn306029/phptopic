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
    /*
	 * 建立連線
	 *
	 *
	 */
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
    <link type="text/css" rel="stylesheet" href="../css/common.css" />
    <link type="text/css" rel="stylesheet" href="../css/manager.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="../js/checkspecial.js"></script>
    <script type="text/javascript">
        /*
		 * 取得會員詳細資料
		 *
		 *
		 */
        $(function(){
            /*
             * 格式檢查
             *
             */
            var ischeck = [true,true,true,true,true,true,true];
            $("body").on("change","#video_detail_name",function(){
                if(checkspecial($(this).val())){
                    $("#imply_name").html("不得包含特殊字元");
                    ischeck[0] = false;
                }else{
                    $("#imply_name").html("");
                    ischeck[0] = true;
                }
            })
            $("body").on("change","#video_detail_language",function(){
                if(checkspecial($(this).val())){
                    $("#imply_language").html("不得包含特殊字元");
                    ischeck[1] = false;
                }else{
                    $("#imply_language").html("");
                    ischeck[1] = true;
                }
            })
            $("body").on("change","#video_detail_region",function(){
                if(checkspecial($(this).val())){
                    $("#imply_region").html("不得包含特殊字元");
                    ischeck[2] = false;
                }else{
                    $("#imply_region").html("");
                    ischeck[2] = true;
                }
            })
            $("body").on("change","#video_detail_score",function(){
                if(checkspecial($(this).val())){
                    $("#imply_score").html("不得包含特殊字元");
                    ischeck[3] = false;
                }else{
                    if(!isNaN($(this).val())){
                        if($(this).val()<=0 || $(this).val()>=11){
                            $("#imply_score").html("分數只能在 1 ~ 10 之間");
                            ischeck[3] = false;
                        }else{
                            $("#imply_score").html("");
                            ischeck[3] = true;
                        }
                    }else{
                        $("#imply_score").html("只能輸入數字");
                        ischeck[3] = false;
                    }
                }
            })
            $("body").on("change","#video_detail_budget",function(){
                if(checkspecial($(this).val())){
                    $("#imply_budget").html("不得包含特殊字元");
                    ischeck[4] = false;
                }else{
                    if(!isNaN($(this).val())){
                        if($(this).val()<=-1){
                            $("#imply_budget").html("預算不得為負");
                            ischeck[4] = false;
                        }else{
                            $("#imply_budget").html("");
                            ischeck[4] = true;
                        }
                    }else{
                        $("#imply_budget").html("只能輸入數字");
                        ischeck[4] = false;
                    }
                }
            })
            $("body").on("change","#video_detail_boxoffice",function(){
                if(checkspecial($(this).val())){
                    $("#imply_boxoffice").html("不得包含特殊字元");
                    ischeck[5] = false;
                }else{
                    if(!isNaN($(this).val())){
                        if($(this).val()<=-1){
                            $("#imply_boxoffice").html("票房不得為負");
                            ischeck[5] = false;
                        }else{
                            $("#imply_boxoffice").html("");
                            ischeck[5] = true;
                        }
                    }else{
                        $("#imply_boxoffice").html("只能輸入數字");
                        ischeck[5] = false;
                    }
                }
            })
            $("body").on("change","#video_detail_playtime",function(){
                if(checkspecial($(this).val())){
                    $("#imply_playtime").html("不得包含特殊字元");
                   ischeck[6] = false;
                }else{
                    var check = $(this).val().split(":");
                    if(check.length != 3){
                        $("#imply_playtime").html("請依照 XX:XX:XX 格式輸入");
                        ischeck[6] = false;
                    }else{
                        var timecheck = false;
                        if(check[0] < "00" || check[0] > "23" || check[1] < "00" || check[1] > "59" || check[2] < "00" || check[2] > "59"){
                            timecheck = false;
                        }else{
                            if(check[0] == "24"){
                                if(check[1] == "00" && check[2] == "00"){
                                    timecheck = true;
                                }else{
                                    timecheck = false;
                                }
                            }else{
                                timecheck = true;
                            }
                        }
                        if(!timecheck){
                            $("#imply_playtime").html("時間格式有誤");
                            ischeck[6] = false;
                        }else{
                            $("#imply_playtime").html("");
                            ischeck[6] = true;
                        }
                    }
                }
            })
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
                        var favorite_infor = "<table width=100%><tr><td></td><td>影片名稱</td><td></td></tr>";
                        for(var i =0; i<Object.keys(output).length;i++){
                            favorite_infor += "<tr>";
                            favorite_infor += "<td width='7%'><div id='circlepic'><a href='./Page_Actor.php?actor_id="+output[i]["VIDEO_ID"]+"'><img src='"+output[i]["PHOTO"]+"' height='100%'></a></div></td>";
                            favorite_infor += "<td width='73%'><a href='./Page_Video.php?VIDEO_ID="+output[i]["VIDEO_ID"]+"' >"+output[i]["VIDEO_NAME"]+"</a></td>";
							favorite_infor += "<td width='20%'><button id='member_delete_favorite' value='"+output[i]["VIDEO_ID"]+"'>刪除</button></td>";
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
			 *刪除會員的我的最愛
			 *
			 *
			 */
			 $("body").on("click","#member_delete_favorite",function(){
				 var button=$(this).parent().parent();
				 $.ajax({
					url:"./Member_Favorite.php",
					data:{
						videoid:$(this).val()
					},
					type:"post",
					dataType:"json",
					success:function(output){
						if(output=='0'){
							button.remove();
						}else{
							alert("無法刪除");
						}
					},
					error: function (request, status, error) {
						alert(request.responseText);
						$("#error_log").html(request.responseText);
					}
				})
			 });
			/*
			 * 管理首頁
             *
             */
            $("#manager_index").click(function(){
				$("#my_infor").html("");
                $("#button_area").html("<button id='manager_ad'>廣告管理</button>");
            })
            /*
             * 廣告管理
             *
             */
            $("body").on("click","#manager_ad",function(){
                var form = "<form id='select_ad' enctype='multipart/form-data' action='./Member_Save_Picture.php' method='post'>";
                form += "<input name='file[]' type='file'></br>";
                form += "</form>";
                form += "<button id='add_picture'>新增檔案</button>";
                form += "<button id='send_picture'>上傳</button>";
                $("#my_infor").html(form);
            })
            /*
             * 新增可選擇的檔案
             *
             */
            $("body").on("click","#add_picture",function(){
                $("#select_ad").append("<input name='file[]' type='file'></br>");
            })
			$("body").on("click","#send_picture",function(){
                $("#select_ad").submit();
            })												
			/*
             * 建立影片管理者介面
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
				video_infor += "<p>影片名稱：<input type='text' id='video_detail_name' name='video_name' /><span id='imply_name'></span></p>";
                video_infor += "<p>上映日期：<input type='date' id='video_detail_release' name='release_date' /></p>";
                video_infor += "<p>影片種類：<select name='add_kind'>"+$("[name=kind]").html()+"</select></p>";
                video_infor += "<p>影片類別：<select name='add_category'>"+$("[name=category]").html()+"</select></p>";
				video_infor += "<p>影片語言：<input type='text' id='video_detail_language' name='language' /><span id='imply_language'></span></p>";
                video_infor += "<p>影片地區：<input type='text' id='video_detail_region' name='region' /><span id='imply_region'></span></p>";
                video_infor += "<p>影片預算：<input type='text' id='video_detail_budget' name='budget' /><span id='imply_budget'></span></p>";
                video_infor += "<p>影片票房：<input type='text' id='video_detail_boxoffice' name='boxoffice' value='0'/><span id='imply_boxoffice'></span></p>";
                video_infor += "<p>影片長度：<input type='text' id='video_detail_playtime' name='playtime' /><span id='imply_playtime'>  EX:23:59:59</span></p>";
                video_infor += "<p>圖片網址：<input type='url' id='video_detail_photo' name='photo' /><span>  該欄位可以為空</span></p>";
                video_infor += "<p>影片簡介：<textarea name='story'></textarea><span>  該欄位可以為空</span></p>";
                video_infor += "<p>Youtube影片編號：<input type='text' name='trail' id='video_detail_trail'/><span>  該欄位可以為空</span></p>";
                video_infor += "</form>";
                video_infor += "<button id='do_add_new_video'>新增</button>";
                $("#my_infor").html(video_infor);
            })
            /*
             * 確定新增
             *
             */
            $("body").on("click","#do_add_new_video",function(){
				//判斷所有欄位是否都輸入
                var form_status = true;
                $("#add_new_video_form input").each(function(){
                    if($(this).val() == ""){
                        if(!(($(this).is($("#video_detail_trail"))) || ($(this).is($("#video_detail_photo"))))){
                            form_status = false;
                        }
                    }
				});
				if(ischeck[0] && ischeck[1] && ischeck[2] && ischeck[3] && ischeck[4] && ischeck[5] && ischeck[6] && form_status){
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
                }else{
                    alert("請檢察表單是否輸入確實");
                }
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
			 *清除SELECT列
			 *
			 *
			 */
			 $("body").on("click",".del",function(){
				 var index = $("ol img").index(this);
				 $('li').slice(index,(index+1)).remove();
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
							html += "<p>影片名稱：<input type='text' id='video_detail_name' name='video_name' value='"+output[0]['VIDEO_NAME']+"'><span id='imply_name'></span></p>";
                            html += "<p>上映日期：<input type='date' id='video_detail_release' name='release_date' value='"+output[0]['RELEASE_DATE']+"'/></p>";
                            html += "<p>影片種類：<select name='add_kind'>"+output_kind+"</select></p>";
                            html += "<p>影片類別：<select name='add_category'>"+output_category+"</select></p>";
                            html += "<p>影片語言：<input type='text' id='video_detail_language' name='language' value='"+output[0]['LANGUAGE']+"'/><span id='imply_language'></span></p>";
                            html += "<p>影片地區：<input type='text' id='video_detail_region' name='region' value='"+output[0]['REGION']+"'/><span id='imply_region'></span></p>";
                            html += "<p>影片預算：<input type='text' id='video_detail_budget' name='budget' value='"+output[0]['BUDGET']+"'/><span id='imply_budget'></span></p>";
                            html += "<p>影片票房：<input type='text' id='video_detail_boxoffice' name='boxoffice' value='"+output[0]['BOXOFFICE']+"'/><span id='imply_boxoffice'></span></p>";
                            html += "<p>影片長度：<input type='text' id='video_detail_playtime' name='playtime' value='"+output[0]['PLAYTIME']+"'/><span id='imply_playtime'></span></p>";
                            html += "<p>圖片網址：<input type='url' id='video_detail_photo' name='photo' value='"+output[0]['PHOTO']+"'/></p>";
                            html += "<p>影片簡介：<textarea name='story'>"+output[0]['STORY']+"</textarea></p>";
                            html += "<p>Youtube影片編號：<input type='text' id='video_detail_trail' name='trail' value='"+output[0]['TRAIL']+"'/></p>";
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
                var form_status = true;
                $("#video_detail input").each(function(){
                    if($(this).val() == ""){
                        if(!(($(this).is($("#video_detail_trail"))) || ($(this).is($("#video_detail_photo"))))){
                            form_status = false;
                        }
                    }
                });
                if(ischeck && form_status){
                    $.ajax({
                        url:"./Member_Information_Set.php",
                        data:$("#video_detail").serialize() + "&action=update_video",
                        type:"post",
                        success:function(output){
                            if(output == "success"){
                                alert("更新成功");
								$("#my_infor").html('');
                            }else{
                                alert(output);
                            }
                        },
                        error: function (request, status, error) {
                            $("#error_log").html(request.responseText);
                        }
                    })
                }else{
                    alert("請檢察表單是否輸入確實");
                }
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
             * 建立演員管理介面
             *
			 */
            $("#actor_manager").click(function(){
                $("#my_infor").html("");
                $("#button_area").html("<button id='add_new_actor'>新增演員</button>　"
                                     +"<button id='get_actor_detail'>演員修改與刪除</button>");
             })
			/*
             * 建立新增演員的欄位
             *
             */
            $("body").on("click","#add_new_actor",function(){
                actor_infor = "<form id='add_new_actor_form'>";
                actor_infor += "<p>演員名稱：<input type='text' name='actor_name' /><span id='imply_actorname'></span></p>";
                actor_infor += "<p>演員生日：<input type='date' name='actor_birth' /><span id='imply_actorbirth'></span></p>";
                actor_infor += "<p>演員生平：<input type='text' name='actor_history' /></p>";
                actor_infor += "<p>圖片網址：<input type='url' name='actor_photo' /></p>";
                actor_infor += "<p>Facebook：<input type='url' name='actor_fb' /></p>";
                actor_infor += "</form >";
				actor_infor += "<form id='add_actorlist_form'>";
				actor_infor += "<ol id='video_list'></ol>";
				actor_infor += "</form>";
				actor_infor += "<button id='add_actorlist'>增加影片</button>　";
				actor_infor += "<button id='do_add_new_actor' >新增演員</button>";
                $("#my_infor").html(actor_infor);
            })
			/*
             * 判斷所有欄位是否都輸入
             *
			 *
             */
			var ischeckactor = [true,true];
            $("body").on("change","[name='actor_name']",function(){
                if(checkspecial($(this).val())){
                    $("#imply_actorname").html("不得包含特殊字元");
                    ischeckactor[0] = false;
                }else if($(this).val()==''){
					$("#imply_actorname").html("不得為空");
                    ischeckactor[0] = false;
				}else{
                    $("#imply_actorname").html("");
                    ischeckactor[0] = true;
                }
            })
			$("body").on("change","[name='actor_birth']",function(){
				if($(this).val()==''){
					$("#imply_actorbirth").html("不得空白");
                    ischeckactor[1] = false;
				}else{
                    $("#imply_actorbirth").html("");
                    ischeckactor[1] = true;
                }
            })
			/*
             * 取得演員清單
             *
			 *
             */
			$("body").on("click","#add_actorlist",function(){
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:{
                        action:"get_video"
                    },
                    type:"post",
                    dataType:"json",
                    success:function(output){
						var select_tag = "<select name='video_list[]'>";
                        select_tag += "<option value='0' selected>---------------請選擇---------------</option>";
                        for(var i =0;i<output.length;i++){
                            select_tag += "<option value='"+output[i]['VIDEO_ID']+"'>"+output[i]['VIDEO_NAME']+"</option>";
                        }
                        select_tag += "</select><img style=\"vertical-align:middle;\" src=\"../PIC/top/delete.jpg\" class=\"del\" height=\"40px\"><br>";
                        $("#video_list").append("<li>請選擇電影名稱："+select_tag+"</li>");
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                 })
            })
			/*
             * 確定新增演員
             *
             */
            $("body").on("click","#do_add_new_actor",function(){
				var form_status = true;
                $("#add_new_actor_form input").each(function(){
                    if($(this).val() == ""){
                        if(!(($(this).is($("[name='actor_history']"))) || ($(this).is($("[name='actor_photo']"))) || ($(this).is($("[name='actor_fb']"))))){
                            form_status = false;
                        }
                    }
				});
				if(ischeckactor[0] && ischeckactor[1]&&form_status){
					$.ajax({
						url:"./Member_Information_Set.php",
						data:$("#add_new_actor_form").serialize()+"&action=add_new_actor",
						type:"post",
						dataType:"json",
						success:function(output){
							var actorid=output[0]['MAX(ACTOR_ID)'];
							$.ajax({
								url:"./Member_Information_Set.php",
								data:$("#add_actorlist_form").serialize()+"&actorid="+actorid+"&action=add_new_actorlist",
								type:"post",
								success:function(output){
									if(output=='success'){
										$("#my_infor").html("");
										alert('新增成功');
									}else{
										alert('新增失敗');
									}
								},
								error: function (request, status, error) {
									$("#error_log").html(request.responseText);
								}
							})
						},
						error: function (request, status, error) {
							$("#error_log").html(request.responseText);
						}
					})
				}else{
					alert("請檢察表單是否輸入確實");
				}
            })
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			/*
             * 取出影片資料欄位
             * 用 ajax 抓出所有影片
             * 更新與刪除都使用這個方法
             *
             */
            $("body").on("click","#get_actor_detail",function(){
                 $.ajax({
                    url:"./Member_Information_Set.php",
                    data:{
                        action:"get_actor"
                    },
                    type:"post",
                    dataType:"json",
                    success:function(output){
                        $("#my_infor").html("");
                        var select_tag = "<select name='actor_select'>";
                        select_tag += "<option value='0' selected>---------------請選擇---------------</option>";
                        for(var i =0;i<output.length;i++){
                            select_tag += "<option value='"+output[i]['ACTOR_ID']+"'>"+output[i]['ACTOR_NAME']+"</option>";
                        }
                        select_tag += "</select>";
                        $("#my_infor").html("請選擇演員名稱："+select_tag);
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
            $("body").on("change","[name=actor_select]",function(){
                $.ajax({
                    url:"./Member_Information_Set.php",
                    data:{
                        action:"get_actor_detail",
                        actor_id:$("[name=actor_select]").val()
                    },
                    type:"post",
                    dataType:"json",
                    success:function(output){
                        /*
                         * 把該部演員的detail設為預設
                         *
                         */
						if(output != "false"){
                            html = "<form id='actor_detail'>";
                            html += "<input type='hidden' name='actor_id' value='"+output[0]['ACTOR_ID']+"'/>";
							html += "<p>演員名稱：<input type='text' id='actor_detail_name' name='actor_name' value='"+output[0]['ACTOR_NAME']+"'><span id='imply_actorname'></span></p>";
							html += "<p>演員生日：<input type='date' id='actor_detail_birth' name='birth' value='"+output[0]['ACTOR_Birth']+"'><span id='imply_actorbirth'></span></p>";
							html += "<p>演員生平：<input type='text' id='actor_detail_history' name='history' value='"+output[0]['ACTOR_HISTORY']+"'></p>";
							html += "<p>演員照片：<input type='text' id='actor_detail_photo' name='actor_photo' value='"+output[0]['ACTOR_PHOTO']+"'></p>";
							html += "<p>演員臉書：<input type='text' id='actor_detail_fb' name='actor_fb' value='"+output[0]['ACTOR_FB']+"'></p>";
                            html += "</form><form id='get_actor_list'><ol id='video_list'>";
							var vid =new Array();
							if(output[0]['VIDEO_ID']==null){
								vid[0]=0;
								
							}else{
								for(var i =0;i<output.length;i++){
									vid [i] =output[i]['VIDEO_ID'];
								}
								var a="";
								$.ajax({
									url:"./Member_Information_Set.php",
									data:{
										action:"get_video"
									},
									type:"post",
									dataType:"json",
									success:function(output){
										for(var i =0;i<vid.length;i++){
											a +="<li>請選擇電影名稱：<select name='video_list[]'>";
											a += "<option value='0' selected>---------------請選擇---------------</option>";
											for(var j=0;j<output.length;j++){
												if(vid[i]==output[j]['VIDEO_ID']){
													a += "<option value='"+output[j]['VIDEO_ID']+"' selected>"+output[j]['VIDEO_NAME']+"</option>";
												}else{
													a += "<option value='"+output[j]['VIDEO_ID']+"'>"+output[j]['VIDEO_NAME']+"</option>";
												}
											
											}
											a +="</select><img style=\"vertical-align:middle;\" src=\"../PIC/top/delete.jpg\" class=\"del\" height=\"40px\"><br>";
											
										}
										$("#video_list").append(a);
										
									},
									error: function (request, status, error) {
										$("#error_log").html(request.responseText);
									}
								})
							}
							html += "</ol></form><br><button id=add_actorlist>增加影片</button><br><br>";
							html += "<button id='do_update_actor'>更新</button>　";
							html += "<button id='do_delete_actor'>刪除演員</button>";
							$("#my_infor").html(html);
                        }else{
                            alert("請選擇演員");
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
             $("body").on("click","#do_update_actor",function(){
                var form_status = true;
                $("#actor_detail input").each(function(){
                    if($(this).val() == ""){
                        if(!(($(this).is($("[name='history']"))) || ($(this).is($("[name='actor_photo']"))) || ($(this).is($("[name='actor_fb']"))))){
                            form_status = false;
                        }
                    }
                });
                if(ischeckactor[0]&&ischeckactor[1]&&form_status){
                    $.ajax({
                        url:"./Member_Information_Set.php",
                        data:$("#actor_detail").serialize() + "&action=update_actor",
                        type:"post",
                        success:function(output){
							if(output=='success'){
								$.ajax({
								url:"./Member_Information_Set.php",
								data:$("#get_actor_list").serialize() + "&action=do_actor_list"+"&actor_id="+$("[name=actor_id]").val(),
								type:"post",
								success:function(output){
									if(output == "success"){
										alert('更新成功');
										$("#my_infor").html("");
									}else{
										alert('更新失敗'+output);
									}
								},
								error: function (request, status, error) {
									$("#error_log").html(error);
								}
								})
							}else{
								alert('更新失敗'+output);
							}
							
                        },
                        error: function (request, status, error) {
                            $("#error_log").html(request.responseText);
                        }
                    })
                }else{
                    alert("請表單是否輸入確實");
                }
            })
            /*
             * 建立刪除影片的欄位
             * 用 ajax 把相關資料丟到後台做相關處理
             *
             */
            $("body").on("click","#do_delete_actor",function(){
                if(confirm("確定刪除這部影片嗎?")){
                    if(confirm("真的?")){
						$.ajax({
							url:"./Member_Information_Set.php",
							data:{
								action:"delete_actor",
								actor_id:$("[name=actor_id]").val()
							},
							type:"post",
							success:function(output){
								if(output == "success"){
									alert("刪除成功");
									var msg = "<div>被刪除的演員就像變了心的基友，回不來了</div>";
									msg += "<img src='http://img.ltn.com.tw/Upload/ent/page/800/2015/09/18/1449261_2.jpg'></img>";
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
            })
			$("body").on("click","#video_list select",function(){
				var last_val = $(this).val();
				$("body").on("change","#video_list select",function(){
					var tmp = $(this);
					$("#video_list select").each(function(){
						
						if(tmp.val()!=0){
							if(!($(this).is(tmp))){
								if(tmp.val()==$(this).val()){
									$(this).val('0');
								}
								$("option[value="+tmp.val()+" ]",this).hide();
								$("option[value="+last_val+" ]",this).show();
							}
						}
					})
				})
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
                        <a href="Page_SearchList.php?search=&kind=1&category=0" onMouseOut="document.movie.src='../PIC/top/movie.png'" onMouseOver="document.movie.src='../PIC/top/movie-1.png'"><img src="../PIC/top/movie.png" name="movie" width="70px"></a> 
                        <a href="Page_SearchList.php?search=&kind=3&category=0" onMouseOut="document.drama.src='../PIC/top/drama.png'" onMouseOver="document.drama.src='../PIC/top/drama-1.png'"><img src="../PIC/top/drama.png" name="drama" width="70px"></a> 
                        <a href="Page_SearchList.php?search=&kind=2&category=0" onMouseOut="document.tvshow.src='../PIC/top/tvshow.png'" onMouseOver="document.tvshow.src='../PIC/top/tvshow-1.png'"><img src="../PIC/top/tvshow.png" name="tvshow" width="70px"></a> 
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
											echo "<p><button id='member_manager'>影片管理</button></p>";
											echo "<p><button id='actor_manager'>演員管理</button></p>";
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
		<footer><table><tr>
				<td><a href="./About.php?action=Me"><img height="36" border="0" alter="關於" src="../PIC/footer/about.png"></a></td>
				<td><a href="./About.php?action=Dev"><img height="36" border="0" alter="開發人員" src="../PIC/footer/dev.png"></a></td>
				<td><div><a href="https://line.me/R/ti/p/%40gib2079k"><img height="36" border="0" alt="加入好友" src="https://scdn.line-apps.com/n/line_add_friends/btn/zh-Hant.png"></a></div></td>
				
			</tr>
			<tr>
				<td colspan=3>© 2017 IMDB,KUASMIS</td>
			</tr></table></footer>
    </div>
    <div id='hidden_category' style="display:none">
        <?php
            echo $form_category;
        ?>
    </div>
</body>

</html>