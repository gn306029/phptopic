<?php
    session_start();
    /*
	 * include 為產生下拉清單的 Php
	 *
	 */
    include './Page_View_Set.php';
	/*
	 * 帳號與密碼的輸入框
	 *
	 */
?>
<?php
	 function search_function($sql){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $result = $conn -> query($sql);
        return $result;
    }
	/*
	 * 預設頁數
	 *
	 */
	$now_pages = 1;
	/*
	 * 如果有頁數 就使用該頁數
	 *
	 */
    if (isset($_GET['page'])) {
	  /*
	   * 去除所有標點符號
	   *
	   */
	   $now_pages = str_replace(    
		array('!', '"', '#', '$', '%', '&', '\'', '(', ')', '*',    
			'+', ', ', '-', '.', '/', ':', ';', '<', '=', '>',    
			'?', '@', '[', '\\', ']', '^', '_', '`', '{', '|',    
			'}', '~', '；', '﹔', '︰', '﹕', '：', '，', '﹐', '、',    
			'．', '﹒', '˙', '·', '。', '？', '！', '～', '‥', '‧',    
			'′', '〃', '〝', '〞', '‵', '‘', '’', '『', '』', '「',    
			'」', '“', '”', '…', '❞', '❝', '﹁', '﹂', '﹃', '﹄'),    
		'',$_GET['page']);
		if(!is_numeric($now_pages)){
			$now_pages = 1;
		}
    }
    /*
     * result 為要顯示的資料
     * count_actor 為要計算總數的查詢
     *
     */
    $sql = "SELECT * FROM `actor` Order by `ACTOR_NAME` LIMIT ".($now_pages*10-10).",10";
	$result = search_function($sql) -> fetchAll();
    $count_actor = search_function("SELECT `ACTOR_ID` FROM `actor`") -> fetchAll();
    /*
     * 計算有幾筆資料
     *
     */
    $all_num = count($count_actor);
    /*
     * table 為要輸出的演員資料
     *
     */
	$table = "";
    foreach ($result as $row) {
        $table .= "<tr>";
		$table .= "<td><div><a href='./Page_Actor.php?actor_id=".$row['ACTOR_ID']."'><img src='".$row['ACTOR_PHOTO']."' height='100%'></a></div></td>";
        $table .= "<td><a href='./Page_Actor.php?actor_id=".$row['ACTOR_ID']."'>".$row['ACTOR_NAME']."</a></td>";
        $table .= "<th Width='200'>".mb_substr($row['ACTOR_HISTORY'],0,30)."...</th>";
        $table .= "</tr>";
    }
	/*
     * 如果總數不被整除 , 頁碼要加一
     *
     */
    if($all_num % 10 != 0){
        $all_num = floor( $all_num / 10 ) + 1 ;
    }else{
        $all_num = floor( $all_num / 10 );
    }
    /*
     * 新增頁碼到 Select
     *
     */
    $page_list = "<select name='page' id='select_page'>";
    for($i = 1;$i<=$all_num;$i++){
        if($i == $now_pages){
            $page_list .= "<option value='".$i."' selected>".$i."</option>";
        }else{
            $page_list .= "<option value='".$i."' >".$i."</option>";
        }
    }
    $page_list .= "</select>";
    /*
     * 頁碼設定
     *
     */
	if($all_num==0){
		
	}
    else if($now_pages == 1 &&$all_num==1){
        $table .= "<tr><td colspan='8' align='center'>".$page_list."</td></tr>";
    }else if($now_pages == 1 &&$all_num!=1){
		$table .= "<tr><td colspan='8' align='center'>".$page_list."<a href='./Page_ActorList.php?page=".($now_pages+1)."'>下一頁</a></td></tr>";
	}else if($now_pages == $all_num){
        $table .= "<tr><td colspan='8' align='center'><a href='./Page_ActorList.php?page=".($now_pages-1)."'>前一頁</a>".$page_list."</td></tr>";
    }else if($now_pages>$all_num){
		echo "<script>alert('查無資料');history.go(-1)</script>";
	}else{
        $table .= "<tr><td colspan='8' align='center'><a href='./Page_ActorList.php?page=".($now_pages-1)."'>前一頁</a>".$page_list." <a href='./Page_ActorList.php?page=".($now_pages+1)."'>下一頁</a></td></tr>";
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/common.css">
    <link type="text/css" rel="stylesheet" href="../css/search.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script type="text/javascript">
        /*
         * 用下拉選單選擇 Page 時觸發的事件
         *
         */
		$(function(){
            $("#select_page").change(function(){
                window.location="./Page_ActorList.php?page="+$("#select_page").val();
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
			<table>
				<tr>
					<td></td><td>名稱</td><td Width='750'>簡歷</td>
				</tr>
				<?php
					echo $table;
				?>
			</table>
		</div>
		<?php
            echo $footer;
        ?>
    </div>
</body>

</html>
