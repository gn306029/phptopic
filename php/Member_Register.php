<?php
	session_start();
    /*
	 * 下拉式清單用
	 *
	 *
	 */
    include './Page_View_Set.php';
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
	if(isset($_SESSION['username'])){
		echo "<script>alert('你註冊過了 你他媽滾回去');location.href='../index.php';</script>" ;
	}
?>
<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/common.css">
    <link type="text/css" rel="stylesheet" href="../css/register.css">
    <style>
    input:focus {
        background-color: yellow;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="../js/checkspecial.js"></script>
    <script type="text/javascript">
    /*
	 * 檢查帳號是否重複
	 *
	 *
	 */
    var isInput = [true,true,true,true,true,true];
    function checkAccount() {
        var REGISTER_ACCOUNT = $('#REGISTER_ACCOUNT').val();
        if(REGISTER_ACCOUNT.length != 0){
            if(!checkspecial(REGISTER_ACCOUNT)&& !checkchinese(REGISTER_ACCOUNT)){
                $.ajax({
                    url: "./Member_Information_Set.php",
                    data: {
                        action: 'checkaccount',
                        account: REGISTER_ACCOUNT
                    },
                    type: 'post',
                    success: function(output) {
                        var result = JSON.parse(output);
                        if (result.length == 0) {
                            $('#imply').html("該帳號可以使用");
                            isInput[0] = true;
                        } else if (result.length == 1) {
                            $('#imply').html("該帳號已有人使用");
                            isInput[0] = false;
                        }
                    }
                })
            }else{
                $('#imply').html("不得包含特殊字元及中文字");
                isInput[0] = false;
            }
        }else{
            $('#imply').html("");
            isInput[0] = false;
        }
    }
	/*
	 * 檢查密碼是否一致
	 *
	 *
	 */
    function checkPassword() {
        var DETERMIND_PASSWORD = $('#DETERMIND_PASSWORD').val();
        var REGISTER_PASSWORD = $('#REGISTER_PASSWORD').val();
        if(!checkspecial(DETERMIND_PASSWORD) && !checkspecial(REGISTER_PASSWORD)){
            if (REGISTER_PASSWORD != DETERMIND_PASSWORD) {
                $('#passwordImply').html("密碼不同");
                isInput[1] = false;
            } else {
                $('#passwordImply').html("正確");
                isInput[1] = true;
            }
        }else{
            $('#passwordImply').html("不得包含特殊字元及中文字");
            isInput[1] = false;
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
            isInput[2] = true;
        } else {
            $('#phoneImply').html("格式錯誤");
            isInput[2] = false;
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
            isInput[3] = true;
        } else {
            $('#emailImply').html("格式錯誤");
            isInput[3] = false;
        }
    }
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
            if (input && isInput[0] && isInput[1] && isInput[2] && isInput[3] && isInput[4] && isInput[5]) {
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
        $("#REGISTER_JOB").change(function(){
            if(checkspecial($(this).val())){
                $('#job_imply').html("不得包含特殊字元及中文字");
                isInput[4] = false;
            }else{
                $('#job_imply').html("");
                isInput[4] = true;
            }
        })
        $("#REGISTER_NAME").change(function(){
            if(checkspecial($(this).val())){
                $('#name_imply').html("不得包含特殊字元及中文字");
                isInput[5] = false;
            }else{
                $('#name_imply').html("");
                isInput[5] = true;
            }
        })
        $("#REGISTER_PASSWORD").change(function(){
            if(checkspecial($(this).val())){
                $('#password_Imply').html("不得包含特殊字元及中文字");
                $('#passwordImply').html("");
                if(checkspecial($("#DETERMIND_PASSWORD").val())){
                    $('#password_Imply').html("不得包含特殊字元及中文字");
                }
                isInput[1] = false;
            }else{
                if(checkspecial($("#DETERMIND_PASSWORD").val())){
                    $('#password_Imply').html("不得包含特殊字元及中文字");
                    isInput[1] = false;
                }else{
                    $('#password_Imply').html("");
                    isInput[1] = true;
                }
            }
        })
        $("#DETERMIND_PASSWORD").change(function(){
            if(checkspecial($(this).val())){
                $('#passwordImply').html("不得包含特殊字元及中文字");
                isInput[1] = false;
            }else{
                $('#passwordImply').html("");
                if(!checkspecial($("#REGISTER_PASSWORD").val())){
                    var DETERMIND_PASSWORD = $('#DETERMIND_PASSWORD').val();
                    var REGISTER_PASSWORD = $('#REGISTER_PASSWORD').val();
                    if (REGISTER_PASSWORD != DETERMIND_PASSWORD) {
                        $('#passwordImply').html("密碼不同");
                        $('#password_Imply').html("");
                       isInput[1] = false;
                    } else {
                        $('#password_Imply').html("");
                        $('#passwordImply').html("正確");
                        isInput[1] = true;
                    }
                }else{
                    $('#password_Imply').html("不得包含特殊字元及中文字");
                    isInput[1] = false;
                }
            }
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
            <form id="register_form">
                <p> 　帳　　號　：
                    <input type="text" id="REGISTER_ACCOUNT" name="REGISTER_ACCOUNT" onchange="checkAccount()" /><span id="imply"></span></p>
                <p> 　密　　碼　：
                    <input type="password" id="REGISTER_PASSWORD" name="REGISTER_PASSWORD" onchange="checkPassword()" /><span id="password_Imply"></span>
                </p>
                <p> 　確認密碼　：
                    <input type="password" id="DETERMIND_PASSWORD" name="DETERMIND_PASSWORD" onchange="checkPassword()"><span id="passwordImply"></span></p>
                <p> 　姓　　名　：
                    <input type="text" id="REGISTER_NAME" name="REGISTER_NAME" /><span id="name_imply"></span>
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
                    <input type="text" id="REGISTER_JOB" name="REGISTER_JOB" /><span id="job_imply"></span></p>
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
		<?php
            echo $footer;
        ?>
    </div>
</body>

</html>