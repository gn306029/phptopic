<?php
	session_start();
    /*
	 * 下拉式清單用
	 *
	 *
	 */
    include './Page_Search_Set.php';
    $login_form = "<form name='memberlogin' action='./Member_Login.php' method='POST'>";
    $login_form .= "<img src=\"../PIC/top/account.png\" width=\"70px\" />";
    $login_form .= "<input type=\"text\" name=\"MEMBER_ACCOUNT\" /></br>";
    $login_form .= "<img src=\"../PIC/top/password.png\" width=\"70px\" />";
    $login_form .= "<input type=\"password\" name=\"MEMBER_PASSWORD\"></br>";
	$login_form .= "</form>";

    $form_category = "";
    $index = 0;
    foreach ($Allcategory as $row) {
        if($index==4){
            $form_category .= "</br>";
            $index = 0;
        }
        $form_category .= "<input type=\"checkbox\" id=\"REGISTER_CATEGORY\" name=\"REGISTER_CATEGORY[]\" value='".$row["CATEGORY_ID"]."''>".$row['CATEGORY_NAME'];
        $index ++;
    }

?>
<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/index.css">
    <link type="text/css" rel="stylesheet" href="../css/register.css">
    <style>
    input:focus {
        background-color: yellow;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
    /*
	 * 檢查帳號是否重複
	 *
	 *
	 */
    var isInput = false;

    function checkAccount() {
        var REGISTER_ACCOUNT = $('#REGISTER_ACCOUNT').val();
        $.ajax({
            url: "../php/SQL.php",
            data: {
                action: 'checkaccount',
                account: REGISTER_ACCOUNT
            },
            type: 'post',
            success: function(output) {
                if (output == 0) {
                    $('#imply').html("該帳號可以使用");
                    isInput = true;
                } else if (output == 1) {
                    $('#imply').html("該帳號已有人使用");
                    isInput = false;
                }
            }
        })
    }
	/*
	 * 檢查密碼是否一致
	 *
	 *
	 */
    function checkPassword() {
        var DETERMIND_PASSWORD = $('#DETERMIND_PASSWORD').val();
        var REGISTER_PASSWORD = $('#REGISTER_PASSWORD').val();
        if (REGISTER_PASSWORD != DETERMIND_PASSWORD) {
            $('#passwordImply').html("密碼不同");
            isInput = false;
        } else {
            $('#passwordImply').html("正確");
            isInput = true;
        }
    }
	/*
	 * 檢查手機格式
	 *
	 *
	 */
    function checkPhoneNum(phone_num) {
        var pattern = /^09\d{8}$/;
        if (pattern.test(phone_num)) {
            $('#phoneImply').html("");
            isInput = true;
        } else {
            $('#phoneImply').html("格式錯誤");
            isInput = false;
        }
    }
	/*
	 * 檢查信箱格式
	 *
	 *
	 */
    function checkEmail(email) {
        var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
        if (pattern.test(email)) {
            $('#emailImply').html("");
            isInput = true;
        } else {
            $('#emailImply').html("格式錯誤");
            isInput = false;
        }
    }
	/*
	 * 檢查表單是否遺漏以及格式是否正確
	 *
	 *
	 */
    $(document).ready(function() {
        $('#btn').click(function() {
            var input = true;
            if ($('#REGISTER_ACCOUNT').val() == "") {
                input = false;
            }
            if ($('#REGISTER_PASSWORD').val() == "") {
                input = false;
            }
            if ($('#DETERMIND_PASSWORD').val() == "") {
                input = false;
            }
            if ($('#REGISTER_NAME').val() == "") {
                input = false;
            }
            if ($('#REGISTER_BIRTHDAY').val() == "") {
                input = false;
            }
            if ($('#REGISTER_PHONE_NUM').val() == "") {
                input = false;
            }
            if ($('#REGISTER_EMAIL').val() == "") {
                input = false;
            }
            if (input && isInput) {
                $.post("./Member_Register_Set.php", $('#register_form').serialize(), function(data) {
                    if (data.substring(1) == "Insert成功") {
                        alert("成功");
                    } else if (data.substring(1) == "Insert失敗") {
                        alert("失敗");
                    } else {
                        alert("其他狀況");
                    }
					window.location.href = "../index.php";
                })
            } else {
                alert("請檢查表單輸入是否確實");
            }
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
                                <img src="../PIC/top/searchbutton.png" onclick="document.search.submit()" width="42px">
                            </form>
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
        <div id="context">
            <form id="register_form">
                <p> 　帳　　號　：
                    <input type="text" id="REGISTER_ACCOUNT" name="REGISTER_ACCOUNT" onchange="checkAccount()" /><span id="imply"></span></p>
                <p> 　密　　碼　：
                    <input type="password" id="REGISTER_PASSWORD" name="REGISTER_PASSWORD" onchange="checkPassword()" />
                </p>
                <p> 　確認密碼　：
                    <input type="password" id="DETERMIND_PASSWORD" name="DETERMIND_PASSWORD" onchange="checkPassword()"><span id="passwordImply"></span></p>
                <p> 　姓　　名　：
                    <input type="text" id="REGISTER_NAME" name="REGISTER_NAME" />
                </p>
                <p> 　生　　日　：
                    <input type="date" id="REGISTER_BIRTHDAY" name="REGISTER_BIRTHDAY" style="width:173px" />
                </p>
                <p> 　信　　箱　：
                    <input type="email" id="REGISTER_EMAIL" name="REGISTER_EMAIL" onchange="checkEmail(this.value)" /><span id="emailImply"></span></p>
                <p> 　手　　機　：
                    <input type="text" id="REGISTER_PHONE_NUM" name="REGISTER_PHONE_NUM" onchange="checkPhoneNum(this.value)" /><span id="phoneImply"></span></p>
                <p> 　性　　別　：
                    <input type="radio" id="REGISTER_GENDER" name="REGISTER_GENDER" value="0" checked>男
                    <input type="radio" id="REGISTER_GENDER" name="REGISTER_GENDER" value="1">女</p>
                <p> 　工　　作　：
                    <input type="text" id="REGISTER_JOB" name="REGISTER_JOB" />
                </p>
                <p> 喜愛電影類型：<br>
                    <?php
                        echo $form_category;
                    ?>
                    <input type="checkbox" id="REGISTER_CATEGORY" name="REGISTER_CATEGORY[]" value="0" checked>其他
            </form>
			<p align="right">
				<input type="button" id="btn" name="button" value="註冊" />
			</p>
        </div>
    </div>
</body>

</html>
