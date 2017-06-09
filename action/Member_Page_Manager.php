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
    include './Page_View_Set.php';
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
	 if(isset($_SESSION['userid'])){
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
    <script src="../js/manager.js"></script>
</head>

<body>
    <div id="main">
        <?php
            echo $div_header;
        ?>
		<div id='context'>
			<table id='table'>
				<tr>
					<td id='left'>
						<div>
								<?php
									if(isset($_SESSION['level'])){
										echo "<button id='member_infor' value='".$_SESSION['userid']."'>基本資料管理</button>";
										echo "<p><button id='member_favorite' value='".$_SESSION['userid']."'>我的最愛</button></p>";
										if($_SESSION['level']=="管理員"){
											echo "<button id='manager_index'>管理首頁</button>";
											echo "<p><button id='member_manager'>影片管理</button></p>";
											echo "<p><button id='actor_manager'>演員管理</button></p>";
										}
									}else{
                                        echo "尚未登入";
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
		<?php
            echo $footer;
        ?>
    </div>
    <div id='hidden_category' style="display:none">
        <?php
            echo $form_category;
        ?>
    </div>
</body>

</html>