<?php
    session_start();
	/*
     * include 為基本版面
     *
     */
    include './Page_View_Set.php';
?>
<?php
    function actor_detail($sql,$array){
        $db_host = 'db.mis.kuas.edu.tw';
        $db_name = 's1104137130';
        $db_user = 's1104137130';
        $db_password = '1314520';
        $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
        $conn = new PDO($dsn,$db_user,$db_password);
        $stmt = $conn -> prepare($sql);
        $stmt -> execute($array);
        return $stmt -> fetchAll();
    }
	if(isset($_GET['actor_id'])){
		$sql = "SELECT `ACTOR_NAME`,`ACTOR_HISTORY`,`ACTOR_PHOTO`,`ACTOR_FB`,AcTOR_BIRTH From `actor` Where `ACTOR_ID` = ?";
		$array = array($_GET['actor_id']);
		$detail = actor_detail($sql,$array);
		$sql = "SELECT `actor_list`.`VIDEO_ID`,`VIDEO_NAME`,`PHOTO` FROM `actor_list` JOIN `video` ON `actor_list`.`VIDEO_ID` = `video`.`VIDEO_ID` Where `Actor_ID` = ?";
		$actor_list = actor_detail($sql,$array);
	}
?>

<!DOCTYPE html>
<html>

<head>
    <title>IMDB</title>
	<link type="text/css" rel="stylesheet" href="../css/common.css">
    <link type="text/css" rel="stylesheet" href="../css/actor.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
    <div id="main">
        <?php
            echo $div_header;
        ?>
        <div id="context">
			<table>
                <?php
                    if(isset($detail[0][0])){
                        echo "<tr>";
                        echo "<td width=50%><img id='PIC' src='".$detail[0][2]."'></td>";
                        echo "<td width=50%>";
                        if($detail[0][3]!=''){
                            echo "<p id='actor_name' style='color:hotpink;'>".$detail[0][0]."<a href=".$detail[0][3]."><img id='FB' src='../PIC/top/FB.png'></a></p>";
                        }else{
                            echo "<p id='actor_name' style='color:hotpink;'>".$detail[0][0];
                        }
                        echo "<p>生日：".$detail[0][4]."</p>";
                        echo "<p style='color:hotpink;'>介紹</p>";
                        echo "<p>".$detail[0][1]."<a href='https://zh.wikipedia.org/wiki/".$detail[0][0]."'/>詳全文</a></p></td>";
                        echo "</tr>";
                    }else{
                        echo "無此藝人";
                    }
                ?>
			</table>
            <table id="actor_list">
                <?php
                    if(isset($detail[0][0])){
                        echo "<tr><th colspan='5'><p style='color:hotpink;'>作品列表</p></th></tr>";
						$list="<tr>";
						$i=1;
                        foreach ($actor_list as $row) {
							if($i%6!=0){
								$list.="<td><div><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'><img src='".$row['PHOTO']."' height='100%'></a></div><br><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</a></td>";
							}else{
								$list.="</tr><tr><td><div><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'><img src='".$row['PHOTO']."' height='100%'></a></div><br><a href='./Page_Video.php?VIDEO_ID=".$row['VIDEO_ID']."'>".$row['VIDEO_NAME']."</a></td>";
							}
							$i++;
                        }
						$list.="</tr>";
						echo $list;
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