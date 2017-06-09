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
    /*
     * 依照條件搜尋影片
     * limit 不等於 null , 為分頁用的資料
     * limit 等於 null , 為計算資料總數用的 Sql
     *
     */
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);
    function search_function($search,$category,$kind,$limit){
        /*
         * 建立資料庫連線
         *
         */
        global $conn;
        if($limit != null){
            $sql = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`SCORE`,`RELEASE_DATE`,`PHOTO` From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_NAME` Like '%".$search."%' And ".$category." And ".$kind." Order by `RELEASE_DATE` desc ".$limit;
        }else{
            $sql = "Select `VIDEO_ID`,`VIDEO_NAME`,`CATEGORY_NAME`,`KIND_NAME`,`LANGUAGE`,`SCORE`,`RELEASE_DATE`,`PHOTO` From `video` Join `kind` On `video`.`KIND_ID` = `kind`.`KIND_ID` Join `category` On `video`.`CATEGORY_ID` = `category`.`CATEGORY_ID` Where `VIDEO_NAME` Like '%".$search."%' And ".$category." And ".$kind;
        }

        $result = $conn -> query($sql);
        return $result;
    }
	if(isset($_GET['search'],$_GET['kind'],$_GET['category'])){
		$search = addslashes($_GET['search']);
		$kind_ = $_GET['kind'];
		$category_ = $_GET['category'];
		/*
		 * 如果沒有指定類別或種類 就搜尋全部
		 *
		 */
		if($kind_ == "0"){
			$kind_ = "`video`.`KIND_ID` LIKE '%%'";
		}else{
			$kind_ = "`video`.`KIND_ID` = '".addslashes($_GET['kind'])."'";
		}
		if($category_ == "0"){
			$category_ = "`video`.`CATEGORY_ID` LIKE '%%'";
		}else{
			$category_ = "`video`.`CATEGORY_ID` = '".addslashes($_GET['category'])."'";
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
		 * count_search 為要計算總數的查詢
		 *
		 */
		$result = search_function($search,$category_,$kind_,"LIMIT ".($now_pages*10-10).",10");
		$count_search = search_function($search,$category_,$kind_,null) -> fetchAll();
		/*
		 * 計算有幾筆資料
		 *
		 */
		$all_num = count($count_search);
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
		 * $table 為要輸出的影片 table
		 *
		 */
		$table = "";
		foreach ($result as $row) {
			$table .= "<tr>";
			$table .= "<td><div><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'><img src='".$row['PHOTO']."' height='100%'></a></div></td>";
			$table .= "<th><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</th>";
			$table .= "<td>".$row['CATEGORY_NAME']."</td>";
			$table .= "<td>".$row['KIND_NAME']."</td>";
			$table .= "<td>".$row['LANGUAGE']."</td>";
			$table .= "<td>".$row['RELEASE_DATE']."</td>";
			$table .= "<td><label class='score'>".$row['SCORE']."</label></td>";
			$table .= "</tr>";
		}
		/*
		 * 頁碼設定
		 *
		 */
		if($all_num==0){
			
		}
		else if($now_pages == 1 && $all_num==1){
			$table .= "<tr><td colspan='8' align='center'>".$page_list."</td></tr>";
		}else if($now_pages == 1 && $all_num!=1){
			$table .= "<tr><td colspan='8' align='center'>".$page_list."<a href='./Page_SearchList.php?page=".($now_pages+1)."&search=".$_GET['search']."&kind=".$_GET['kind']."&category=".$_GET['category']."'>下一頁</a></td></tr>";
		}
		else if($now_pages==$all_num){
			$table .= "<tr><td colspan='8' align='center'><a href='./Page_SearchList.php?page=".($now_pages-1)."&search=".$_GET['search']."&kind=".$_GET['kind']."&category=".$_GET['category']."'>前一頁</a>".$page_list."</td></tr>";
		}else if($now_pages>$all_num){
			echo "<script>alert('查無資料');history.go(-1)</script>";
		}else{
			$table .= "<tr><td colspan='8' align='center'><a href='./Page_SearchList.php?page=".($now_pages-1)."&search=".$_GET['search']."&kind=".$_GET['kind']."&category=".$_GET['category']."'>前一頁</a>".$page_list." <a href='./Page_SearchList.php?page=".($now_pages+1)."&search=".$_GET['search']."&kind=".$_GET['kind']."&category=".$_GET['category']."'>下一頁</a></td></tr>";
		}
		/*
		 * 搜尋所有種類
		 *
		 */
		$category_table='';
		$category_table .= "<tr>";
		$stmt=$conn->query("SELECT DISTINCT A.CATEGORY_ID, B.CATEGORY_NAME FROM VIDEO A JOIN CATEGORY B ON A.CATEGORY_ID=B.CATEGORY_ID where a.KIND_ID='".addslashes($_GET['kind'])."'");    
		foreach($stmt as $row){ 
			$category_table .= "<td><a href=./Page_SearchList.php?search=".$_GET['search']."&kind=".$_GET['kind']."&category=". $row['CATEGORY_ID']. ">". $row['CATEGORY_NAME']."</a></td>";
			$i++;
			if($i%10==0){
				$category_table .='</tr><tr>';
			}
		}
		$category_table .= "</tr>";
		/* 
		 * 使用下拉選單時 , 存儲條件的地方
		 *
		 */
		echo "<input type='hidden' id='search_hidden' value='".$_GET['search']."'>";
		echo "<input type='hidden' id='category_hidden' value='".$_GET['category']."'>";
		echo "<input type='hidden' id='kind_hidden' value='".$_GET['kind']."'>";
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
                window.location="./Page_SearchList.php?page="+$("#select_page").val()+"&search="+$("#search_hidden").val()+"&kind="+$("#kind_hidden").val()+"&category="+$("#category_hidden").val();
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
			<table align='center'>
                <?php
                    /*
                     * 輸出那一排 選擇種類 的標題
                     *
                     */
					 if(isset($_GET['search'],$_GET['kind'],$_GET['category'])){
						echo $category_table;
					 }
                ?>
            </table>
			<table>
				<tr>
					<td colspan='2'>名稱</td><td>類別</td><td>類型</td><td>語言</td><td>上映日期</td><td>分數</td>
				</tr>
				<?php
				if(isset($_GET['search'],$_GET['kind'],$_GET['category'])){
					echo $table;
				}
				?>
			</table>
		</div>
		<?php
			echo $footer;
		?>
    </div>
</body>

</html>
