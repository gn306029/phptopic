﻿<?php
	session_start();
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    function sql_exec($sql,$array){
        global $dsn,$db_user,$db_password;
        $conn = new PDO($dsn,$db_user,$db_password);
        $stmt = $conn -> prepare($sql);
        if(isset($array)){
            $stmt -> execute($array);
        }else{
            $stmt -> execute();
        }
        //$result = $conn -> exec($sql);
        $conn = null;
        return $stmt;
    }
    function sql_select($sql,$array){
        global $conn;
        $stmt = $conn -> prepare($sql);
        if(isset($array)){
            $stmt -> execute($array);
        }else{
            $stmt -> execute();
        }
        $conn = null;
        return $stmt->fetchAll();
    }
    /*
     * 判斷 Ajax 動作
     *
     */
	 if(isset($_POST['action'])){
		switch($_POST['action']){
			case 'infor':
				/*
				 * 取得使用者資訊
				 *
				 */
				$sql = "Select * From Member Where member_id = ?";
				$array = array($_POST['member_id']);
				$result = sql_select($sql,$array);
				echo json_encode($result[0]);
				break;
			case 'favorite':
				/*
				 * 取得使用者的最愛
				 *
				 */
				$sql = "Select `video`.`VIDEO_ID` , `VIDEO_NAME` , `PHOTO` From `video` Join `favorite` On `video`.`VIDEO_ID` = `favorite`.`VIDEO_ID` Where `MEMBER_ID` =  ?";
				$array = array($_POST['member_id']);
				$result = sql_select($sql,$array);
				echo json_encode($result);
				break;
			case 'Update':
				/*
				 * 更新使用者資訊
				 * category 為把喜歡的類別整理成字串
				 *
				 */
				$category = "";
				for($i = 0 ; $i < count($_POST['member_category']);$i++){
					 $category .= $_POST['member_category'][$i];
					 if($i != count($_POST['member_category'])-1){
						$category .= ",";
					 }
				}
				$sql = "UPDATE `member` SET `MEMBER_NAME`= ? ,`MEMBER_PASSWORD`= ? ,`MEMBER_BIRTHDAY`= ? ,`MEMBER_EMAIL`= ? ,`MEMBER_PHONE_NUM`= ? ,`MEMBER_JOB`= ? , `CATEGORY` = ? WHERE `MEMBER_ID` = ?";
				$array = array($_POST['member_name'],$_POST['member_password'],$_POST['member_birthday'],$_POST['member_email'],$_POST['member_phone_num'],$_POST['member_job'],$category,$_POST['id']);
				/*
				 * 修改成功就更新 SESSION
				 *
				 */
				$result = sql_exec($sql,$array);
				try{
					if(gettype($result) != false){
						$_SESSION['username'] = $_POST['member_name'];
						echo "success";
					}
				}catch(Execption $e){
					echo "fail";
				}
				break;
			case 'add_new_video':
				/*
				 * 新增影片
				 *
				 */
				$sql = "INSERT INTO `video`(`VIDEO_NAME`,`RELEASE_DATE`,`CATEGORY_ID`,`LANGUAGE`,`REGION`,`SCORE`,`BUDGET`,`BOXOFFICE`,`PLAYTIME`,`KIND_ID`,`PHOTO`,`STORY`,`TRAIL`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
				$array = array($_POST['video_name'],$_POST['release_date'],$_POST['add_category'],$_POST['language'],$_POST['region'],0,$_POST['budget'],$_POST['boxoffice'],$_POST['playtime'],$_POST['add_kind'],$_POST['photo'],$_POST['story'],$_POST['trail']);
				$test = sql_exec($sql,$array);
				if(gettype($test) != false){
					echo "success";
				}else{
					echo "fail";
				}
				break;
			case 'get_video':
				/*
				 * 撈出所有影片 id 與名稱
				 *
				 */
				$sql = "SELECT `VIDEO_ID`,`VIDEO_NAME` From `video` order by `VIDEO_NAME`";
				$result = sql_select($sql,null);
				echo json_encode($result);
				break;
			case 'get_video_detail':
				/*
				 * 撈出管理者要更新或刪除的電影內容
				 * 若沒有選擇影片就直接回傳 false
				 */
				if($_POST['video_id'] != 0){
					$sql = "SELECT * From `video` Where `VIDEO_ID` = ?";
					$array = array($_POST['video_id']);
					$result = sql_select($sql,$array);
					echo json_encode($result);
				}else{
					echo "false";
				}
				break;
			case 'update_video':
				/*
				 * 更新資料
				 * 這裡尚未做資料格式的檢查
				 *
				 */
				$sql = "UPDATE `video` SET `VIDEO_NAME` = ?, `RELEASE_DATE` = ?, `LANGUAGE` = ?, `CATEGORY_ID` = ?, `REGION` = ?, `BUDGET` = ?, `BOXOFFICE` = ?, `PLAYTIME` = ?, `KIND_ID` = ?, `PHOTO` = ?, `STORY` = ?, `TRAIL` = ? WHERE `VIDEO_ID` = ?";
				$array = array($_POST['video_name'],$_POST['release_date'],$_POST['language'],$_POST['add_category'],$_POST['region'],$_POST['budget'],$_POST['boxoffice'],$_POST['playtime'],$_POST['add_kind'],$_POST['photo'],$_POST['story'],$_POST['trail'],$_POST['video_id']);
				try{
					sql_exec($sql,$array);
					echo "success";
				}catch(Execption $e){
					echo $e -> getMessage();
				}
				break;
			case 'delete_video':
				/*
				 *刪除資料
				 *
				 *
				 */
				try{
					$sql = "DELETE FROM `actor_list` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
					sql_exec($sql,null);
					$sql = "DELETE FROM `commentary` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
					sql_exec($sql,null);
					$sql = "DELETE FROM `rating` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
					sql_exec($sql,null);
					$sql = "DELETE FROM `favorite` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
					sql_exec($sql,null);
					$sql = "DELETE FROM `video` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
					sql_exec($sql,null);
					echo "success";
				}catch(Exception $e){
					echo $e -> getMessage();
				}
				break;
			case 'rate_movie_top5':
				/*
				 * 取得電影排名
				 *
				 */
				$sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '1' ORDER BY `SCORE` ".$_POST['rate']." LIMIT 5";
				$result = sql_select($sql,null);
				echo json_encode($result);
				break;
			case 'rate_drama_top5':
				/*
				 * 取得戲劇排名
				 *
				 */
				$sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '3' ORDER BY `SCORE` ".$_POST['rate']." LIMIT 5";
				$result = sql_select($sql,null);
				echo json_encode($result);
				break;
			case 'rate_tvshow_top5':
				/*
				 * 取得綜藝排名
				 *
				 */
				$sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '2' ORDER BY `SCORE` ".$_POST['rate']." LIMIT 5";
				$result = sql_select($sql,null);
				echo json_encode($result);
				break;
			case 'add_new_actor':
				/*
				 * 新增演員
				 *
				 */
				//$sql = "INSERT INTO `actor`(`ACTOR_NAME`, `ACTOR_Birth`, `ACTOR_HISTORY`, `ACTOR_PHOTO`, `ACTOR_FB`) VALUES ('".$_POST['actor_name']."','".$_POST['actor_birth']."','".$_POST['actor_history']."','".$_POST['actor_photo']."','".$_POST['actor_fb']."')";
				$sql = "INSERT INTO `actor`(`ACTOR_NAME`, `ACTOR_Birth`, `ACTOR_HISTORY`, `ACTOR_PHOTO`, `ACTOR_FB`) VALUES (?,?,?,?,?)";
				$array = array($_POST['actor_name'],$_POST['actor_birth'],$_POST['actor_history'],$_POST['actor_photo'],$_POST['actor_fb']);
				if(gettype(sql_exec($sql,$array)) != false){
					$sql = "SELECT MAX(ACTOR_ID) FROM actor";
					$result = sql_select($sql,null);
					echo json_encode($result);
				}else{
					echo "fail";
				}
				break;
			case 'add_new_actorlist':
				/*
				 * 新增演員清單
				 *
				 */
				if(!(isset($_POST['video_list']))){
					echo "success";
					break;
				}
				$actor_list=$_POST['video_list'];
				foreach($actor_list as $row){
					$sql = "INSERT INTO `actor_list`(`ACTOR_ID`, `VIDEO_ID`) VALUES (?,?)";
					$array = array($_POST['actorid'],$row);
					$result = sql_exec($sql,$array);
				}
				echo "success" ;
				break;	  
			case 'get_actor':
				/*
				 * 撈出所有演員 id 與名稱
				 *
				 */
				$sql = "SELECT `ACTOR_ID`,`ACTOR_NAME` From `ACTOR` order by `ACTOR_NAME`";
				$result = sql_select($sql,null);
				echo json_encode($result);
				break;
			case 'get_actor_detail':
				/*
				 * 撈出管理者要更新或刪除的演員內容
				 * 若沒有選擇演員就直接回傳 false
				 */
				if($_POST['actor_id'] != 0){
					$sql = "SELECT a.`ACTOR_ID`,`ACTOR_NAME`,`ACTOR_Birth`,`ACTOR_HISTORY`,`ACTOR_PHOTO`,`ACTOR_FB`,c.`VIDEO_ID`,`VIDEO_NAME` FROM `actor`as a left join `actor_list`as b on a.actor_id=b.actor_id LEFT join video as c on b.video_id=c.VIDEO_ID where a.actor_id= ? ";
					$array=array($_POST['actor_id']);
					$result = sql_select($sql,$array);
					echo json_encode($result) ;
				}else{
					echo "false";
				}
				break;
			case 'do_actor_list':
				/*
				 * 取得actor_list
				 * 
				 */
				try{
					$sql = "DELETE FROM `actor_list` WHERE `ACTOR_ID`= ? ";
					$array=array($_POST['actor_id']);
					$result = sql_exec($sql,$array);
					if(!(isset($_POST['video_list']))){
						echo "success";
						break;
					}
					$new_video_id=$_POST['video_list'];
					foreach($new_video_id as $row){
						$sql = "INSERT INTO `actor_list`(`ACTOR_ID`, `VIDEO_ID`) VALUES (?,?)";
						$array=array($_POST['actor_id'],$row);
						$result = sql_exec($sql,$array);
					}
					echo "success";
				}catch(Execption $e){
					echo $e -> getMessage();
				}
				break;
			case 'update_actor':
				/*
				 * 更新演員資料
				 * 這裡尚未做資料格式的檢查
				 *
				 */
				$sql = "UPDATE `actor` SET `ACTOR_NAME` = ?, `ACTOR_Birth`=?, `ACTOR_HISTORY`= ?, `ACTOR_PHOTO`= ?, `ACTOR_FB`= ? where `ACTOR_ID`=?";
				$array = array($_POST['actor_name'],$_POST['birth'],$_POST['history'],$_POST['actor_photo'],$_POST['actor_fb'],$_POST['actor_id']);
				try{
					sql_exec($sql,$array);
					echo "success";
				}catch(Execption $e){
					echo $e -> getMessage();
				}
				break;
			case 'delete_actor':
				/*
				 * 刪除演員
				 * 
				 *
				 */
				try{
					$sql = "DELETE FROM `actor_list` WHERE `ACTOR_ID` = '".$_POST['actor_id']."'";
					sql_exec($sql,null);
					$sql = "DELETE FROM `ACTOR` WHERE `ACTOR_ID` = '".$_POST['actor_id']."'";
					sql_exec($sql,null);
					echo "success";
				}catch(Exception $e){
					echo $e -> getMessage();
				}
				break;
			case 'checkaccount':
				/*
				 * 檢查帳號是否重複
				 *
				 */
				$sql = "SELECT MEMBER_ACCOUNT FROM MEMBER WHERE MEMBER_ACCOUNT = ?";
				$array = array($_POST['account']);
				$result = sql_select($sql,$array);
				echo json_encode($result);
				break;
			case 'video_favorite':
				if(isset($_SESSION['userid'],$_POST['videoid'])){
					$sql = "Select `video`.`VIDEO_NAME` From `video` Join `favorite` On `video`.`VIDEO_ID` = `favorite`.`VIDEO_ID` Where `MEMBER_ID` = '".$_SESSION['userid']."' AND `video`.`VIDEO_ID`='".$_POST['videoid']."'";
					$result = sql_select($sql,null);
					if($result==null){
						$sql = "INSERT INTO `favorite` (`MEMBER_ID`, `VIDEO_ID`, `FAVORITE_ID`) VALUES ('".$_SESSION['userid']."','".$_POST['videoid']."',null)";
						$result =sql_exec($sql,null);
						echo "1";
					}else{
						$sql = "DELETE FROM `favorite` WHERE `MEMBER_ID`='".$_SESSION['userid']."' AND `VIDEO_ID`='".$_POST['videoid']."'";
						$result =sql_exec($sql,null);
						echo "0";
					}
				}
				break;
		}
	 }
?>