<?php
	session_start();
	$db_host = 'db.mis.kuas.edu.tw';
    $db_name = 's1104137130';
    $db_user = 's1104137130';
    $db_password = '1314520';
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $conn = new PDO($dsn,$db_user,$db_password);

    function get_infor(){
    	//使用全域變數
    	global $conn;
    	$sql = "Select * From Member Where member_id = '".$_POST['member_id']."'";
    	$result = $conn -> query($sql);
    	$result = $result -> fetchAll();
    	return $result;
    }

    switch($_POST['action']){
    	case 'infor':
    		$result = get_infor();
    		//echo json_encode($result);
    		echo json_encode($result[0]);
    		break;
    }

?>