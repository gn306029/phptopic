<?php
	session_start();
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    function get_infor(){
    	global $conn;
    	$sql = "Select * From Member Where member_id = '".$_POST['member_id']."'";
    	$result = $conn -> query($sql);
    	$result = $result -> fetchAll();
        $conn = null;
    	return $result;
    }

    /*
     * 更新使用者資訊
     *
     */
    function update_infor(){
        global $conn;
        $category = "";
        for($i = 0 ; $i < count($_POST['member_category']);$i++){
             $category .= $_POST['member_category'][$i];
             if($i != count($_POST['member_category'])-1){
                $category .= ",";
             }
        }
        $sql = "UPDATE `member` SET `MEMBER_NAME`='".$_POST['member_name']."',`MEMBER_PASSWORD`='".$_POST['member_password']."',`MEMBER_BIRTHDAY`='".$_POST['member_birthday']."',`MEMBER_EMAIL`='".$_POST['member_email']."',`MEMBER_PHONE_NUM`='".$_POST['member_phone_num']."',`MEMBER_JOB`='".$_POST['member_job']."' , `CATEGORY` = '".$category."' WHERE `MEMBER_ID` = '".$_POST['id']."'";
        try{
            $result = $conn -> exec($sql);
            $_SESSION['username'] = $_POST['member_name'];
            $conn = null;
            return "success";
        }catch(Execption $e){
            $conn = null;
            return "fail";
        }
    }

    /*
     * 取得使用者的最愛
     *
     */
    function get_favorite(){
        global $conn;
        $favorite = "";
        $sql = "Select `video`.`VIDEO_ID` , `VIDEO_NAME` , `PHOTO` , `STORY` From `video` Join `favorite` On `video`.`VIDEO_ID` = `favorite`.`VIDEO_ID` Where `MEMBER_ID` = '".$_POST['member_id']."'";
        $result = $conn -> query($sql);
        $conn = null;
        return $result;
        

    }

    /*
     * 判斷 Ajax 動作
     *
     */
    switch($_POST['action']){
    	case 'infor':
    		$result = get_infor();
    		echo json_encode($result[0]);
    		break;
        case 'favorite':
            $result = get_favorite() -> fetchAll();
            echo json_encode($result);
            break;
        case 'Update':
            echo update_infor();
            break;
    }

?>