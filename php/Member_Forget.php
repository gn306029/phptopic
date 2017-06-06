<?php
    session_start();
    include './Page_View_Set.php';
?>


<!DOCTYPE html>
<html>

	<head>
		<title>忘記密碼</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="../css/common.css">
		<link type="text/css" rel="stylesheet" href="../css/video.css">
		<link href="../js/jquery.loading.css" rel="stylesheet">	
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="../js/checkspecial.js"></script>
		<script src="../js/jquery.loading.js"></script>
		<script type="text/javascript">
			$(function(){
				var ischeck = [true,true];
				$("#username").change(function(){
					if(checkspecial($(this).val())){
						ischeck[0] = false;
					}else{
						ischeck[0] = true;
					}
				})
				$("#email").change(function(){
					var pattern = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/;
			        if (pattern.test($(this).val())) {
			            ischeck[1] = true;
			        } else {
			            ischeck[1] = false;
			        }
				})
				$("#send").click(function(){
					if(ischeck[0] && ischeck[1] && $("#username").val().length != 0 && $("#email").val().length != 0){
						$("body").loading({
						  stoppable: true
						});
						$.ajax({
							url:"./Member_Forget_Set.php",
							data:$("#data").serialize(),
							type:"post",
							success:function(output){
								setTimeout(function(){
									$("body").loading('stop');
									if(output=="err"){
										alert("寄信時出現錯誤，請洽管理員");
									}else if(output == "no data"){
										alert("資料有誤");
									}else if(output == "success"){
										alert("寄信成功");
									}
								},500)
							},
							error: function (request, status, error) {
			                   $("#error_log").html(request.responseText);
			            	}
						})
					}else{
						alert("請檢察表單輸入是否確實");
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
				<form id='data' method="POST">
					<p>帳　　號：<input type='text' name='username' id="username"/></p>
					<p>電子郵件：<input type='email' name='email' id="email"/></br></p>
					<input type='button' id='send' value='送出' />
				</form>
			</div>
			<?php
				echo $footer;
			?>
		</div>
	</body>
</html>