<?php
    /*
     *
     * 該檔案有 Bug , 修改資料後無法顯示最新的興趣
     *
     *
     */
    session_start();
    /*
     * 下拉清單用
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
     */
    $sql = "Select `CATEGORY` From `member` Where `MEMBER_ID` = '".$_SESSION['userid']."'";
    $result = $conn -> query($sql);
    $result = $result -> fetchAll();
    $favorite_category =  explode(",",$result[0]["CATEGORY"]);
    /*
     * Hidden category 區域要裝的東西
     *
     */
    $form_category = "";
    /*
     * 處理換行用
     *
     */
    $index = 0;
    foreach ($Allcategory as $row) {
        /*
         * 每 4 個換一次行
         *
         */
        if($index==4){
            $form_category .= "</br>";
            $index = 0;
        }
        /*
         * 該變數用來判斷是否找到會員喜歡的種類
         *
         */
        $find = false;
        /*
         * 當找到會員喜歡的種類時 將其設為 checked
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
    <link type="text/css" rel="stylesheet" href="../css/index.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
        /*
         * 取得會員詳細資料
         *
         */
        $(function(){
            var member_id = $("#member_infor").val();
            $("#member_infor").click(function() {
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
                        infor_html += "<input type='hidden' id='id' value='"+output[0]+"'></br>";
                        infor_html += "會員帳號：<div id='user_account'>"+output[2]+"</div></br>";
                        infor_html += "會員名稱：<div id='user_name'>"+output[1]+"</div></br>";
                        infor_html += "會員密碼：<div id='user_password'>"+output[3]+"</div></br>";
                        infor_html += "生日：<div id='user_birthday'>"+output[4]+"</div></br>";
                        infor_html += "信箱：<div id='user_email'>"+output[5]+"</div></br>";
                        infor_html += "手機：<div id='user_phone_num'>"+output[6]+"</div></br>";
                        if(output[7] == 0){
                            infor_html += "性別：<div id='user_gender'>男</div></br>";
                        }else if(output[7] == 1){
                            infor_html += "性別：<div id='user_gender'>女</div></br>";
                        }else{
                            infor_html += "性別：<div id='user_gender'>第三性</div></br>";
                        }
                        infor_html += "興趣：</br><div>"+$("#hidden_category").html()+"</div>";
                        infor_html += "<input type='hidden' name='gender' value='"+output[7]+"'></br>";
                        infor_html += "工作：<div id='user_job'>"+output[8]+"</div></br>";
                        infor_html += "等級：<div id='user_level'>"+output[10]+"</div></br>";
                        infor_html += "<button id='update'>修改</button>"
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
                infor_html += "生日：<input type='text' name='member_birthday' value='"+$("#user_birthday").text()+"'></br>";
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
             */
            $("body").on("click","#detail_form_send",function () {
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
                    },
                    error: function (request, status, error) {
                        $("#error_log").html(request.responseText);
                    }
                })
            })
            /*
             * 取得會員的我的最愛
             *
             */
            $("#member_favorite").click(function(){
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
                            favorite_infor += "<td>"+output[i]["STORY"]+"</td>";
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
        <table id='content'>
        <tr>
        <td id='left'>
            <div id="context">
                    <?php
                        echo "<button id='member_infor' value='".$_SESSION['userid']."'>基本資料管理</button></br>";
                        echo "<button id='member_favorite' onclick='' >我的最愛</button>";
                    ?>
            </div>
        </td>
        <td id='right'>
            <div id="my_infor"></div>    
            <div id='error_log'></div>
        </td>
        </tr>
        </table>
    </div>
    <div id='hidden_category' style="display:none">
        <?php
            echo $form_category;
        ?>
    </div>
</body>

</html>