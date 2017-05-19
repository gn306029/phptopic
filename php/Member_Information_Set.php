<?php
	session_start();
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    function sql_exec($sql){
        global $dsn,$db_user,$db_password;
        $conn = new PDO($dsn,$db_user,$db_password);
        $result = $conn -> exec($sql);
        $conn = null;
        return $result;
    }
    function sql_select($sql){
        global $conn;
        $result = $conn -> query($sql);
        $result = $result -> fetchAll();
        $conn = null;
        return $result;
    }
    /*
     * 判斷 Ajax 動作
     *
     */
    switch($_POST['action']){
    	case 'infor':
            /*
             * 取得使用者資訊
             *
             */
            $sql = "Select * From Member Where member_id = '".$_POST['member_id']."'";
    		$result = sql_select($sql);
    		echo json_encode($result[0]);
    		break;
        case 'favorite':
            /*
             * 取得使用者的最愛
             *
             */
            $sql = "Select `video`.`VIDEO_ID` , `VIDEO_NAME` , `PHOTO`  From `video` Join `favorite` On `video`.`VIDEO_ID` = `favorite`.`VIDEO_ID` Where `MEMBER_ID` = '".$_POST['member_id']."'";
            $result = sql_select($sql);
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
            $sql = "UPDATE `member` SET `MEMBER_NAME`='".$_POST['member_name']."',`MEMBER_PASSWORD`='".$_POST['member_password']."',`MEMBER_BIRTHDAY`='".$_POST['member_birthday']."',`MEMBER_EMAIL`='".$_POST['member_email']."',`MEMBER_PHONE_NUM`='".$_POST['member_phone_num']."',`MEMBER_JOB`='".$_POST['member_job']."' , `CATEGORY` = '".$category."' WHERE `MEMBER_ID` = '".$_POST['id']."'";
            /*
             * 修改成功就更新 SESSION
             *
             */
            try{
                if(sql_exec($sql) == 1){
                    $_SESSION['username'] = $_POST['member_name'];
                }
                $conn = null;
                echo "success";
            }catch(Execption $e){
                $conn = null;
                echo "fail";
            }
            break;
        case 'add_new_video':
            /*
             * 新增影片
             *
             */
            $sql = "INSERT INTO `video`(`VIDEO_NAME`,`RELEASE_DATE`,`CATEGORY_ID`,`LANGUAGE`,`REGION`,`SCORE`,`BUDGET`,`BOXOFFICE`,`PLAYTIME`,`KIND_ID`,`PHOTO`,`STORY`,`TRAIL`) VALUES ('".$_POST['video_name']."','".$_POST['release_date']."','".$_POST['add_category']."','".$_POST['language']."','".$_POST['region']."','".$_POST['score']."','".$_POST['budget']."','".$_POST['boxoffice']."','".$_POST['playtime']."','".$_POST['add_kind']."','".$_POST['photo']."','".$_POST['story']."','".$_POST['trail']."')";
            if((sql_exec($sql))>0){
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
            $sql = "SELECT `VIDEO_ID`,`VIDEO_NAME` From `video`";
            $result = sql_select($sql);
            echo json_encode($result);
            break;
        case 'get_video_detail':
            /*
             * 撈出管理者要更新或刪除的電影內容
             * 若沒有選擇影片就直接回傳 false
             */
            if($_POST['video_id'] != 0){
                $sql = "SELECT * From `video` Where `VIDEO_ID` = '".$_POST['video_id']."'";
                $result = sql_select($sql);
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
            $sql = "UPDATE `video` SET `VIDEO_NAME` = '".$_POST['video_name']."', `RELEASE_DATE` = '".$_POST['release_date']."', `LANGUAGE` = '".$_POST['language']."', `CATEGORY_ID` = '".$_POST['add_category']."', `REGION` = '".$_POST['region']."', `SCORE` = '".$_POST['score']."', `BUDGET` = '".$_POST['budget']."', `BOXOFFICE` = '".$_POST['boxoffice']."', `PLAYTIME` = '".$_POST['playtime']."', `KIND_ID` = '".$_POST['add_kind']."', `PHOTO` = '".$_POST['photo']."', `STORY` = '".$_POST['story']."', `TRAIL` = '".$_POST['trail']."' WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
            try{
                sql_exec($sql);
                echo "success";
            }catch(Execption $e){
                echo $e -> getMessage();
            }
            break;
        case 'delete_video':
            /*
             * 刪除資料
             *
             */
            try{
                $sql = "DELETE FROM `actor_list` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
                sql_exec($sql);
                $sql = "DELETE FROM `commentary` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
                sql_exec($sql);
                $sql = "DELETE FROM `video` WHERE `VIDEO_ID` = '".$_POST['video_id']."'";
                sql_exec($sql);
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
            $result = sql_select($sql);
            echo json_encode($result);
            break;
        case 'rate_drama_top5':
            /*
             * 取得電影排名
             *
             */
            $sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '2' ORDER BY `SCORE` ".$_POST['rate']." LIMIT 5";
            $result = sql_select($sql);
            echo json_encode($result);
            break;
        case 'rate_tvshow_top5':
            /*
             * 取得電影排名
             *
             */
            $sql = "SELECT `VIDEO_ID`,`VIDEO_NAME`,`SCORE`,`PHOTO` FROM `video` Where `KIND_ID` = '3' ORDER BY `SCORE` ".$_POST['rate']." LIMIT 5";
            $result = sql_select($sql);
            echo json_encode($result);
            break;

    }

?>